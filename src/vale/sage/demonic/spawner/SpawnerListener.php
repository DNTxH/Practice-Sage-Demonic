<?php

namespace vale\sage\demonic\spawner;

use vale\sage\demonic\Loader;
use vale\sage\demonic\spawner\Mobstacker;
use vale\sage\demonic\spawner\SpawnerUtils;
use vale\sage\demonic\spawner\tile\MobSpawner;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\ItemBlock;
use pocketmine\item\Pickaxe;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat as C;
class SpawnerListener implements Listener
{
    public function __construct(private Loader $plugin){}

    public function onDamage(EntityDamageEvent $event): void{
        $entity = $event->getEntity();
        if(!$entity instanceof Living || $entity instanceof Human){
            return;
        }
        $mobStacker = new Mobstacker($entity);
        if($entity->getHealth() - $event->getFinalDamage() <= 0){
            $cause = null;
            if($event instanceof EntityDamageByEntityEvent){
                $player = $event->getDamager();
                if($player instanceof Player) {
                    $cause = $player;
                }
            }

            if($mobStacker->removeStack($cause)){
                $entity->setHealth($entity->getMaxHealth());
                $event->cancel();
            }
        }
    }

    public function onSpawn(EntitySpawnEvent $event): void{
        $entity = $event->getEntity();
        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function() use ($entity): void {
			if(!$entity instanceof Living) return;
            if(!in_array(str_replace(" ", "", strtolower($entity->getName())), SpawnerUtils::getEntityArrayList())) return;
            if($entity->getWorld() === null) return;
            if(!$entity->getWorld()->isLoaded()) return;
			if($entity instanceof Human or !$entity instanceof Living) return;
			$mobStacker = new Mobstacker($entity);
			$mobStacker->stack();
        }), 1);
    }
	
	public function onBreak(BlockBreakEvent $event): void{
		$player = $event->getPlayer();
		$item = $event->getItem();
		$block = $event->getBlock();
		$tile = $player->getWorld()->getTile($block->getPosition());
		if($tile instanceof MobSpawner){
			if(!$item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())){
				return;
			}
			$nbt = CompoundTag::create()->setString(MobSpawner::ENTITY, $tile->getEntity());
			$spawner = ItemFactory::getInstance()->get(ItemIds::MOB_SPAWNER, 0, 1, $nbt);
			$spawner->setCustomName(C::RESET . C::GREEN . C::BOLD . SpawnerUtils::getEntityName($tile->getEntity()) . C::RESET . C::WHITE . " Spawner");
			$spawner->setLore([
				C::RESET . " ",
				C::RESET . C::YELLOW . C::BOLD . "INFO",
				C::RESET . C::YELLOW . "  * " . C::WHITE . "Type: " . C::YELLOW . SpawnerUtils::getEntityName($tile->getEntity()),
				C::RESET . C::YELLOW . "  * " . C::WHITE . "Drops: " . C::YELLOW . SpawnerUtils::getEntityDrop($tile->getEntity()),
				// C::RESET . " ",
				// C::RESET . C::GRAY . C::BOLD . "Right-Click" . C::RESET . " on a block to place this spawner down",
				
			]);
			$block->getPosition()->getWorld()->dropItem($block->getPosition()->add(0.5, 0.5, 0.5), $spawner);
			if($tile->getCount() - 1 >= 1){
				$tile->setCount($tile->getCount() - 1);
				$event->cancel();
			}
		}
	}
	
    public function onInteractSpawner(PlayerInteractEvent $event): void{
        $item = $event->getItem();
        if($item instanceof Pickaxe){
            return;
        }
        if($item instanceof ItemBlock && $item->getNamedTag()->getTag(MobSpawner::ENTITY) === false){
            return;
        }
        $player = $event->getPlayer();
        $vec3 = $event->getBlock()->getPosition()->asVector3();
        $world = $player->getWorld();
        $tile = $world->getTile($vec3);
		if($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
		if(!$tile instanceof MobSpawner) return;
		if($item->getNamedTag()->getTag(MobSpawner::ENTITY) !== null && $item->getNamedTag()->getTag("Entity")->getValue() === $tile->getEntity()){
			if($player->isSneaking()){
				if($tile->getCount() < 20){
					$tile->setCount($tile->getCount() + 1);
					$player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
					$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've added 1+ stack in " . $tile->getName() . " Spawner");
				}else{
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This spawner reached the max stacking amount (20)");
				}
			}else{
				$text = C::RESET . C::BOLD . C::YELLOW . "Spawner Information";
				$text .= C::RESET . C::EOL . C::YELLOW . "  * " . C::WHITE . "Type: " . C::YELLOW . $tile->getName();
				$text .= C::RESET . C::EOL . C::YELLOW . "  * " . C::WHITE . "Amount: " . C::YELLOW . $tile->getCount() . "x";
				$player->sendMessage($text);
			}
			$event->cancel();
		}else{
			if($tile instanceof MobSpawner){
				$text = C::RESET . C::BOLD . C::YELLOW . "Spawner Information";
				$text .= C::RESET . C::EOL . C::YELLOW . "  * " . C::WHITE . "Type: " . C::YELLOW . $tile->getName();
				$text .= C::RESET . C::EOL . C::YELLOW . "  * " . C::WHITE . "Amount: " . C::YELLOW . $tile->getCount() . "x";
				$player->sendMessage($text);
				$event->cancel();
			}
		}
    }
}