<?php

namespace vale\sage\demonic\Shop\Category;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Shop\BillingManager\BillingManager;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class CategorySpawners extends Task
{
    private static array $item = [
        "Chicken Spawner" => [
            "item" => "chicken",
            "price" => "65000",
            "sellable" => false
        ],
        "Pig Spawner" => [
            "item" => "pig",
            "price" => "65000",
            "sellable" => false
        ],
        "Cave Spider Spawner" => [
            "item" => "cavespider",
            "price" => "90000",
            "sellable" => false
        ],
        "Spider Spawner" => [
            "item" => "spider",
            "price" => "95000",
            "sellable" => false
        ],
        "Sheep Spawner" => [
            "item" => "sheep",
            "price" => "95000",
            "sellable" => false
        ],
        "Wolf Spawner" => [
            "item" => "wolf",
            "price" => "95000",
            "sellable" => false
        ],
        "Zombie Spawner" => [
            "item" => "zombie",
            "price" => "115000",
            "sellable" => false
        ],
        "Skeleton Spawner" => [
            "item" => "skeleton",
            "price" => "115000",
            "sellable" => false
        ],
        "Cow Spawner" => [
            "item" => "cow",
            "price" => "162000",
            "sellable" => false
        ],
        "Creeper Spawner" => [
            "item" => "creeper",
            "price" => "162000",
            "sellable" => false
        ],
        "Enderman Spawner" => [
            "item" => "enderman",
            "price" => "390000",
            "sellable" => false
        ],
        "Zombie Pigman Spawner" => [
            "item" => "zombiePigman",
            "price" => "390000",
            "sellable" => false
        ],
        "Blaze Spawner" => [
            "item" => "blaze",
            "price" => "390000",
            "sellable" => false
        ],
        "Slime Spawner" => [
            "item" => "slime",
            "price" => "1300000",
            "sellable" => false
        ],
        "Iron Golem Spawner" => [
            "item" => "irongolem",
            "price" => "2000000",
            "sellable" => false
        ],

    ];

    private Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }


    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $menu->setName("§l§aSpawner");
        $inv = $menu->getInventory();
        foreach (self::$item as $name => $item_data){
            $item = Loader::getInstance()->getSpawnerManager()->getSpawner($item_data["item"]);
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
                    $item = Loader::getInstance()->getSpawnerManager()->getSpawner($data["item"]);
                    $player->removeCurrentWindow();
                    $billing = new BillingManager($player, 0, 0, $price, $data["sellable"], true, $item);
                    $billing->run();
                }
            }
            return $transaction->discard();
        });
    }

}