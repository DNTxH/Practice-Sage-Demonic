<?php

namespace vale\sage\demonic\cchest;

use vale\sage\demonic\Loader;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as C;

class CollectionListener implements Listener
{
    /**
     * @param Loader $loader
     */
	public function __construct(private Loader $loader){}
	
	public function onPlace(BlockPlaceEvent $event): void{
		$item = $event->getItem();
		if($event->isCancelled()) return;
		if(!$item->equals($this->loader->getCollectionManager()->getItem())) return;
		$this->loader->getCollectionManager()->addChest($event->getBlock()->getPosition());
	}
	
	public function onBreak(BlockBreakEvent $event): void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		if($event->isCancelled()) return;
		if(($chest = $this->loader->getCollectionManager()->getChest($block->getPosition())) === null) return;
		if(!$chest->isEmpty()){
			$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This collection chest is not empty, you can't break it!");
			$event->cancel();
			return;
		}
		$event->setDrops([$this->loader->getCollectionManager()->getItem()]);
		$this->loader->getCollectionManager()->removeChest($block->getPosition());
	}
	
	public function onInteract(PlayerInteractEvent $event): void{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
		if(($chest = $this->loader->getCollectionManager()->getChest($event->getBlock()->getPosition())) === null) return;
		$chest->sendMenu($player);
		$event->cancel();
	}
}