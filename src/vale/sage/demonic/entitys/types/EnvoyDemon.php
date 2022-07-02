<?php

declare(strict_types = 1);


namespace vale\sage\demonic\entitys\types;

use pocketmine\block\Flowable;
use pocketmine\block\Liquid;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\entity\animation\ArmSwingAnimation;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ContainerIds;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\enchants\EnchantManager;
use vale\sage\demonic\entitys\EntityBase;

class EnvoyDemon extends EntityBase
{

	protected $stepHeight = 0.5;

	const FIND_DISTANCE = 15;
	const LOSE_DISTANCE = 15;


	public $target = "";
	public $findNewTargetTicks = 0;
	public $randomPosition = null;
	public $scale = 0.70;
	public $findNewPositionTicks = 200;
	public $jumpTicks = 20;
	public $attackWait = 20;
	public $canWhistle = true;
	public $attackDamage = 1;
	public $speed = 0.80;
	public $startingHealth = 100;
	public $assisting = [];
	public static $despawnTime = 60 * 5 * 20;

	public function __construct(Location $level, CompoundTag $nbt = null)
	{
		parent::__construct($level, $nbt);
		$this->generateRandomPosition();
		$this->sendAttributes();

	}


	public function entityBaseTick(int $tickDiff = 1): bool
	{
		$item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE);
		EnchantManager::glint($item);
		$packet = MobEquipmentPacket::create($this->getId(), ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($item)), 0, ContainerIds::INVENTORY, WindowTypes::INVENTORY);
		foreach ($this->getViewers() as $viewer) {
			$viewer->getNetworkSession()->sendDataPacket($packet);
		}
		if (!$this->isFlaggedForDespawn() && self::$despawnTime > 0) {
			self::$despawnTime--;
		}
		if (self::$despawnTime <= 0) {
			$this->flagForDespawn();
		}
		$hasUpdate = parent::entityBaseTick($tickDiff);
		if (!$this->isAlive()) {
			if (!$this->closed) $this->flagForDespawn();
			return false;
		}
		if ($this->hasTarget()) {
			$this->lookAt($this->getTarget()->getLocation()->asVector3());
			return $this->attackTarget();
		}
		if ($this->findNewTargetTicks > 0) {
			$this->findNewTargetTicks--;
		}
		if (!$this->hasTarget() && $this->findNewTargetTicks === 0) {
			$this->findNewTarget();
		}
		if ($this->jumpTicks > 0) {
			$this->jumpTicks--;
		}
		if ($this->findNewPositionTicks > 0) {
			$this->findNewPositionTicks--;
		}
		if (!$this->isOnGround()) {
			if ($this->motion->y > -$this->gravity * 4) {
				$this->motion->y = -$this->gravity * 4;
			} else {
				$this->motion->y += $this->isUnderwater() ? $this->gravity : -$this->gravity;
			}
		} else {
			$this->motion->y -= $this->gravity;
		}
		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
		if ($this->shouldJump()) {

			$this->jump();
		}
		if ($this->atRandomPosition() || $this->findNewPositionTicks === 0) {
			$this->generateRandomPosition();
			$this->findNewPositionTicks = 200;
			return true;
		}
		$position = $this->getRandomPosition();
		$x = $position->x - $this->getLocation()->getX();
		$y = $position->y - $this->getLocation()->getY();
		$z = $position->z - $this->getLocation()->getZ();
		if ($x * $x + $z * $z < 4 + $this->getScale()) {
			$this->motion->x = 0;
			$this->motion->z = 0;
		} else {
			$this->motion->x = $this->getSpeed() * 0.15 * ($x / (abs($x) + abs($z)));
			$this->motion->z = $this->getSpeed() * 0.17 * ($z / (abs($x) + abs($z)));
		}
		$this->yaw = rad2deg(atan2(-$x, $z));
		$this->pitch = 0;
		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
		if ($this->shouldJump()) {
			$this->jump();
		}
		$this->updateMovement();
		return $this->isAlive();
	}

	public function attackTarget()
	{
		$target = $this->getTarget();
		if ($target == null || $target->getLocation()->distance($this->location) >= self::LOSE_DISTANCE) {
			$this->target = null;
			return true;
		}
		if ($this->jumpTicks > 0) {
			$this->jumpTicks--;
		}
		if (!$this->isOnGround()) {
			if ($this->motion->y > -$this->gravity * 4) {
				$this->motion->y = -$this->gravity * 4;
			} else {
				$this->motion->y += $this->isUnderwater() ? $this->gravity : -$this->gravity;
			}
		} else {
			$this->motion->y -= $this->gravity;
		}
		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
		if ($this->shouldJump()) {
			$this->jump();
		}
		$x = $target->location->x - $this->location->x;
		$y = $target->location->y - $this->location->y;
		$z = $target->location->z - $this->location->z;
		if ($x * $x + $z * $z < 1.2) {
			$this->motion->x = 0;
			$this->motion->z = 0;
		} else {
			$this->motion->x = $this->getSpeed() * 0.15 * ($x / (abs($x) + abs($z)));
			$this->motion->z = $this->getSpeed() * 0.15 * ($z / (abs($x) + abs($z)));
		}
		$this->yaw = rad2deg(atan2(-$x, $z));
		$this->pitch = rad2deg(-atan2($y, sqrt($x * $x + $z * $z)));
		$this->move($this->motion->x, $this->motion->y, $this->motion->z);
		if ($this->shouldJump()) {
			$this->jump();
		}
		if ($this->getLocation()->distance($target->location) <= $this->getScale() + 0.3 && $this->attackWait <= 0) {
			$this->lookAt($target->getLocation()->asVector3());
			$event = new EntityDamageByEntityEvent($this, $target, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $this->getBaseAttackDamage() + mt_rand(1,4));
			$this->broadcastAnimation(new ArmSwingAnimation($this));
			$target->attack($event);
			$this->attackWait = 6;
		}
		$this->updateMovement();
		$this->attackWait--;
		return $this->isAlive();
	}

	public function attack(EntityDamageEvent $source): void
	{
		if ($source instanceof EntityDamageByEntityEvent) {
			$killer = $source->getDamager();
			if ($killer instanceof Player) {
				if ($killer->isSpectator()) {
					$source->cancel();
					return;
				}
				if ($this->target != $killer->getName() && mt_rand(1, 5) == 1 || $this->target == "") {
					$this->target = $killer->getName();
				}
				if (!isset($this->assisting[$killer->getName()])) {
					$this->assisting[$killer->getName()] = true;
				}
				if ($this->getHealth() <= $this->getMaxHealth() / 2 && mt_rand(0, 2) == 1 && $this->canWhistle) {
					$this->whistle();
					$this->canWhistle = false;
				}
			}
		}
		parent::attack($source);
	}

	public function knockBack(float $x, float $z, float $force = 0.4, ?float $verticalLimit = 0.4): void
	{
      parent::knockBack($x,$z,$force,$verticalLimit);
	}

	public function whistle()
	{
		foreach ($this->getWorld()->getNearbyEntities($this->getBoundingBox()->expandedCopy(15, 15, 15)) as $entity) {
			if ($entity instanceof $this && !$entity->hasTarget() && $entity->canWhistle) {
				$entity->target = $this->target;
				$entity->canWhistle = false;
			}
		}
	}

	public function kill(): void
	{
		parent::kill();
	}

	//Targetting//
	public function findNewTarget()
	{
		$distance = self::FIND_DISTANCE;
		$target = null;
		foreach ($this->getWorld()->getPlayers() as $player) {
			if ($player->location->distance($this->location) <= $distance && !$player->isSpectator()) {
				$distance = $player->location->distance($this->location);
				$target = $player;
			}
		}
		$this->findNewTargetTicks = 60;
		$this->target = ($target != null ? $target->getName() : "");
	}

	public function hasTarget()
	{
		$target = $this->getTarget();
		if ($target == null) return false;
		$player = $this->getTarget();
		return !$player->isSpectator();
	}

	public function getTarget()
	{
		return Server::getInstance()->getPlayerExact((string)$this->target);
	}

	public function atRandomPosition()
	{
		return $this->getRandomPosition() == null || $this->getLocation()->distance($this->getRandomPosition()) <= 2;
	}

	public function getRandomPosition()
	{
		return $this->randomPosition;
	}

	public function generateRandomPosition()
	{
		$minX = $this->getLocation()->getFloorX() - 8;
		$minY = $this->getLocation()->getFloorY() - 8;
		$minZ = $this->getLocation()->getFloorZ() - 8;
		$maxX = $minX + 16;
		$maxY = $minY + 16;
		$maxZ = $minZ + 16;
		$level = $this->getWorld();
		for ($attempts = 0; $attempts < 16; ++$attempts) {
			$x = mt_rand($minX, $maxX);
			$y = mt_rand($minY, $maxY);
			$z = mt_rand($minZ, $maxZ);
			while ($y >= 0 and !$level->getBlockAt($x, $y, $z)->isSolid()) {
				$y--;
			}
			if ($y < 0) {
				continue;
			}
			$blockUp = $level->getBlockAt($x, $y + 1, $z);
			$blockUp2 = $level->getBlockAt($x, $y + 2, $z);
			if ($blockUp->isSolid() or $blockUp instanceof Liquid or $blockUp2->isSolid() or $blockUp2 instanceof Liquid) {
				continue;
			}
			break;
		}
		$this->randomPosition = new Vector3($x, $y + 1, $z);
	}

	public function getSpeed()
	{
		return ($this->isUnderwater() ? $this->speed / 2 : $this->speed);
	}

	public function getBaseAttackDamage()
	{
		return $this->attackDamage;
	}

	public function getAssisting()
	{
		$assisting = [];
		foreach ($this->assisting as $name => $bool) {
			$player = Server::getInstance()->getPlayerExact($name);
			if ($player instanceof Player) $assisting[] = $player;
		}
		return $assisting;
	}

	public function getFrontBlock($y = 0)
	{
		$dv = $this->getDirectionVector();
		$pos = $this->location->asVector3()->add($dv->x * $this->getScale(), $y + 1, $dv->z * $this->getScale())->round();
		return $this->getWorld()->getBlock($pos);
	}

	public function shouldJump()
	{
		if ($this->jumpTicks > 0) return false;
		return $this->isCollidedHorizontally ||
			($this->getFrontBlock()->getId() != 0 || $this->getFrontBlock(-1) instanceof Stair) ||
			($this->getWorld()->getBlock($this->getLocation()->asVector3()->add(0, -0, 5)) instanceof Slab &&
				(!$this->getFrontBlock(-0.5) instanceof Slab && $this->getFrontBlock(-0.5)->getId() != 0)) &&
			$this->getFrontBlock(1)->getId() == 0 &&
			$this->getFrontBlock(2)->getId() == 0 &&
			!$this->getFrontBlock() instanceof Flowable &&
			$this->jumpTicks == 0;
	}

	public function getJumpMultiplier()
	{
		return 16;
		if (
			$this->getFrontBlock() instanceof Slab ||
			$this->getFrontBlock() instanceof Stair ||
			$this->getLevel()->getBlock($this->asVector3()->subtract(0, 0.5)->round()) instanceof Slab &&
			$this->getFrontBlock()->getId() != 0
		) {
			$fb = $this->getFrontBlock();
			if ($fb instanceof Slab && $fb->getDamage() & 0x08 > 0) return 8;
			if ($fb instanceof Stair && $fb->getDamage() & 0x04 > 0) return 8;
			return 4;
		}
		return 8;
	}

	public function jump(): void
	{
		$this->motion->y = $this->gravity * $this->getJumpMultiplier();
		$this->move($this->motion->x * 1.25, $this->motion->y, $this->motion->z * 1.25);
		$this->jumpTicks = 5;
		//	$this->jump();
		//($this->getJumpMultiplier() == 4 ? 2 : 5);
	}

	protected function sendAttributes(): void{
		$this->setNameTag("§l§bEnvoy §dDemon");
		$item = ItemFactory::getInstance()->get(ItemIds::DIAMOND_AXE);
		EnchantManager::glint($item);
		$packet = MobEquipmentPacket::create($this->getId(), ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet($item)), 0, ContainerIds::INVENTORY, WindowTypes::INVENTORY);
		foreach ($this->getViewers() as $viewer) {
			$viewer->getNetworkSession()->sendDataPacket($packet);
		}
	}
}