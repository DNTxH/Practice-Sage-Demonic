<?php

namespace vale\sage\demonic\kits;

use Closure;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class previewGui extends Task
{
    private Player $player;
    private array $item;
    private string $title;

    public function __construct(Player $player, array $item, $title){
        $this->player = $player;
        $this->item = $item;
        $this->title = $title;
    }

    public function onRun(): void{
        $menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
        foreach ($this->item as $item) {
            $menu->getInventory()->addItem($item);
        }
        $back = ItemFactory::getInstance()->get(ItemIds::ARROW, 0, 1);
        $back->setCustomName("§c§lBack");
        $menu->getInventory()->setItem(53, $back);
        $menu->setName($this->title);
        $menu->send($this->player);
        $menu->setListener(function(InvMenuTransaction $transaction) use ($back){
            if($back->getCustomName() === $transaction->getItemClicked()->getCustomName()){
                $transaction->getPlayer()->removeCurrentWindow();
                GuiManager::openGui($transaction->getPlayer(),"testing category");
            }
            return $transaction->discard();
        });
    }
}