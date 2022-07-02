<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\privateVaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use SOFe\AwaitGenerator\Await;
use vale\sage\demonic\privatevault\VaultCache;
use vale\sage\demonic\privatevault\Vault;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;

class PrivateVaultsCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("privatevaults", "Access your private vaults.", null, ["pvs", "pv"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return bool
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if(!$sender instanceof Player){
            return false;
        }
        if(!isset($args[0])){
            $sender->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "Usage: /privatevault <number>");
            return false;
        }
        $number = intval($args[0]);
        if($number <= 0){
            $number = 1;
            return false;
        }
        if(!$sender->hasPermission("privatevault.command." . $number) && !$sender->getServer()->isOp($sender->getName())){
            $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "No Permission to use private vault #" . $number);
            return false;
        }
        $username = $sender->getName();
        if(isset($args[1]) && $sender->hasPermission("privatevault.command.admin")){
            $username = $args[1];
            $player = Loader::getInstance()->getServer()->getPlayerByPrefix($args[1]);
            if($player instanceof Player){
                $username = $player->getName();
            }
        }
        if(($vault = VaultCache::getFromCache($username . "." . $number)) instanceof Vault){
            if($vault->isLoading()){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Vault is currently loading, please try it again later.");
                return false;
            }
            if($vault->isUnloading()){
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Vault is currenly unloading, please try it again later.");
                return false;
            }
            $vault->getMenu()->send($sender);
            return true;
        }
        Await::f2c(function() use ($sender, $username, $number){
            $vault = yield Loader::getPrivateVaultDB()->loadVault($username, $number);
            $vault->getMenu()->send($sender);
        });
        return true;
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}