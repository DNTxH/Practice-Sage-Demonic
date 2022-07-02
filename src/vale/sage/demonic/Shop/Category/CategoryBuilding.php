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

class CategoryBuilding extends Task
{
    public static $item = [
        "Grass" => [
            "id" => ItemIds::GRASS,
            "meta" => 0,
            "price" => 3,
            "sellable" => true,
        ],
        "Dirt" => [
            "id" => ItemIds::DIRT,
            "meta" => 0,
            "price" => 3,
            "sellable" => true,
        ],
        "Dirt " => [
            "id" => ItemIds::DIRT,
            "meta" => 1,
            "price" => 3,
            "sellable" => true,
        ],
        "Grass Path" => [
            "id" => ItemIds::GRASS_PATH,
            "meta" => 0,
            "price" => 3,
            "sellable" => true,
        ],
        "Podzol" => [
            "id" => ItemIds::PODZOL,
            "meta" => 0,
            "price" => 10.94,
            "sellable" => true,
        ],
        "Mycelium" => [
            "id" => ItemIds::MYCELIUM,
            "meta" => 0,
            "price" => 10.16,
            "sellable" => true,
        ],
        "Snow Block" => [
            "id" => ItemIds::SNOW_BLOCK,
            "meta" => 0,
            "price" => 250,
            "sellable" => false,
        ],
        "Ice" => [
            "id" => ItemIds::ICE,
            "meta" => 0,
            "price" => 50,
            "sellable" => false,
        ],
        "Packed Ice" => [
            "id" => ItemIds::PACKED_ICE,
            "meta" => 0,
            "price" => 75,
            "sellable" => false,
        ],
        "Blue Ice" => [
            "id" => ItemIds::BLUE_ICE,
            "meta" => 0,
            "price" => 100,
            "sellable" => false,
        ],
        "Oak Log" => [
            "id" => ItemIds::LOG,
            "meta" => 0,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Supruce Log" => [
            "id" => ItemIds::LOG,
            "meta" => 1,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Birch Log" => [
            "id" => ItemIds::LOG,
            "meta" => 2,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Jungle Log" => [
            "id" => ItemIds::LOG,
            "meta" => 3,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Acacia Log" => [
            "id" => ItemIds::LOG2,
            "meta" => 0,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Dark Oak Log" => [
            "id" => ItemIds::LOG2,
            "meta" => 1,
            "price" => 8.12,
            "sellable" => true,
        ],
        "Cobblestone" => [
            "id" => ItemIds::COBBLESTONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Stone" => [
            "id" => ItemIds::STONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Smooth Stone" => [
            "id" => ItemIds::SMOOTH_STONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Stone Brick" => [
            "id" => ItemIds::STONE_BRICK,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Mossy Stone Brick" => [
            "id" => ItemIds::STONE_BRICK,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Cracked Stone Brick" => [
            "id" => ItemIds::STONE_BRICK,
            "meta" => 2,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Chiseled Stone Brick" => [
            "id" => ItemIds::STONE_BRICK,
            "meta" => 3,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Granite" => [
            "id" => ItemIds::STONE,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Diorite" => [
            "id" => ItemIds::STONE,
            "meta" => 2,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Andesite" => [
            "id" => ItemIds::STONE,
            "meta" => 3,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Netherrack" => [
            "id" => ItemIds::NETHERRACK,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Nether Brick Block" => [
            "id" => ItemIds::NETHER_BRICK_BLOCK,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Red Nether Brick Block" => [
            "id" => ItemIds::NETHER_BRICK_BLOCK,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Soul Sand" => [
            "id" => ItemIds::SOUL_SAND,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Glowstone" => [
            "id" => ItemIds::GLOWSTONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Sea Lantern" => [
            "id" => ItemIds::SEA_LANTERN,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Sand" => [
            "id" => ItemIds::SAND,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Red Sand" => [
            "id" => ItemIds::SAND,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Gravel" => [
            "id" => ItemIds::GRAVEL,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "SandStone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Chiseled SandStone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Chiseled Red SandStone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 2,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Cut Red SandStone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 3,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Smooth Red SandStone" => [
            "id" => ItemIds::SANDSTONE,
            "meta" => 4,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Block of Quartz" => [
            "id" => ItemIds::QUARTZ_BLOCK,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Pillar Quartz Block" => [
            "id" => ItemIds::QUARTZ_BLOCK,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Smooth Quartz Block" => [
            "id" => ItemIds::QUARTZ_BLOCK,
            "meta" => 2,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Prismarine" => [
            "id" => ItemIds::PRISMARINE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Prismarine Brick" => [
            "id" => ItemIds::PRISMARINE,
            "meta" => 1,
            "price" => 5.47,
            "sellable" => true,
        ],
        "End Stone" => [
            "id" => ItemIds::END_STONE,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "End Stone Brick" =>[
            "id" => ItemIds::END_BRICKS,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Purpur Block" => [
            "id" => ItemIds::PURPUR_BLOCK,
            "meta" => 0,
            "price" => 5.47,
            "sellable" => true,
        ],
        "Obsidian" => [
            "id" => ItemIds::OBSIDIAN,
            "meta" => 0,
            "price" => 5.47,
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
        $menu->setName("§l§aBuilding Blocks");
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
        $menu->setListener(function(InvMenuTransaction $transaction) {
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