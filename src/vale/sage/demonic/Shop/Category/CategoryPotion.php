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

class CategoryPotion extends Task
{
    private static array $item = [
        "Regents II Potion (0:22)" => [
            "id" => 373,
            "meta" => 30,
            "price" => "120",
            "sellable" => false,
        ],
        "Regents II Splash (0:16)" => [
            "id" => 438,
            "meta" => 30,
            "price" => "145"
        ],
        "Swiftness II Potion (1:30)" => [
            "id" => 373,
            "meta" => 16,
            "price" => "30",
            "sellable" => false,
        ],
        "Swiftness II Splash (1:07)" => [
            "id" => 438,
            "meta" => 16,
            "price" => "55",
            "sellable" => false,
        ],
        "Water Breathing Potion (8:00)" => [
            "id" => 373,
            "meta" => 20,
            "price" => "225",
            "sellable" => false,
        ],
        "Strength II Potion (1:30)" => [
            "id" => 373,
            "meta" => 33,
            "price" => "85",
            "sellable" => false,
        ],
        "Strength II Splash (1:07)" => [
            "id" => 438,
            "meta" => 33,
            "price" => "110",
            "sellable" => false,
        ],
        "Instant Health Potion" => [
            "id" => 373,
            "meta" => 22,
            "price" => "75",
            "sellable" => false,
        ],
        "Fire Resistance Potion (8:00)" => [
            "id" => 373,
            "meta" => 13,
            "price" => "1000",
            "sellable" => false,
        ],
        "Night Vision Potion (6:00)" => [
            "id" => 373,
            "meta" => 6,
            "price" => "750",
            "sellable" => false,
        ],
    ];
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§l§aPotion");
        $inv = $menu->getInventory();
        foreach (self::$item as $name => $item_data){
            $item = ItemFactory::getInstance()->get($item_data["id"], $item_data["meta"]);
            $price = $item_data["price"];
            $item->setCustomName("§r§l§e$name");
            $item->setLore(["\n§aBuy Price\n$$price"]);
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
                    $billing = new BillingManager($player, $id, $meta, $price, $data["sellable"]);
                    $billing->run();
                }
            }
            return $transaction->discard();
        });
    }
}