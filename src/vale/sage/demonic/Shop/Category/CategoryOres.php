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

class CategoryOres extends Task
{
    public static $item = [
        "Iron Ingot" => [
            "id" => ItemIds::IRON_INGOT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Iron Block" => [
            "id" => ItemIds::IRON_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Gold Ingot" => [
            "id" => ItemIds::GOLD_INGOT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Gold Block" => [
            "id" => ItemIds::GOLD_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Coal" => [
            "id" => ItemIds::COAL,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Coal Block" => [
            "id" => ItemIds::COAL_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Lapis Lazuli" => [
            "id" => 351,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Lapis Block" => [
            "id" => ItemIds::LAPIS_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Redstone Dust" => [
            "id" => 331,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Redstone Block" => [
            "id" => ItemIds::REDSTONE_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Diamond" => [
            "id" => ItemIds::DIAMOND,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Diamond Block" => [
            "id" => ItemIds::DIAMOND_BLOCK,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Emerald" => [
            "id" => ItemIds::EMERALD,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Emerald Block" => [
            "id" => ItemIds::EMERALD_BLOCK,
            "meta" => 0,
            "price" => 5,
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
        $menu->setName("§l§aOres and Gems");
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