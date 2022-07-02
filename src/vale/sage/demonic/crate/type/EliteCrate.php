<?php

namespace vale\sage\demonic\crate\type;

use vale\sage\demonic\enchants\factory\EnchantFactory;
use vale\sage\demonic\Loader;
use vale\sage\demonic\crate\Crate;
use vale\sage\demonic\crate\Reward;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\Position;
use pocketmine\world\particle\BlockBreakParticle;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;
use vale\sage\demonic\rewards\Rewards;

class EliteCrate extends Crate
{
    /**
     * @param Position $position
     * @param Item $key
     */
	public function __construct(private Position $position, private Item $key){
		$factory = ItemFactory::getInstance();
		parent::__construct(self::ELITE, $position, $key, [
			// new Reward(Item, Callable, Chance),
			new Reward(($item = $factory->get(ItemIds::GOLD_BLOCK, 0, 32)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::getEnchantDust("elite",true,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::getEnchantDust("elite",false,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::getEnchantRandomizer("elite")), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::giveEnchantUtilities("blackscroll")), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::giveEnchantUtilities("extractor")), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::giveEnchantUtilities("whitescroll",rand(1,2))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::giveEnchantUtilities("weaponorb",rand(1,2))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = EnchantFactory::createSoulGem(null,129999)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),


			new Reward(($item = EnchantFactory::giveEnchantUtilities("armourorb",rand(1,2))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, rand(1,100)),

			new Reward(($item = $factory->get(ItemIds::ENCHANTED_GOLDEN_APPLE, 0, 32)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 1),

			new Reward(($item = Rewards::createXPBottle(null,rand(1,10000))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 12),

			new Reward(($item = Rewards::createMoneyNote(null,rand(1,19992))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 34),
			new Reward(($item = Rewards::get(Rewards::ELITE_SPACE_CHEST,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 11),
			new Reward(($item = Rewards::get(Rewards::STARTER_BUNDLE,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 1),

			new Reward(($item = Rewards::get(Rewards::SPECIAL_EQUIPMET_BOX,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 56),

			new Reward(($item = Rewards::get(Rewards::EMP,rand(1,2))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 80),

			new Reward(($item = Rewards::get(Rewards::MYSTERY_STASH_BOX,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 17),

			new Reward(($item = RedeemableAPI::getRandomStashBox()), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 23),
			new Reward(($item = RedeemableAPI::getRandomStashBox()), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 23),

			new Reward(($item = RedeemableAPI::getRandomStashBox()), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 23),

			new Reward(($item = Rewards::get(Rewards::LIFE_STEAL_MASK,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 10),

			new Reward(($item = Rewards::get(Rewards::STARTER_BUNDLE,1)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 1),
			new Reward(($item = Rewards::get(Rewards::ELITE_BOOK,rand(1,2))), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 56),
			new Reward(($item = $factory->get(ItemIds::DIAMOND_BLOCK, 0, 64)), function(Player $player)use($item): void{
				$drops = $player->getInventory()->addItem($item);
				foreach($drops as $drop) $player->getWorld()->dropItem($player->getPosition(), $drop);
			}, 50),
		]);
	}

    /**
     * @param Player $player
     * @return void
     */
	public function spawnTo(Player $player):void{
        $particle = Loader::getInstance()->getCrateManager()->getFloatingText($player, $this->getName());
        if($particle !== null) return;
		$description = " " . C::EOL . C::GRAY . "Left-Click to preview" . C::EOL . C::GRAY . "Right-Click to open" . C::EOL . C::GRAY . "SHIFT Click to open Stack";
        Loader::getInstance()->getCrateManager()->addFloatingText($player, Position::fromObject($this->getPosition()->add(0.5, 1.25, 0.5), $this->getPosition()->getWorld()), $this->getName(), $this->getName() . C::EOL . $description);
    }

    /**
     * @param Player $player
     * @return void
     */
	public function despawnTo(Player $player):void{
        $particle = Loader::getInstance()->getCrateManager()->getFloatingText($player, $this->getName());
        if($particle !== null) Loader::getInstance()->getCrateManager()->removeFloatingText($player, $this->getName());
    }

    /**
     * @param Player $player
     * @return void
     */
	public function broadcastParticle(Player $player): void{
		$particle = new BlockBreakParticle(VanillaBlocks::DIAMOND());
		$this->getPosition()->getWorld()->addParticle($this->getPosition()->add(0.5, 0.5, 0.5), $particle, [$player]);
	}
}