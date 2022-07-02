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

class CategoryFood extends Task
{
    public static array $item = [
        "Raw Beef" => [
            "id" => ItemIds::RAW_BEEF,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cooked Beef" => [
            "id" => ItemIds::COOKED_BEEF,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Raw Pork Chop" => [
            "id" => ItemIds::RAW_PORKCHOP,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cooked Pork Chop" => [
            "id" => ItemIds::COOKED_PORKCHOP,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Raw Mutton" => [
            "id" => ItemIds::RAW_MUTTON,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cooked Mutton" => [
            "id" => ItemIds::COOKED_MUTTON,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Raw Chicken" => [
            "id" => ItemIds::RAW_CHICKEN,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cooked Chicken" => [
            "id" => ItemIds::COOKED_CHICKEN,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Raw Rabbit" => [
            "id" => ItemIds::RAW_RABBIT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cooked Rabbit" => [
            "id" => ItemIds::COOKED_RABBIT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Mushroom Stew" => [
            "id" => ItemIds::MUSHROOM_STEW,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Beetroot Soup" => [
            "id" => ItemIds::BEETROOT_SOUP,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Rabbit Stew" => [
            "id" => ItemIds::RABBIT_STEW,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Golden Apple" => [
            "id" => ItemIds::GOLDEN_APPLE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Baked Potato" => [
            "id" => ItemIds::BAKED_POTATO,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Pumpkin Pie" => [
            "id" => ItemIds::PUMPKIN_PIE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cake" => [
            "id" => ItemIds::CAKE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Bread" => [
            "id" => ItemIds::BREAD,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cookie" => [
            "id" => ItemIds::COOKIE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Seeds" => [
            "id" => ItemIds::SEEDS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Pumpkin Seeds" => [
            "id" => ItemIds::PUMPKIN_SEEDS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Melon Seeds" => [
            "id" => ItemIds::MELON_SEEDS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Beetroot Seeds" => [
            "id" => ItemIds::BEETROOT_SEEDS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Wheat" => [
            "id" => ItemIds::WHEAT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Beetroot" => [
            "id" => ItemIds::BEETROOT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Carrot" => [
            "id" => ItemIds::CARROT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Apple" => [
            "id" => ItemIds::APPLE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Melon" => [
            "id" => ItemIds::MELON,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Pumpkin" => [
            "id" => ItemIds::PUMPKIN,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Cactus" => [
            "id" => ItemIds::CACTUS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Sugar Canes" => [
            "id" => ItemIds::SUGARCANE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Nether Wart" => [
            "id" => ItemIds::NETHER_WART,
            "meta" => 0,
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
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName("§l§aFood and Farming");
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