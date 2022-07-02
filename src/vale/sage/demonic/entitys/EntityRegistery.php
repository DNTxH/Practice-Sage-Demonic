<?php
namespace vale\sage\demonic\entitys;

use BlockHorizons\Fireworks\entity\FireworksRocket;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\data\bedrock\EntityLegacyIds;
use pocketmine\entity\EntityDataHelper;
use vale\sage\demonic\entitys\types\CustomEnderPearlEntity;
use pocketmine\entity\EntityFactory;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\world\World;
use vale\sage\demonic\entitys\types\EnvoyDemon;
use vale\sage\demonic\entitys\types\KitMerchant;
use vale\sage\demonic\entitys\types\TextEntity;
use vale\sage\demonic\addons\types\end\entity\EndEntity;
use vale\sage\demonic\entitys\types\TinkererMerchant;
use vale\sage\demonic\entitys\utils\IEManager;
use vale\sage\demonic\floatingtext\PopupTextEntity;
use vale\sage\demonic\Loader;

class EntityRegistery
{


	public static function init()
	{
		$entityFactory = EntityFactory::getInstance();
		$entityFactory->register(KitMerchant::class, function (World $world, CompoundTag $nbt): KitMerchant {
			$manager = new IEManager(Loader::getInstance(), "merchant.png");
			$skin = $manager->skin;
			return new KitMerchant(EntityDataHelper::parseLocation($nbt, $world), $skin);
		}, ["aKit"]);
		$entityFactory->register(TinkererMerchant::class, function (World $world, CompoundTag $nbt): TinkererMerchant {
		$manager = new IEManager(Loader::getInstance(), "tinkerer.png");
		$skin = $manager->skin;
		return new TinkererMerchant(EntityDataHelper::parseLocation($nbt, $world), $skin);
	}, ["aTinkerer"]);


		$entityFactory->register(TextEntity::class, function (World $world, CompoundTag $nbt): TextEntity {
			return new TextEntity(EntityDataHelper::parseLocation($nbt, $world));
		}, ["Floating"]);

		$entityFactory->register(PopupTextEntity::class, function (World $world, CompoundTag $nbt): PopupTextEntity {
			return new PopupTextEntity(EntityDataHelper::parseLocation($nbt, $world), null);
		}, ["Floating"]);

		$entityFactory->register(EnvoyDemon::class, function (World $world, CompoundTag $nbt): EnvoyDemon {
			return new EnvoyDemon(EntityDataHelper::parseLocation($nbt, $world), null);
		}, ["an Envoy Guardian"]);

		$entityFactory->register(EndEntity::class, function (World $world, CompoundTag $nbt): EndEntity {
			return new EndEntity(EntityDataHelper::parseLocation($nbt, $world), null);
		}, ["an Envoy Guardian"]);
        
        $pearl = $entityFactory->register(CustomEnderPearlEntity::class, function(World $world, CompoundTag $nbt): CustomEnderPearlEntity
        {
            #var_dump(new EnderPearlEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt));
            return new EnderPearlEntity(EntityDataHelper::parseLocation($nbt, $world), null, $nbt);
        },
        ['ThrownEnderpearl', 'minecraft:ender_pearl'], EntityLegacyIds::ENDER_PEARL);
		var_dump($pearl);
        
		ItemFactory::getInstance()->register(new Fireworks(new ItemIdentifier(ItemIds::FIREWORKS, 0), "Fireworks"), true);
		EntityFactory::getInstance()->register(FireworksRocket::class, static function (World $world, CompoundTag $nbt): FireworksRocket {
			return new FireworksRocket(EntityDataHelper::parseLocation($nbt, $world), ItemFactory::getInstance()->get(ItemIds::FIREWORKS));
		}, ["FireworksRocket", EntityIds::FIREWORKS_ROCKET], EntityLegacyIds::FIREWORKS_ROCKET);
	}
}
