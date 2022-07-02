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

class CategoryRaid extends Task
{
    public static array $item = [
        "RedStone" => [
            "id" => ItemIds::REDSTONE,
            "meta" => 0,
            "price" => 16.25,
            "sellable" => true,
        ],
        "RedStone Block" => [
            "id" => ItemIds::REDSTONE_BLOCK,
            "meta" => 0,
            "price" => 195,
            "sellable" => true,
        ],
        "RedStone Torch" => [
            "id" => ItemIds::REDSTONE_TORCH,
            "meta" => 0,
            "price" => 25,
            "sellable" => false,
        ],
        "RedStone Comparator" => [
            "id" => 404,
            "meta" => 0,
            "price" => 100,
            "sellable" => false,
        ],
        "RedStone Repeater" => [
            "id" => 356,
            "meta" => 0,
            "price" => 85,
            "sellable" => false,
        ],
        "Oak Button" => [
            "id" => ItemIds::WOODEN_BUTTON,
            "meta" => 0,
            "price" => 5.5,
            "sellable" => false,
        ],
        "Stone Button" => [
            "id" => ItemIds::STONE_BUTTON,
            "meta" => 0,
            "price" => 5.5,
            "sellable" => false,
        ],

        'TNT' => [
            'id' => ItemIds::TNT,
            'meta' => 0,
            'price' => 81.25,
            'sellable' => true,
        ],
        "Dispenser" => [
            "id" => ItemIds::DISPENSER,
            "meta" => 0,
            "price" => 819,
            "sellable" => false,
        ],
        "Sticky Piston" => [
            "id" => ItemIds::PISTON,
            "meta" => 1,
            "price" => 325,
            "sellable" => false,
        ],
        "Piston" => [
            "id" => ItemIds::PISTON,
            "meta" => 0,
            "price" => 195,
            "sellable" => false,
        ],
        "Glowstone" => [
            "id" => ItemIds::GLOWSTONE,
            "meta" => 0,
            "price" => 20,
            "sellable" => true,
        ],
        "Sea Lantern" => [
            "id" => ItemIds::SEA_LANTERN,
            "meta" => 0,
            "price" => 20,
            "sellable" => false,
        ],
        "Glass" => [
            "id" => ItemIds::GLASS,
            "meta" => 0,
            "price" => 4,
            "sellable" => true,
        ],
        "Ladder" => [
            "id" => ItemIds::LADDER,
            "meta" => 0,
            "price" => 10,
            "sellable" => false,
        ],
        "Cobweb" => [
            "id" => ItemIds::COBWEB,
            "meta" => 0,
            "price" => 40,
            "sellable" => false,
        ],
        "Sponge" => [
            "id" => ItemIds::SPONGE,
            "meta" => 0,
            "price" => 2000,
            "sellable" => false,
        ],
        "Level" => [
            "id" => ItemIds::LEVER,
            "meta" => 0,
            "price" => 20,
            "sellable" => false,
        ],
        "Oak Trapdoor" => [
            "id" => ItemIds::TRAPDOOR,
            "meta" => 0,
            "price" => 50,
            "sellable" => false,
        ],
        "Stone" => [
            "id" => ItemIds::STONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Oak Planks" => [
            "id" => 5,
            "meta" => 0,
            "price" => 5,
            "sellable" => true,
        ],
        "Sand" => [
            "id" => ItemIds::SAND,
            "meta" => 0,
            "price" => 4.06,
            "sellable" => true,
        ],
        "Red Sand" => [
            "id" => ItemIds::SAND,
            "meta" => 1,
            "price" => 4.06,
            "sellable" => true,
        ],
        "Gravel" => [
            "id" => ItemIds::GRAVEL,
            "meta" => 0,
            "price" => 3.52,
            "sellable" => true,
        ],
        "Ice" => [
            "id" => ItemIds::ICE,
            "meta" => 0,
            "price" => 50,
            "sellable" => false,
        ],
        "Water Bucket" => [
            "id" => ItemIds::BUCKET,
            "meta" => 8,
            "price" => 500,
            "sellable" => false,
        ],
        "Lava Bucket" => [
            "id" => ItemIds::BUCKET,
            "meta" => 10,
            "price" => 500,
            "sellable" => false,
        ],
        "Smooth Stone Slab" => [
            "id" => ItemIds::STONE_SLAB,
            "meta" => 0,
            "price" => 50,
            "sellable" => false,
        ],
        "Flint and Steel" => [
            "id" => ItemIds::FLINT_AND_STEEL,
            "meta" => 0,
            "price" => 145,
            "sellable" => false,
        ],
        "Fishing Rod" => [
            "id" => ItemIds::FISHING_ROD,
            "meta" => 0,
            "price" => 500,
            "sellable" => false,
        ],

    ];
    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        $menu->setName("§l§aRaid Shop");
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