<?php

namespace vale\sage\demonic\crate;

use vale\sage\demonic\Loader;
use muqsit\invmenu\InvMenu;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;


class CrateListener implements Listener
{

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
	public function onBreak(BlockBreakEvent $event): void{
		$pos = $event->getBlock()->getPosition();
		foreach(Loader::getInstance()->getCrateManager()->getCrates() as $crate){
			if(!$crate->getPosition()->equals($pos)) continue;
			$event->cancel();
		}
	}

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
	public function onInteract(PlayerInteractEvent $event): void{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$block = $event->getBlock();
		foreach(Loader::getInstance()->getCrateManager()->getCrates() as $crate){
            if(!$crate->getPosition()->equals($block->getPosition())) continue;
			$event->cancel();
			switch($event->getAction()){
				case PlayerInteractEvent::LEFT_CLICK_BLOCK:
					$menu = InvMenu::create(InvMenu::TYPE_CHEST);
					$menu->setListener(InvMenu::readonly());
					$menu->setName($crate->getName());
					$items = [];
					foreach($crate->getRewards() as $i => $reward){
						$menu->getInventory()->setItem($i, $reward->getItem());
					}
					$menu->send($player);
				break;
				case PlayerInteractEvent::RIGHT_CLICK_BLOCK:
					if(!$item->equals($crate->getKey())){
						$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "You need a " . $crate->getKey()->getName() . C::RESET . C::RED . " to open this crate");
						return;
					}
					$loop = 1;
					if($player->isSneaking()){
						$loop = $item->getCount();
					}
					for($i = 0; $i < $loop; $i++){
						$hand = $player->getInventory()->getItemInHand();
						if(!$hand->equals($crate->getKey())) continue;
						$crate->broadcastParticle($player);
						$crate->getReward()->getCallback()($player);
						$player->getInventory()->setItemInHand($hand->setCount($hand->getCount() - 1));
					}
				break;
			}
		}
	}

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
	public function onJoin(PlayerJoinEvent $event): void{
		$player = $event->getPlayer();
        foreach(Loader::getInstance()->getCrateManager()->getCrates() as $crate){
			$crate->spawnTo($player);
		}
	}

    /**
     * @param PlayerQuitEvent $event
     * @return void
     */
	public function onQuit(PlayerQuitEvent $event): void{
		$player = $event->getPlayer();
        foreach(Loader::$instance->getCrateManager()->getCrates() as $crate){
			$crate->despawnTo($player);
		}
	}

    /**
     * @param EntityTeleportEvent $event
     * @return void
     */
	public function onTeleport(EntityTeleportEvent $event): void{
        $entity = $event->getEntity();
        if(!$entity instanceof Player) return;
        foreach(Loader::getCrateManager()->getFloatingTexts($entity) as $floatingText) {
            if ($floatingText->isInvisible() and $event->getTo()->getWorld()->getDisplayName() === $floatingText->getWorld()->getDisplayName()) {
                $floatingText->spawn($entity);
                continue;
            }
            if ((!$floatingText->isInvisible()) and $event->getTo()->getWorld()->getDisplayName() !== $floatingText->getWorld()->getDisplayName()) {
                $floatingText->despawn($entity);
                continue;
            }
        }
	}
}