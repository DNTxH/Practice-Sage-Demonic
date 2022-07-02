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

class CategorySpecial extends Task
{
    public static array $item = [
        "Chest" => [
            "id" => ItemIds::CHEST,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Trap Chest" => [
            "id" => ItemIds::TRAPPED_CHEST,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Enchanting Table" => [
            "id" => ItemIds::ENCHANTING_TABLE,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Anvil" => [
            "id" => ItemIds::ANVIL,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Ender Chest" => [
            "id" => ItemIds::ENDER_CHEST,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Beacon" => [
            "id" => ItemIds::BEACON,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Flint and Steel" => [
            "id" => ItemIds::FLINT_AND_STEEL,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Daylight Sensor" => [
            "id" => ItemIds::DAYLIGHT_DETECTOR,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Tripwire Hook" => [
            "id" => ItemIds::TRIPWIRE_HOOK,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Hopper" => [
            "id" => ItemIds::HOPPER,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Sticky Piston" => [
            "id" => ItemIds::STICKY_PISTON,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Piston" => [
            "id" => ItemIds::PISTON,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Redstone Lamp" => [
            "id" => ItemIds::REDSTONE_LAMP,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Cobweb" => [
            "id" => ItemIds::COBWEB,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Dispenser" => [
            "id" => ItemIds::DISPENSER,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Ice" => [
            "id" => ItemIds::ICE,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Packed Ice" => [
            "id" => ItemIds::PACKED_ICE,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Snow" => [
            "id" => ItemIds::SNOW_BLOCK,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Sponge" => [
            "id" => ItemIds::SPONGE,
            "meta" => 0,
            "price" => 100,
            "sellable" => true,
        ],
        "Nether Brick Furnace" => [
            "id" => ItemIds::FURNACE,
            "meta" => 1,
            "price" => 100,
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
        $menu->setName("§l§aSpeciality");
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