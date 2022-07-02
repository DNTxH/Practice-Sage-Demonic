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

class CategoryWool extends Task
{
    public static array $item = [
        "White Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 0,
            "price" => 1,
            "sellable" => true
        ],
        "Light Gray Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 1,
            "price" => 1,
            "sellable" => true
        ],
        "Gray Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 2,
            "price" => 1,
            "sellable" => true
        ],
        "Black Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 3,
            "price" => 1,
            "sellable" => true
        ],
        "Brown Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 4,
            "price" => 1,
            "sellable" => true
        ],
        "Red Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 5,
            "price" => 1,
            "sellable" => true
        ],
        "Orange Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 6,
            "price" => 1,
            "sellable" => true
        ],
        "Yellow Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 7,
            "price" => 1,
            "sellable" => true
        ],
        "Lime Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 8,
            "price" => 1,
            "sellable" => true
        ],
        "Green Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 9,
            "price" => 1,
            "sellable" => true
        ],
        "Cyan Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 10,
            "price" => 1,
            "sellable" => true
        ],
        "Light Blue Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 11,
            "price" => 1,
            "sellable" => true
        ],
        "Blue Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 12,
            "price" => 1,
            "sellable" => true
        ],
        "Purple Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 13,
            "price" => 1,
            "sellable" => true
        ],
        "Magenta Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 14,
            "price" => 1,
            "sellable" => true
        ],
        "Pink Wool" => [
            "id" => ItemIds::WOOL,
            "meta" => 15,
            "price" => 1,
            "sellable" => true
        ],
        //repeat 16 times
        "White Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 0,
            "price" => 1,
            "sellable" => true
        ],
        "Light Gray Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 1,
            "price" => 1,
            "sellable" => true
        ],
        "Gray Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 2,
            "price" => 1,
            "sellable" => true
        ],
        "Black Carpet" =>[
            "id" => ItemIds::CARPET,
            "meta" => 3,
            "price" => 1,
            "sellable" => true
        ],
        "Brown Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 4,
            "price" => 1,
            "sellable" => true
        ],
        "Red Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 5,
            "price" => 1,
            "sellable" => true
        ],
        "Orange Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 6,
            "price" => 1,
            "sellable" => true
        ],
        "Yellow Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 7,
            "price" => 1,
            "sellable" => true
        ],
        "Lime Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 8,
            "price" => 1,
            "sellable" => true
        ],
        "Green Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 9,
            "price" => 1,
            "sellable" => true
        ],
        "Cyan Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 10,
            "price" => 1,
            "sellable" => true
        ],
        "Light Blue Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 11,
            "price" => 1,
            "sellable" => true
        ],
        "Blue Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 12,
            "price" => 1,
            "sellable" => true
        ],
        "Purple Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 13,
            "price" => 1,
            "sellable" => true
        ],
        "Magenta Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 14,
            "price" => 1,
            "sellable" => true
        ],
        "Pink Carpet" => [
            "id" => ItemIds::CARPET,
            "meta" => 15,
            "price" => 1,
            "sellable" => true
        ],
    ];

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName("§l§aWool");
        $inv = $menu->getInventory();
        foreach (self::$item as $name => $item_data){
            $sell_price = round(($item_data["price"] / 2),2);
            $sellable = $item_data["sellable"] ? "\n§4Sell price\n$$sell_price" : "";
            $item = ItemFactory::getInstance()->get($item_data["id"], $item_data["meta"]);
            $name = $item->getVanillaName();
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