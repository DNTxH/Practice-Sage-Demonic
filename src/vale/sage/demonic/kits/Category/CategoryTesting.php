<?php

namespace vale\sage\demonic\kits\Category;

use FormAPI\SimpleForm;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use vale\sage\demonic\kits\GuiManager;
use vale\sage\demonic\Loader;

class CategoryTesting extends Task
{
    private Player $player;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function onRun(): void
    {
        $wooden = ItemFactory::getInstance()->get(ItemIds::WOODEN_SWORD);
        $stone = ItemFactory::getInstance()->get(ItemIds::STONE_SWORD);
        $iron = ItemFactory::getInstance()->get(ItemIds::IRON_SWORD);
        $gold = ItemFactory::getInstance()->get(ItemIds::GOLD_SWORD);
        $diamond = ItemFactory::getInstance()->get(ItemIds::DIAMOND_SWORD);
        $book = ItemFactory::getInstance()->get(ItemIds::BOOK);
        $nether_star = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR);

        $menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
        $glass = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS,1,1);
        for($i = 0; $i < 9; $i++){
            $menu->getInventory()->setItem($i,$glass);
        }
        for($i = 18; $i < 27; $i++){
            $menu->getInventory()->setItem($i,$glass);
        }
        $menu->getInventory()->setItem(9,$glass);
        $menu->getInventory()->setItem(17,$glass);
        $menu->getInventory()->setItem(10,$wooden);
        $menu->getInventory()->setItem(11,$stone);
        $menu->getInventory()->setItem(12,$iron);
        $menu->getInventory()->setItem(13,$gold);
        $menu->getInventory()->setItem(14,$diamond);
        $menu->getInventory()->setItem(15,$book);
        $menu->getInventory()->setItem(16,$nether_star);
        $menu->send($this->player);
        $menu->setListener(function(InvMenuTransaction $transaction) use ($wooden,$stone,$iron,$gold,$diamond,$book,$nether_star){
            $item_choosen = $transaction->getItemClicked();
            switch($item_choosen){
                case $wooden:
                case $stone:
                case $iron:
                case $gold:
                case $diamond:
                case $book:
                case $nether_star:
                    $transaction->getPlayer()->removeCurrentWindow();
                    $this->showTestingInformation($transaction->getPlayer());
                    break;
            }
            return $transaction->discard();
        });
    }

    public function showTestingInformation(Player $player){
        $form = new SimpleForm(function(Player $player,$data){
            if($data === null){
                return true;
            }
            switch ($data){
                case 0:
                    //confirm
                    $this->giveTestingKit($player);
                    break;
                case 1:
                    $diamond = ItemFactory::getInstance()->get(ItemIds::DIAMOND);
                    $spawner = Loader::getInstance()->getSpawnerManager()->getSpawner("creeper");
                    GuiManager::openPreview($player,"Preview Testing Kit",array($diamond,$spawner));
                    break;
            }
            return true;
        });
        $form->setTitle("Testing kit Information");
        $form->setContent("§e§lSage HCP\n§r§7Select an option.§f\n-Confirm\n-Preview");
        $form->addButton("§f§lConfirm\n§rClick to claim the testing kit");
        $form->addButton("§f§lPreview\n§rClick to preview its contains");
        $player->sendForm($form);
    }

    public function giveTestingKit(Player $player){
       $diamond = ItemFactory::getInstance()->get(ItemIds::DIAMOND);
       $spawner = Loader::getInstance()->getSpawnerManager()->getSpawner("creeper");
       if($player->getInventory()->canAddItem($diamond) && $player->getInventory()->canAddItem($spawner)) {
           $player->getInventory()->addItem($diamond);
           $player->getInventory()->addItem($spawner);
           $player->sendMessage("§a§lTesting kit has been given to you.");
       } else {
           $player->sendMessage("§c§lYou don't have enough space in your inventory.");
       }
    }


}