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

class CategoryFlower extends Task
{
    public static array $item = [
        "Dandelion" => [
            "id" => ItemIds::DANDELION,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Poppy" => [
            "id" => ItemIds::POPPY,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Blue Orchid" => [
            "id" => 38,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Allium" => [
            "id" => 38,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Azure Bluet" => [
            "id" => 38,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Red Tulip" => [
            "id" => 38,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Orange Tulip" => [
            "id" => 38,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "White Tulip" => [
            "id" => 38,
            "meta" => 6,
            "price" => 1,
            "sellable" => true,
        ],
        "Pink Tulip" => [
            "id" => 38,
            "meta" => 7,
            "price" => 1,
            "sellable" => true,
        ],
        "Oxeye Daisy" => [
            "id" => 38,
            "meta" => 8,
            "price" => 1,
            "sellable" => true,
        ],
        "Corn Flower" => [
            "id" => 38,
            "meta" => 9,
            "price" => 1,
            "sellable" => true,
        ],
        "Lily of the Valley" => [
            "id" => 38,
            "meta" => 10,
            "price" => 1,
            "sellable" => true,
        ],
        "Sunflower" => [
            "id" => 175,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Lilac" => [
            "id" => 175,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Rose Bush" => [
            "id" => 175,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Peony" => [
            "id" => 175,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "Wither Rose" => [
            "id" => 471,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Lily Pad" => [
            "id" => ItemIds::LILY_PAD,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Vines" => [
            "id" => ItemIds::VINE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Red Mushroom" => [
            "id" => ItemIds::RED_MUSHROOM,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Flower Pot" => [
            "id" => ItemIds::FLOWER_POT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Oak Leaves" => [
            "id" => ItemIds::LEAVES,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Spruce Leaves" => [
            "id" => ItemIds::LEAVES,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Birch Leaves" => [
            "id" => ItemIds::LEAVES,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Jungle Leaves" => [
            "id" => ItemIds::LEAVES,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Acacia Leaves" => [
            "id" => ItemIds::LEAVES2,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Dark Oak Leaves" => [
            "id" => ItemIds::LEAVES2,
            "meta" => 1,
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
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName("§l§aFlowers");
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
        $inv->setItem(53, $back);
        $menu->send($this->player);
        $menu->setListener(function(InvMenuTransaction $transaction){
            $item_Selected = $transaction->getItemClicked();
            $name = str_replace("§r§l§e", "", $item_Selected->getCustomName());
            if($item_Selected->getNamedTag()->getTag("back")){
                $transaction->getPlayer()->removeCurrentWindow();
                GuiManager::OpenGui($transaction->getPlayer(),"Main");
            } else {
                if(in_array($name, array_keys(self::$item))) {
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