<?php

declare(strict_types = 1);

namespace vale\sage\demonic\commands\defaults\enchants;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\Loader;

class EnchantCommand extends Command implements PluginOwned {


    public function __construct() {
        parent::__construct("ec", "Enchant an item with custom enchants.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if(!$sender instanceof Player) return;

        if(!isset($args[0]) || Loader::getInstance()->getServer()->getPlayerByPrefix($args[0]) === null) {
            $sender->sendMessage(TextFormat::RED . "Invalid player!");
            return;
        }

        if(!isset($args[1]) || EnchantmentsManager::getEnchantmentByName($args[1]) === null) {
            $sender->sendMessage(TextFormat::RED . "Invalid enchantment specified!");
            return;
        }

        if(!EnchantmentsManager::check(EnchantmentsManager::getEnchantmentByName($args[1]), Loader::getInstance()->getServer()->getPlayerByPrefix($args[0])->getInventory()->getItemInHand())) {
            $sender->sendMessage(TextFormat::RED . "That enchantment is not compatible!");
            return;
        }

        if(!isset($args[2]) || !is_numeric($args[2]) || EnchantmentsManager::getEnchantmentByName($args[1])->getMaxLevel() < $args[2]) {
            $sender->sendMessage(TextFormat::RED . "Specified enchantment level exceeds the maximum level!");
            return;
        }

        Loader::getInstance()->getServer()->getPlayerByPrefix($args[0])->getInventory()->setItemInHand(EnchantmentsManager::applyEnchant(Loader::getInstance()->getServer()->getPlayerByPrefix($args[0])->getInventory()->getItemInHand(), EnchantmentsManager::getEnchantmentByName($args[1]), (int)$args[2]));
        $sender->sendMessage(TextFormat::GREEN . "Enchantment successful!");
    }

    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}