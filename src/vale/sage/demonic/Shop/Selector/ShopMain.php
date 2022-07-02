<?php

namespace vale\sage\demonic\Shop\Selector;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class ShopMain extends Task
{

    private Player $player;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function onRun(): void{
        $player = $this->player;
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("Shop Categories");

        $potion = ItemFactory::getInstance()->get(373,0,1);
        $potion->setCustomName("§r§l§ePotion");
        $potion->setLore(["§7Click to view this category."]);

        $raid = ItemFactory::getInstance()->get(331,0,1);
        $raid->setCustomName("§r§l§eRaid Shop");
        $raid->setLore(["§7Click to view this category."]);

        $spawners = ItemFactory::getInstance()->get(52,0,1);
        $spawners->setCustomName("§r§l§eSpawners");
        $spawners->setLore(["§7Click to view this category."]);

        $buildings = ItemFactory::getInstance()->get(98,0,1);
        $buildings->setCustomName("§r§l§eBuilding Blocks");
        $buildings->setLore(["§7Click to view this category."]);

        $ores = ItemFactory::getInstance()->get(388,0,1);
        $ores->setCustomName("§r§l§eOres and Gems");
        $ores->setLore(["§7Click to view this category."]);

        $food = ItemFactory::getInstance()->get(363,0,1);
        $food->setCustomName("§r§l§eFood and Farming");
        $food->setLore(["§7Click to view this category."]);

        $mob_drops = ItemFactory::getInstance()->get(369,0,1);
        $mob_drops->setCustomName("§r§l§eMob Drops");
        $mob_drops->setLore(["§7Click to view this category."]);

        $speciality = ItemFactory::getInstance()->get(381,0,1);
        $speciality->setCustomName("§r§l§eSpeciality");
        $speciality->setLore(["§7Click to view this category."]);

        $brewing = ItemFactory::getInstance()->get(379,0,1);
        $brewing->setCustomName("§r§l§eBrewing");
        $brewing->setLore(["§7Click to view this category."]);

        $wool = ItemFactory::getInstance()->get(35,0,1);
        $wool->setCustomName("§r§l§eWool");
        $wool->setLore(["§7Click to view this category."]);

        $glass = ItemFactory::getInstance()->get(20,1,1);
        $glass->setCustomName("§r§l§eGlass");
        $glass->setLore(["§7Click to view this category."]);

        $base = ItemFactory::getInstance()->get(49,0,1);
        $base->setCustomName("§r§l§eBase Grind");
        $base->setLore(["§7Click to view this category."]);

        $clay = ItemFactory::getInstance()->get(159,0,1);
        $clay->setCustomName("§r§l§eClay / Terracotta");
        $clay->setLore(["§7Click to view this category."]);

        $concrete = ItemFactory::getInstance()->get(236,0,1);
        $concrete->setCustomName("§r§l§eConcrete");
        $concrete->setLore(["§7Click to view this category."]);

        $flower = ItemFactory::getInstance()->get(38,0,1);
        $flower->setCustomName("§r§l§eFlowers");
        $flower->setLore(["§7Click to view this category."]);

        $inv = $menu->getInventory();
        $inv->setContents([
            0 => $potion,
            1 => $raid,
            2 => $spawners,
            3 => $buildings,
            4 => $ores,
            5 => $food,
            6 => $mob_drops,
            7 => $speciality,
            8 => $brewing,
            11 => $wool,
            12 => $glass,
            13 => $base,
            14 => $clay,
            15 => $concrete,
            22 => $flower
        ]);
        $menu->send($player);

        $menu->setListener(function(InvMenuTransaction $transaction) use ($player,$menu){
            $clicked = $transaction->getItemClicked();
            $itemName = $clicked->getCustomName();
            $itemName = str_replace("§r§l§e","",$itemName);
            switch($itemName){
                case "Potion":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"potion");
                    break;
                case "Raid Shop":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"raid");
                    break;
                case "Spawners":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"spawners");
                    break;
                case "Building Blocks":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"buildings");
                    break;
                case "Ores and Gems":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"ores");
                    break;
                case "Food and Farming":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"food");
                    break;
                case "Mob Drops":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"mob_drops");
                    break;
                case "Speciality":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"speciality");
                    break;
                case "Brewing":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"brewing");
                    break;
                case "Wool":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"wool");
                    break;
                case "Glass":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"glass");
                    break;
                case "Base Grind":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"base");
                    break;
                case "Clay / Terracotta":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"clay");
                    break;
                case "Concrete":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"concrete");
                    break;
                case "Flowers":
                    $player->removeCurrentWindow();
                    GuiManager::OpenGui($player,"flower");
                    break;
                default:
                    break;
            }
            return $transaction->discard();
        });

    }
}