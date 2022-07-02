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

class CategoryConcrete extends Task
{
    public static array $item = [
        "White Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Orange Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Magenta Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Light Blue Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Yellow Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Lime Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "Pink Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 6,
            "price" => 1,
            "sellable" => true,
        ],
        "Gray Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 7,
            "price" => 1,
            "sellable" => true,
        ],
        "Light Gray Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 8,
            "price" => 1,
            "sellable" => true,
        ],
        "Cyan Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 9,
            "price" => 1,
            "sellable" => true,
        ],
        "Purple Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 10,
            "price" => 1,
            "sellable" => true,
        ],
        "Blue Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 11,
            "price" => 1,
            "sellable" => true,
        ],
        "Brown Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 12,
            "price" => 1,
            "sellable" => true,
        ],
        "Green Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 13,
            "price" => 1,
            "sellable" => true,
        ],
        "Red Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 14,
            "price" => 1,
            "sellable" => true,
        ],
        "Black Concrete" => [
            "id" => ItemIds::CONCRETE,
            "meta" => 15,
            "price" => 1,
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
        $menu->setName("§l§aConcrete");
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