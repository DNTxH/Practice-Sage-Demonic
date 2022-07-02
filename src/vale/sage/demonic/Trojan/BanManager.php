<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\utils\Config;
use vale\sage\demonic\Loader;

class BanManager
{
    private function getBannedConfig(): Config
    {
        return new Config(Loader::getInstance()->getServer()->getDataPath() . "banned-players.yml", Config::YAML);
    }

    public function isBanned(string $player): bool
    {
        return $this->getBannedConfig()->exists($player);
    }

    public function unBan(string $player,$is_blackList = false): bool
    {
        $config = $this->getBannedConfig();
        if ($config->exists($player)) {
            if($is_blackList) {
                if ($config->get($player)["blacklist"] === true) {
                    $config->remove($player);
                    $config->save();
                } else {
                    return false;
                }
            } else {
                if ($config->get($player)["blacklist"] === false) {
                    $config->remove($player);
                    $config->save();
                } else {
                    return false;
                }
            }
            $config->save();
            return true;
        }
        return false;
    }

    public function joinChecker(PlayerPreLoginEvent $event): void
    {
        $config = $this->getBannedConfig();
        $player = $event->getPlayerInfo();
        if ($config->exists($player->getUsername())) {
            $info = $config->get($player->getUsername());
            if($info["blacklist"] === true) {
                $con_ip = $config->get($player->getUsername())["ip"];
                $con_xuid = $config->get($player->getUsername())["xuid"];
                if($con_ip === $event->getIp() || $con_xuid === $player->getXuid()) {
                    $reason = $config->get($player->getUsername())["reason"];
                    $event->setKickReason(0,"§c§lYou are banned from GenesisNetwork\n§r§4§lREASON: §r§c$reason\n§r§cAppeal: https://discord.gg/genesisnetwork");
                }
            } else {
                $time = $config->get($player->getUsername())["banned_until"];
                if($time === "forever"){
                    $reason = $config->get($player->getUsername())["reason"];
                    $event->setKickReason(0,"§c§lYou are banned from GenesisNetwork\n§r§4§lREASON: §r§c$reason\n§r§cAppeal: https://discord.gg/genesisnetwork");
                } else {
                    $time = new \DateTime($time);
                    if(time() >= $time->getTimestamp()){
                        $this->unBan($player->getUsername());
                        TrojanAPI::addLog($player->getUsername(),"times up","BanManager","unban");
                    } else {
                        $reason = $config->get($player->getUsername())["reason"];
                        $event->setKickReason(0,"§c§lYou are temporary banned from GenesisNetwork\n§r§4§lUnban date:§r§c". $time->format("Y-m-d H:i:s") . "\n§r§4§lREASON: §r§c$reason\n§r§cAppeal: https://discord.gg/genesisnetwork");
                    }
                }
            }
        }
    }

    private function kick(Player $player, string $reason): void
    {
        $player->kick("§c§lYou are banned from GenesisNetwork\n§r§4§lREASON: §r§c$reason\n§r§cAppeal: https://discord.gg/genesisnetwork");
    }

    public function ban(Player $player, string $reason,string $banned_by,bool $blackList = false,?\DateTime $dateTime = null): bool
    {
        $config = $this->getBannedConfig();
        if($this->isBanned($player->getName()))
        {
            return false;
        }
        $xuid = $player->getXuid();
        $date = $dateTime ? $dateTime->format("Y-m-d H:i:s") : "forever";
        switch ($blackList){
            case true:
                $ip = $player->getNetworkSession()->getIp();
                $config->setNested($player->getName(), ["reason" => $reason, "banned_by" => $banned_by, "blacklist" => true,"ip" => $ip,"xuid" => $xuid,"banned_until" => $date]);
                break;
            case false:
                $config->setNested($player->getName(), ["reason" => $reason, "banned_by" => $banned_by, "blacklist" => false,"xuid" => $xuid,"banned_until" => $date]);
                break;
        }
        $config->save();
        $this->kick($player, $reason);
        return true;
    }

}