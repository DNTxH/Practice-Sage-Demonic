<?php

namespace vale\sage\demonic\kits;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class KitsMain
{
    public static function showMainGUI(Player $player){
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§l§aKits");
        $obsidion = ItemFactory::getInstance()->get(-289, 0, 1);
        $purple_glass = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS,2,1);
        $nether_star = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR);
        $black_glass = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS,15,1);
        $purple_firework_star = ItemFactory::getInstance()->get(ItemIds::FIREWORKS_CHARGE,2,1);
        $black_firework_star = ItemFactory::getInstance()->get(ItemIds::FIREWORKS_CHARGE,3,1);
        $bed = ItemFactory::getInstance()->get(ItemIds::BED,14,1);
        $enchanted_book = ItemFactory::getInstance()->get(ItemIds::ENCHANTED_BOOK);
        $blaze_powder = ItemFactory::getInstance()->get(ItemIds::BLAZE_POWDER);
        $skull = ItemFactory::getInstance()->get(ItemIds::SKULL,0,1);

        $menu->getInventory()->setItem(0,$obsidion);
        $menu->getInventory()->setItem(2,$obsidion);
        $menu->getInventory()->setItem(6,$obsidion);
        $menu->getInventory()->setItem(8,$obsidion);
        $menu->getInventory()->setItem(9,$obsidion);
        $menu->getInventory()->setItem(17,$obsidion);
        $menu->getInventory()->setItem(18,$obsidion);
        $menu->getInventory()->setItem(20,$obsidion);
        $menu->getInventory()->setItem(24,$obsidion);
        $menu->getInventory()->setItem(26,$obsidion);
        $menu->getInventory()->setItem(1,$purple_glass);
        $menu->getInventory()->setItem(3,$purple_glass);
        $menu->getInventory()->setItem(11,$purple_glass);
        $menu->getInventory()->setItem(19,$purple_glass);
        $menu->getInventory()->setItem(21,$purple_glass);
        $menu->getInventory()->setItem(10,$purple_firework_star);
        $menu->getInventory()->setItem(5,$black_glass);
        $menu->getInventory()->setItem(7,$black_glass);
        $menu->getInventory()->setItem(15,$black_glass);
        $menu->getInventory()->setItem(23,$black_glass);
        $menu->getInventory()->setItem(25,$black_glass);
        $menu->getInventory()->setItem(4,$nether_star);
        $menu->getInventory()->setItem(13,$enchanted_book);
        $menu->getInventory()->setItem(12,$bed);
        $menu->getInventory()->setItem(14,$blaze_powder);
        $menu->getInventory()->setItem(22,$skull);
        $menu->getInventory()->setItem(16,$black_firework_star);
        $menu->send($player);
        $menu->setListener(function(InvMenuTransaction $transaction) use ($bed,$blaze_powder,$enchanted_book,$skull,$nether_star){
            $item_choosen = $transaction->getItemClicked();
            switch ($item_choosen){
                case $bed:
                case $blaze_powder:
                case $enchanted_book:
                case $skull:
                case $nether_star:
                    $transaction->getPlayer()->removeCurrentWindow();
                    GuiManager::openGui($transaction->getPlayer(),"testing category");
                    break;
            }
            return $transaction->discard();
        });
    }
}