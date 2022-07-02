<?php

declare(strict_types=1);

namespace vale\sage\demonic\listeners\types;

use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\particle\AngryVillagerParticle;
use pocketmine\world\particle\BlockBreakParticle;
use pocketmine\world\particle\CriticalParticle;
use pocketmine\world\particle\DustParticle;
use pocketmine\world\particle\EnchantmentTableParticle;
use pocketmine\world\particle\EntityFlameParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\Position;
use pocketmine\world\sound\GhastSound;
use vale\sage\demonic\floatingtext\PopupTextEntity;
use vale\sage\demonic\Loader;
use vale\sage\demonic\tasks\types\BlockReplaceTask;
use vale\sage\demonic\Utils;

class SetsBonusesListener implements Listener
{
	private Loader $plugin;

	public function __construct(Loader $plugin)
	{
		$this->plugin = $plugin;
	}

	/**
	 * @param EntityDamageByEntityEvent $event
	 */
	public function onEntityDamageByEntityEvent(EntityDamageByEntityEvent $event)
	{
		$entity = $event->getEntity();
		$damager = $event->getDamager();
		$proc3 = rand(0, 1000);
		$proc2 = rand(0, 267);
		$chance = mt_rand(0, 570);
		if ($event->isCancelled()) {
			return;
		}
		if ($damager instanceof Player) {
			$hand = $damager->getInventory()->getItemInHand();
			if ($hand->getNamedTag()->getString("crystaled", "") === "") {
				return;
			}
			$entitySession = Loader::getInstance()->getSessionManager()->getSession($entity);
			$damagerSession = Loader::getInstance()->getSessionManager()->getSession($damager);
			$tag = $hand->getNamedTag()->getInt("crystaltier");
			if ($tag === 0) {
				if ($chance === 36 || $chance === 111 || $chance === 45) {
					$damager->sendMessage("§r§6§l *** Weapon Crystal (§r§4§lDEMONIC§r§6§l) *** §r§7[Flame Ability]");
					$center = new Vector3($entity->getLocation()->getX(), $entity->getLocation()->getY(), $entity->getLocation()->getZ());
					for ($yaw = 0; $yaw <= 15; $yaw += (M_PI * 2) / 10) {
						$x = -sin($yaw) + $center->x;
						$z = cos($yaw) + $center->z;
						$y = $center->y;
						$entity->getWorld()->addParticle(new Vector3($x, $y + 1, $z), new EntityFlameParticle());
						$entity->getWorld()->addParticle(new Vector3($x, $y + 1, $z), new EntityFlameParticle());
					}
					if ($entity->isInsideOfSolid()) {
						return;
					}
					$pos = $entity->getPosition();
					$block = BlockFactory::getInstance()->get(BlockLegacyIds::STILL_LAVA, 2, 1);
					$entity->getWorld()->setBlock(new Position($pos->getX(), $pos->getY() + 1.5, $pos->getZ(), $pos->getWorld()), $block);
					$event->setBaseDamage($event->getBaseDamage() + rand(1, 3));
					$entity->getWorld()->addParticle($entity->getLocation(), new BlockBreakParticle(BlockFactory::getInstance()->get(35, mt_rand(1, 14))));
					$entity->getWorld()->addParticle($entity->getLocation(), new BlockBreakParticle(BlockFactory::getInstance()->get(35, mt_rand(1, 14))));
					PopupTextEntity::spawnText(new Location($pos->x + 0.5, $pos->y + 0.5, $pos->z + 0.3, $entity->getWorld(), 0, 0), "§r§6§l *** Weapon Crystal (§r§4§7{$entity->getName()}§r§6§l) ***");
					Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new BlockReplaceTask(new Position($pos->getX(), $pos->getY(), $pos->getZ(), $damager->getWorld()), $block->getId(), $damager, $entity), 20);
				}
				if ($proc2 === 12 || $proc2 === 98 || $proc2 === 113) {
					$pos = $entity->getPosition();
					PopupTextEntity::spawnText(new Location($pos->x + 0.5, $pos->y + 0.5, $pos->z + 0.3, $entity->getWorld(), 0, 0), "§r§6§l *** Weapon Crystal §r§4§lDEMONIC §r§6§l(§r§4§7{$entity->getName()}§r§6§l) §r§c§l+ 15% DMG ***");
					$event->setBaseDamage($event->getBaseDamage() + rand(1, 4));
				}
				if ($proc3 === 114 || $proc3 === 567 || $proc3 === 987 || $proc3 === 412) {
					foreach ($damager->getWorld()->getNearbyEntities($damager->getBoundingBox()->expandedCopy(10, 10, 10)) as $p) {
						if ($p instanceof Player) {
							if ($p === $damager) {
								return;
							}
							$names[] = $p->getName();
							$imploded = implode(",", $names);
							$damager->sendMessage("§r§6§l *** Weapon Crystal §r§4§lDEMONIC §r§6§l(§r§7{$imploded}§r§6§l) *** ");
							$damager->sendMessage("§r§7Everybody in a 10 block radius, has received negative effects.");
							$naesua = new EffectInstance(VanillaEffects::NAUSEA(), 20 * rand(9, 15) + 2, 40);
							$slowneess = new EffectInstance(VanillaEffects::BLINDNESS(), 20 * rand(9, 15) + 2, 40);
							$p->getEffects()->add($naesua);
							$p->getEffects()->add($slowneess);
							Loader::playSound($p, "mob.enderdragon.growl", 2);
						}
					}
				}
			}

			if ($tag === 1) {
				if($chance === 78 || $chance === 112 || $chance === 242){
					$souls = rand(1,56);
					if($entitySession->getSouls() > $souls){
						$entitySession->setSouls($entitySession->getSouls() - $souls);
						$damager->sendMessage("§r§d§l** BLOOD SUCK (§r§7{$entitySession->getPlayer()->getName()}, - {$souls}§r§d§l) ***");
						$damagerSession->addSouls($souls);
					}elseif ($entitySession->getSouls() < $souls){
						$damager->sendMessage("§r§d§l** BLOOD SUCK (§r§7{$entitySession->getPlayer()->getName()}, - {$souls}§r§d§l) ***");
						$damagerSession->addSouls($souls);
					}
					$entity->setHealth($entity->getHealth() - rand(1,2));
					$entity->sendTitle("§r§d§lBLOODSUCKED \n §r§7you are being infected.");
					$entity->getWorld()->addSound($entity->getLocation(),new GhastSound());
					Utils::Lightning($entity->getLocation());
					PopupTextEntity::spawnText(new Location($pos->x + 0.5, $pos->y + 0.5, $pos->z + 0.3, $entity->getWorld(), 0, 0), "§r§6§l *** Weapon Crystal §r§d§lBLOODSUCK §r§6§l(§r§4§7{$entity->getName()}§r§6§l) ***");
					$entity->getWorld()->addParticle($entity->getLocation(), new BlockBreakParticle(BlockFactory::getInstance()->get(BlockLegacyIds::RED_NETHER_BRICK,0,1)));
				}
			}
		}
	}
}