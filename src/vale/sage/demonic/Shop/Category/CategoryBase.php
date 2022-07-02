<?php

namespace vale\sage\demonic\Shop\Category;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Shop\BillingManager\BillingManager;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class CategoryBase extends Task
{
    public static array $item = [
        "Obsidian" => [
            "id" => ItemIds::OBSIDIAN,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Stone" => [
            "id" => ItemIds::STONE,
            "meta" => 0,
            "price" => 50,
            "sellable" => true,
        ],
        "Cobblestone" => [
            "id" => ItemIds::COBBLESTONE,
            "meta" => 0,
            "price" => 25,
            "sellable" => true,
        ],
        "TNT" => [
            "id" => ItemIds::TNT,
            "meta" => 0,
            "price" => 500,
            "sellable" => true,
        ],
        "Lava Bucket" => [
            "id" => ItemIds::BUCKET,
            "meta" => 10,
            "price" => 1000,
            "sellable" => true,
        ],
        "Water Bucket" => [
            "id" => ItemIds::BUCKET,
            "meta" => 8,
            "price" => 1000,
            "sellable" => true,
        ],
        "Bucket" => [
            "id" => ItemIds::BUCKET,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Ice" => [
            "id" => ItemIds::ICE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Packed Ice" => [
            "id" => ItemIds::PACKED_ICE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Sandstone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Sand" => [
            "id" => ItemIds::SAND,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Red Sand" => [
            "id" => ItemIds::RED_SANDSTONE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Gravel" => [
            "id" => ItemIds::GRAVEL,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Dirt" => [
            "id" => ItemIds::DIRT,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Netherrack" => [
            "id" => ItemIds::NETHERRACK,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "End Stone" => [
            "id" => ItemIds::END_STONE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Smooth Stone Slab" => [
            "id" => ItemIds::STONE_SLAB,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Stone Pressure Plate" => [
            "id" => ItemIds::STONE_PRESSURE_PLATE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Ender Chest" => [
            "id" => ItemIds::ENDER_CHEST,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Diamond Pickaxe" => [
            "id" => ItemIds::DIAMOND_PICKAXE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Diamond Shovel" => [
            "id" => ItemIds::DIAMOND_SHOVEL,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Diamond Axe" => [
            "id" => ItemIds::DIAMOND_AXE,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
        "Scaffolding" => [
            "id" => ItemIds::SCAFFOLDING,
            "meta" => 0,
            "price" => 1000,
            "sellable" => true,
        ],
    ];

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§l§aBase Grind");
        $inv = $menu->getInventory();
        foreach (self::$item as $name => $item_data){
            $sell_price = round(($item_data["price"] / 2),2);
            $sellable = $item_data["sellable"] ? "\n§4Sell price\n$$sell_price" : "";
            $item = ItemFactory::getInstance()->get($item_data["id"], $item_data["meta"]);
            $price = $item_data["price"];
            $item->setCustomName("§r§l§e$name");
            $item->setLore(["\n§aBuy Price\n$$price" . "$sellable"]);
            $inv->addItem($item);
        }
        $back = ItemFactory::getInstance()->get(ItemIds::ARROW, 0,1);
        $back->setCustomName("§r§l§eBack");
        $back->getNamedTag()->setString("back", "true");
        $inv->setItem(26, $back);
        $menu->send($this->player);
        $menu->setListener(function(InvMenuTransaction $transaction){
            $item_Selected = $transaction->getItemClicked();
            $name = str_replace("§r§l§e", "", $item_Selected->getCustomName());
            if($item_Selected->getNamedTag()->getTag("back")){
                $transaction->getPlayer()->removeCurrentWindow();
                GuiManager::OpenGui($transaction->getPlayer(),"Main");
            } else {
                if (in_array($name, array_keys(self::$item))) {
                    $player = $transaction->getPlayer();
                    $item = $transaction->getItemClicked();
                    $name = str_replace("§r§l§e", "", $item->getCustomName());
                    $data = self::$item[$name];
                    $price = $data["price"];
                    $id = $item->getId();
                    $meta = $item->getMeta();
                    $player->removeCurrentWindow();
                    $billing = new BillingManager($player, $id, $meta, $price, true);
                    $billing->run();
                }
            }
            return $transaction->discard();
        });
    }
}