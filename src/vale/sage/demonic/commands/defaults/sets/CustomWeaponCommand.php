<?php
    
namespace vale\sage\demonic\commands\defaults\sets;

use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\sets\manager\WeaponManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class CustomWeaponCommand extends Command{

    public function __construct(){
        parent::__construct("weapon", "Give a player custom weapons", "/weapon [player]", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if (empty($args[0]) && !$sender instanceof Player) {
            $sender->sendMessage("§cUse /weapon <weapon> <player> or use the command ingame!");
            return;
        }
        if (!Server::getInstance()->isOp($sender->getName())) {
            $sender->sendMessage("§cYou don't have the permission to use this command!");
            return;
        }
        if (empty($args[0])) {
            $sender->sendMessage($this->getUsage());
            return;
        }
        $weapon = WeaponManager::getInstance()->getWeapon($args[0]);
        if (!$weapon instanceof BaseWeaponItem) {
            $sender->sendMessage("§cThat weapon doesn't exist!");
            return;
        }
        $player = $sender;
        if (!empty($args[1])) {
            if (!($player = Server::getInstance()->getPlayerByPrefix($args[1]))) {
                $sender->sendMessage("§cThis player is not online!");
                return;
            }
        }

        if ($player->getInventory()->canAddItem($weapon->asItem())) {
            $player->getInventory()->addItem($weapon->asItem());
        } else {
            $player->getWorld()->dropItem($player->getLocation(), $weapon->asItem());
        }
        $sender->sendMessage("§aYou gave§b {$player->getDisplayName()}§a the§5 {$weapon->getWeaponType()}§a weapon!");
    }
}