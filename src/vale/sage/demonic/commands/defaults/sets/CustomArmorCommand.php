<?php
    
namespace vale\sage\demonic\commands\defaults\sets;

use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\sets\manager\ArmorManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;


class CustomArmorCommand extends Command{

    public function __construct(){
        parent::__construct("armor", "Give a player custom armor pieces", "/armor <armor> [player]", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if (empty($args[1]) && !$sender instanceof Player) {
            $sender->sendMessage("§cUse /armor <armor> <player> or use the command ingame!");
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
        $armor = explode(":", $args[0]);
        if (strtolower($armor[1] ?? "") === "full") {
            if (!ArmorManager::getInstance()->getArmor([$armor[0], "helmet"]) instanceof BaseArmorItem) {
                $sender->sendMessage("§cThat armor doesn't exist!");
                return;
            }

            foreach (ArmorManager::getInstance()->getArmors() as $type => $name) {
                foreach ($name as $idk => $item) {
                    if ($type === $armor[0]) {
                        $sender->getInventory()->addItem($item->asItem());
                    }
                }
            }
            $sender->sendMessage("§aYou got a full {$armor[0]}§a set!");
            return;
        }

        $armor = ArmorManager::getInstance()->getArmor($armor);
        if (!$armor instanceof BaseArmorItem) {
            $sender->sendMessage("§cThat armor doesn't exist!");
            return;
        }
        $player = $sender;
        if (!empty($args[1])) {
            if (!($player = Server::getInstance()->getPlayerByPrefix($args[1]))) {
                $sender->sendMessage("§cThis player is not online!");
                return;
            }
        }

        if ($player->getInventory()->canAddItem($armor->asItem())) {
            $player->getInventory()->addItem($armor->asItem());
        } else {
            $player->getWorld()->dropItem($player->getLocation(), $armor->asItem());
        }
        $sender->sendMessage("§aYou gave§b {$player->getDisplayName()}§a the§5 " . ucwords($armor->getArmorName()) . " {$armor->getArmorType()}§a!");
    }
}