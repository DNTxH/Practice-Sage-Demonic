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

class CategoryGlass extends Task
{
    public static array $item = [
        "Glass1" => [
            "id" => ItemIds::GLASS,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass2" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass3" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass4" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass5" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass6" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass7" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 6,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass8" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 7,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass9" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 8,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass10" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 9,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass11" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 10,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass12" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 11,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass13" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 12,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass14" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 13,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass15" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 14,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass16" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 15,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass17" => [
            "id" => ItemIds::STAINED_GLASS,
            "meta" => 16,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes1" => [
            "id" => ItemIds::GLASS_PANE,
            "meta" => 0,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes2" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 1,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes3" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 2,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes4" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 3,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes5" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 4,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes6" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 5,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes7" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 6,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes8" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 7,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes9" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 8,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes10" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 9,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes11" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 10,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes12" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 11,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes13" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 12,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes14" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 13,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes15" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 14,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes16" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 15,
            "price" => 1,
            "sellable" => true,
        ],
        "Glass Panes17" => [
            "id" => ItemIds::STAINED_GLASS_PANE,
            "meta" => 16,
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
        $menu->setName("§l§aGlass");
        $inv = $menu->getInventory();
        foreach (self::$item as $name => $item_data){
            $sell_price = round(($item_data["price"] / 2),2);
            $sellable = $item_data["sellable"] ? "\n§4Sell price\n$$sell_price" : "";
            $item = ItemFactory::getInstance()->get($item_data["id"], $item_data["meta"]);
            $name = $item->getName();
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