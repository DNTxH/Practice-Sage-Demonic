<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use vale\sage\demonic\privatevault\VaultCache;
use vale\sage\demonic\privatevault\Vault;
use SOFe\AwaitGenerator\Await;
use vale\sage\demonic\Loader;

class ChestSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if(!$sender instanceof Player) {
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		
		if($session->getFaction() == null) {
			$sender->sendMessage("§r§c§l(!) §r§cNo faction!");
			return;
		}
		$fac = $session->getFaction();
        if(($vault = VaultCache::getFromCache($fac->getId())) instanceof Vault) {
            if($vault->isLoading()) {
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Vault is currently loading, please try it again later.");
                return;
            }
            if($vault->isUnloading()) {
                $sender->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Vault is currenly unloading, please try it again later.");
                return;
            }
            $vault->getMenu()->send($sender);
        }
        Await::f2c(function() use ($sender, $fac){
            $vault = yield Loader::getPrivateVaultDB()->loadVault($fac->getId(), 1);
            $vault->getMenu()->send($sender);
        });
	}
}