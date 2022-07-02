<?php

namespace vale\sage\demonic\enchants\utils;

use vale\sage\demonic\Loader;
use Ramsey\Uuid\UuidInterface;
use ReflectionClass;
use pocketmine\event\Listener;
use pocketmine\entity\Entity;
use pocketmine\player\Player;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\world\Position;
use pocketmine\item\{
    ItemFactory,
    ItemIds
};
use pocketmine\network\mcpe\protocol\{
	AddPlayerPacket,
	RemoveActorPacket,
	AdventureSettingsPacket 
};
use pocketmine\network\mcpe\protocol\types\entity\{
    EntityMetadataFlags,
    EntityMetadataProperties,
    FloatMetadataProperty,
    LongMetadataProperty
};
use pocketmine\event\entity\
{
	EntityDamageEvent,
	EntityDamageByEntityEvent
};

use pocketmine\utils\TextFormat as C;

class IndicatorManager
{
	public static $eid;
	
	public static function addTag(Player $player, Player $entity, string $message, int $seconds, string $color)
	{
		if (!$entity->isOnline()) return;
		if (!$entity->isAlive()) return;
		$packet = new AddPlayerPacket();
		$id = Entity::nextRuntimeId();
		$packet->actorRuntimeId = $id;
		$packet->actorUniqueId = $id;
		self::$eid[$id] = true;
		$packet->position = $entity->getPosition()->add(0, (rand(5, 15) / 10) ,0);
		$uuid = \Ramsey\Uuid\Uuid::uuid4();
		$packet->uuid = $uuid;
		$packet->item = ItemStackWrapper::legacy(TypeConverter::getInstance()->coreItemStackToNet(ItemFactory::air()));
		
		$packet->username = $color."* ".$message.C::GRAY." [".$seconds."]".$color." *";
		
		$flags = (1 << EntityMetadataFlags::IMMOBILE);
	
		$packet->metadata = [
			EntityMetadataProperties::FLAGS => new LongMetadataProperty($flags),
			EntityMetadataProperties::SCALE => new FloatMetadataProperty(0.01)
		];
		
		$packet->adventureSettingsPacket = AdventureSettingsPacket::create(0, 0, 0, 0, 0, $id);
		
		$player->getNetworkSession()->sendDataPacket($packet);
		
		// i dont care. fuck this shit. i don't care. don't fucking say sh9it. if i see you on my discord wit this topic, i'm actually fucking done bro
		// ez oop :clown:
		// ill prob fix this later. i can't think of a workaround at the moment. 
		Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new IndicatorRemoveTask(Loader::getInstance(), $id, $seconds, $message, $color, $entity, $player), 20, 20);
	}
	
	public static function removeTag($eid)
	{
		if (isset(self::$eid[$eid])) {
			$packet = new RemoveActorPacket();
			$packet->actorUniqueId = $eid;
			foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $players){
				$players->getNetworkSession()->sendDataPacket($packet);
			}
			unset(self::$eid[$eid]);
		}
	}
}