<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\entity\Human;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Loader;

class TrojanAPI
{
    public static function addCps(Player $player){
        if(isset(Loader::$trojan["cps"]["cache"][$player->getName()]))
            Loader::$trojan["cps"]["cache"][$player->getName()] = Loader::$trojan["cps"]["cache"][$player->getName()] + 1;
        else
            Loader::$trojan["cps"]["cache"][$player->getName()] = 1;
    }

    public static function resetCacheCps(string $player_name){
        if(isset(Loader::$trojan["cps"]["cache"][$player_name]))
            Loader::$trojan["cps"]["cache"][$player_name] = 0;
    }

    public static function resetMovingCache(string $player_name)
    {
        if (isset(Loader::$trojan["moving"]["cache"][$player_name])){
            Loader::$trojan["moving"]["cache"][$player_name]["move"] = 0;
        }
    }

    public static function setCps(string $player_name,int $cps){
        Loader::$trojan["cps"]["cps"][$player_name] = $cps;
    }

    public static function setMoving(string $player_name,int $cps){
        Loader::$trojan["moving"]["moving"][$player_name] = $cps;
    }

    public static function addMoving(Player $player){
        if(isset(Loader::$trojan["moving"]["cache"][$player->getName()]["move"]))
            Loader::$trojan["moving"]["cache"][$player->getName()]["move"] = (Loader::$trojan["moving"]["cache"][$player->getName()]["move"] + 1);
        else
            Loader::$trojan["moving"]["cache"][$player->getName()]["move"] = 1;
    }

    public static function getLiveMoving(string $player_name){
        return Loader::$trojan["moving"]["cache"][$player_name]["move"] ?? 0;
    }

    public static function getLiveCps(string $player_name){
        return Loader::$trojan["cps"]["cache"][$player_name] ?? 0;
    }

    private static function getData(): Config{
        return new Config(Loader::getInstance()->getServer()->getDataPath() . "player_flags.yml",Config::YAML,array(
            "cps" => [],
        ));
    }

    public static function addFlag(string $player_name,string $type,?bool $high_ping = false){
        $data = self::getData();
        $list = $data->getAll();
        switch ($type){
            case "cps":
                if(isset($list[$player_name]["cps_warning"])){
                    $list[$player_name]["cps_warning"] = $list[$player_name]["cps_warning"] + 1;
                } else {
                    $list[$player_name]["cps_warning"] = 1;
                }
                if($high_ping){
                    if(isset($list[$player_name]["cps_high_ping"])){
                        $list[$player_name]["cps_high_ping"] = $list[$player_name]["cps_high_ping"] + 1;
                    } else {
                        $list[$player_name]["cps_high_ping"] = 1;
                    }
                }
                break;
            case "moving":
                if(isset($list[$player_name]["moving_warning"])){
                    $list[$player_name]["moving_warning"] = ($list[$player_name]["moving_warning"] + 1);
                } else {
                    $list[$player_name]["moving_warning"] = 1;
                }
                if($high_ping){
                    if(isset($list[$player_name]["moving_high_ping"])){
                        $list[$player_name]["moving_high_ping"] = $list[$player_name]["moving_high_ping"] + 1;
                    } else {
                        $list[$player_name]["moving_high_ping"] = 1;
                    }
                }
                break;
            case "reach":
                if(isset($list[$player_name]["reach_warning"])){
                    $list[$player_name]["reach_warning"] = ($list[$player_name]["reach_warning"] + 1);
                } else {
                    $list[$player_name]["reach_warning"] = 1;
                }
                if($high_ping){
                    if(isset($list[$player_name]["reach_high_ping"])){
                        $list[$player_name]["reach_high_ping"] = $list[$player_name]["reach_high_ping"] + 1;
                    } else {
                        $list[$player_name]["reach_high_ping"] = 1;
                    }
                }
                break;
            case "Glitch":
                if(isset($list[$player_name]["Glitch_warning"])){
                    $list[$player_name]["Glitch_warning"] = ($list[$player_name]["Glitch_warning"] + 1);
                } else {
                    $list[$player_name]["Glitch_warning"] = 1;
                }
                return true;
            case "phase":
                if(isset($list[$player_name]["phase_warning"])){
                    $list[$player_name]["phase_warning"] = ($list[$player_name]["phase_warning"] + 1);
                } else {
                    $list[$player_name]["phase_warning"] = 1;
                }
                return true;
            case "bhop":
                if(isset($list[$player_name]["bhop_warning"])){
                    $list[$player_name]["bhop_warning"] = ($list[$player_name]["bhop_warning"] + 1);
                } else {
                    $list[$player_name]["bhop_warning"] = 1;
                }
                return true;

        }
        if(isset($list[$player_name]["last_time"])){
            $time = $list[$player_name]["last_time"];
            $time = strtotime($time);
            $time_now = time();
            if($time_now - $time > (10 * 60)){//10 minute
                //reset
                $list[$player_name]["count"] = 0;
                $list[$player_name]["high_ping"] = 0;
            }
        }
        if(!isset($list[$player_name])) {
            $list[$player_name] = array("count" => 0);
        }
        if(isset($list[$player_name]["count"])){
            $list[$player_name]["count"] = $list[$player_name]["count"] + 1;
        } else {
            $list[$player_name]["count"] = 1;
        }
        if($high_ping){
            if(isset($list[$player_name]["high_ping"])){
                $list[$player_name]["high_ping"] = $list[$player_name]["high_ping"] + 1;
            } else {
                $list[$player_name]["high_ping"] = 1;
            }
            if($list[$player_name]["high_ping"] >= 5){
                if(isset($list[$player_name]["check"])){
                    $list[$player_name]["check"] = $list[$player_name]["check"] + 1;
                } else {
                    $list[$player_name]["check"] = 1;
                }
                $list[$player_name]["high_ping"] = 0;
            }
        }
        $data->setAll($list);
        $data->save();
        return true;
    }

