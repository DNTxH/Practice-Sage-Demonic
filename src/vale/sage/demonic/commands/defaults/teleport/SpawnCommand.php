<?php

namespace vale\sage\demonic\commands\defaults\teleport;

use vale\sage\demonic\tasks\types\TeleportationTask;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class SpawnCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("spawn", "Teleport to spawn.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        $player = $sender;

        if(isset($args[0])){
            if(!$sender->hasPermission("spawn.command.admin")){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "You don't have permission teleport other players to spawn");
                return;
            } else {
                $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]);

                if($player === null){
                    $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Player not found");
                    return;
                }
            }
        } else {
            if(!$sender instanceof Player){
                return;
            }
        }

        $timer = 7;
        $exp = 0;

        if($player->getXpManager()->getCurrentTotalXp() >= 1000){
            $player->getXpManager()->subtractXp(1000);
            $timer = 0;
        }elseif($player->getXpManager()->getCurrentTotalXp() > 0){
            $exp = $player->getXpManager()->getCurrentTotalXp();
            $timer = 7 - round(142 / $exp);
            $player->getXpManager()->subtractXp($exp);
        }

        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportationTask(Loader::getInstance(), $player, Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->getSpawnLocation(), $exp, $timer), 20);

        $player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Teleporting To Spawn");

        if($player !== $sender){
            $sender->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "Teleporting " . C::YELLOW . $player->getDisplayName() . C::GREEN . " To Spawn");
        }
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}