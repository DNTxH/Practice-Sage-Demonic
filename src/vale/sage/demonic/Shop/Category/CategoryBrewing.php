<?php

namespace vale\sage\demonic\Shop\Category;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Shop\BillingManager\BillingManager;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class CategoryBrewing extends Task
{
    public static array $item = [
        "Spider Eye" => [
            "id" => ItemIds::SPIDER_EYE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Ghast Tear" => [
            "id" => ItemIds::GHAST_TEAR,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Brewing Stand" => [
            "id" => ItemIds::BREWING_STAND,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Water Bottle" => [
            "id" => 373,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Golden Carrot" => [
            "id" => ItemIds::GOLDEN_CARROT,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Glowstone Dust" => [
            "id" => ItemIds::GLOWSTONE_DUST,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Sugar" => [
            "id" => ItemIds::SUGAR,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Slime Ball" => [
            "id" => ItemIds::SLIME_BALL,
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
        "Golden Melon" => [
            "id" => ItemIds::GOLDEN_APPLE,
            "meta" => 1,
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
        $menu->setName("§l§aBrewing");
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