    public static function getFlag(string $player_name,string $type){
        $data = self::getData();
        $data = $data->getAll();
        return match ($type) {
            "cps" => $data[$player_name]["cps_warning"] ?? 0,
            "moving" => $data[$player_name]["moving_warning"] ?? 0,
            default => false,
        };
    }

    /**
     * call to check players check, it will ban player when > 5
     */
    public static function update(string $player_name){
        $data = self::getData()->getAll();
        if(isset($data[$player_name]["check"])){
            if($data[$player_name]["check"] >= 3){
                $ban = new BanManager();
                $player = Loader::getInstance()->getServer()->getPlayerExact($player_name);
                $ban->ban($player,"Ban Hammer was spoken","Ban Hammer");
                TrojanAPI::addLog($player->getName(), "Ban Hammer was spoken", "Ban Hammer","ban");
            }
        }
    }

    public static function Kick(string $player_name,string $reason){
        $player = Loader::getInstance()->getServer()->getPlayerExact($player_name);
        $player?->kick("§c§lYou have been banned from GenesisNetwork\n§r§4§lREASON: §r§c$reason\n§r§cAppeal: https://discord.gg/genesisnetwork");
    }


    public static function getPlayerCps(string $player_name)
    {
        if (isset(Loader::$trojan["cps"]["cps"][$player_name])) {
            return Loader::$trojan["cps"]["cps"][$player_name];
        }
        return 0;
    }

    public static function getPlayerMoving(string $player_name)
    {
        if (isset(Loader::$trojan["moving"]["moving"][$player_name])) {
            return Loader::$trojan["moving"]["moving"][$player_name];
        }
        return 0;
    }

    public static function kickPlayer(Player $player){
        $player->kick("Kicked by admin. Reason:\nCps is too high.");
    }

    private static function getLogConfig(string $player_name){
        @mkdir(Loader::getInstance()->getServer()->getDataPath() . "/player_history/");
        if(file_exists(Loader::getInstance()->getServer()->getDataPath() . "/player_history/" . $player_name . ".yml")){
            $data = new Config(Loader::getInstance()->getServer()->getDataPath() . "/player_history/$player_name.yml", Config::YAML);
        } else {
            $data = new Config(Loader::getInstance()->getServer()->getDataPath() . "/player_history/$player_name.yml", Config::YAML,array(
                "warn" => [],
                "log" => []
            ));
        }
        return $data;
    }

    public static function getLog(string $player_name){
        $data = self::getLogConfig($player_name);
        return $data->get("log");
    }

    public static function addWarn(string $player_name,string $reason,string $wanner){
        $data = self::getLogConfig($player_name);
        $date = date("d/m/Y H:i:s");
        $data->set("warn",array_merge($data->get("warn"),[
            [
                "date" => $date,
                "reason" => $reason,
                "wanner" => $wanner
            ]
        ]));
        $data->save();
    }

    public static function seeWarn(CommandSender $player,string $target){
        $data = self::getLogConfig($target);
        $warn = $data->get("warn");
        $warn = array_reverse($warn);
        if(count($warn) == 0){
            $player->sendMessage("§c§l$target has no warns");
            return;
        }
        $player->sendMessage("§l§cWarns of $target:");
        foreach($warn as $key => $value){
            $player->sendMessage("§l§c".$key+1 ." §r§cReason: §r§c$value[reason] §r§cWarned by: §r§c$value[wanner] §r§cDate: §r§c$value[date]");
        }
    }

    public static function addLog(string $player_name,string $reason,string $wanner,string $type){
        $data = self::getLogConfig($player_name);
        $date = date("d/m/Y H:i:s");
        $data->set("log",array_merge($data->get("log"),[
            [
                "date" => $date,
                "reason" => $reason,
                "causer" => $wanner,
                "type" => $type
            ]
        ]));
        $data->save();
    }
    public static function seeLog(CommandSender $player,string $target){
        $data = self::getLogConfig($target);
        $log = $data->get("log");
        $log = array_reverse($log);
        if(count($log) == 0){
            $player->sendMessage("§c§l$target has no violations log");
            return;
        }
        $player->sendMessage("§l§cviolations logs of $target:");
        foreach($log as $key => $value){
            $player->sendMessage("§l§c".$key+1 ." §r§cReason: §r§c$value[reason] §r§cCaused by: §r§c$value[causer] §r§cDate: §r§c$value[date] §r§cType: §r§c$value[type]");
        }
    }

    public static function setAlert(Player $player,bool $alert){
        $config = self::getLogConfig($player->getName());
        if($config && self::canSetAlert($player)){
            $config->set("alert",$alert);
            $config->save();
            return true;
        }
        return false;
    }

    public static function canSetAlert(Player $player){
        $staffClass = new StaffManager();
        if($staffClass->isInStaffMode($player)){
            return false;
        }
        return true;
    }

    public static function isAlert(Player $player){
        $config = self::getLogConfig($player->getName());
        if($config){
            return $config->get("alert");
        }
        return false;
    }
}