<?php

namespace vale\sage\demonic\Shop\Category;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\Glass;
use pocketmine\block\GlazedTerracotta;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Shop\BillingManager\BillingManager;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class CategoryClay extends Task
{
    public static array $item = [
        "Clay" => [
            "id" => ItemIds::CLAY,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Clay Block" => [
            "id" => ItemIds::CLAY_BLOCK,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Hardened Clay" => [
            "id" => ItemIds::HARDENED_CLAY,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "White Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Magenta Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Light Blue Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Yellow Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Lime Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "Red Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 6,
            "price" => 1,
            "sellable" => true,
        ],
        "Gray Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 7,
            "price" => 1,
            "sellable" => true,
        ],
        "Light Gray Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 8,
            "price" => 1,
            "sellable" => true,
        ],
        "Cyan Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 9,
            "price" => 1,
            "sellable" => true,
        ],
        "Purple Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 10,
            "price" => 1,
            "sellable" => true,
        ],
        "Blue Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 11,
            "price" => 1,
            "sellable" => true,
        ],
        "Brown Blue Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 12,
            "price" => 1,
            "sellable" => true,
        ],
        "Green Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 13,
            "price" => 1,
            "sellable" => true,
        ],
        "Pink Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 14,
            "price" => 1,
            "sellable" => true,
        ],
        "Black Terracotta" => [
            "id" => ItemIds::TERRACOTTA,
            "meta" => 15,
            "price" => 1,
            "sellable" => true,
        ]
    ];

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§l§aClay / Terracotta");
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