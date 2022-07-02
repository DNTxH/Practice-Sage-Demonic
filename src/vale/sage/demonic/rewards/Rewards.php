<?php
namespace vale\sage\demonic\rewards;
use Exception;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use vale\sage\demonic\enchants\EnchantManager;
use vale\sage\demonic\Loader;

class Rewards{

	public const TEST = 0;

	public const STARTER_BUNDLE = 1;

	public const LOL = 2;

	public const SIMPLE_BOOK = 3;

	public const UNIQUE_BOOK = 4;

	public const ELITE_BOOK = 5;

	public const ULITMATE_BOOK = 6;

	public const LEGENDARY_BOOK = 7;

	public const GODLY_BOOK = 8;

	public const HEROIC_BOOK = 9;

	public const MASTERY_BOOK = 10;

	public const DEMONIC_BOOK = 19;

	public const LOOTBOX =  11;

	public const TEST_123 = 12;

	public const ITEM_LORE_CRYSTAL = 13;

	public const MYSTERY_STASH_BOX = 14;

	public const LIFE_STEAL_MASK = 15;

	public const ELITE_SPACE_CHEST = 16;

	public const GODLY_SPACE_CHEST = 17;

	public const MEDKIT = 18;

	public const EMP = 20;

	const SPECIAL_EQUIPMET_BOX = 21;

	const MYSTERYMOB = 22;

	const BLAZE_GRAB_BAG = 23;

	const RANDOM_MONEY_GENERATOR = 24;

	const ENCHANTRESS_BOX = 25;

	/**
	 * @param int $id
	 * @param int $amount
	 * @return Item|null
	 * @throws Exception
	 */
	public static function get(int $id, int $amount = 1): ?Item{
		$item = ItemFactory::getInstance()->get(ItemIds::AIR);
		$randomID = rand(1,785);
		switch ($id){
			case self::TEST:
				$item = ItemFactory::getInstance()->get(ItemIds::NAME_TAG,0,$amount);
				$item->setCustomName("§r§6§lItem Nametag §r§7(Right-Click) §r§f#{$randomID}");
				$item->setLore(["§r§7Rename and customize your equipment"]);
				$item->getNamedTag()->setString("rename", "true");

				break;

			case self::STARTER_BUNDLE:
				$item = ItemFactory::getInstance()->get(ItemIds::CLOCK,0,$amount);
				$item->setCustomName("§r§l§aStarter Pack §r§7(Right-Click)");
				$item->setLore([
					"§r§7Here is our token of appreciation",
					"§r§7to help you start the new season",
					"§r§7",
					"§r§7Right-Click (in your hand) to receive",
					"§r§7all these items below that is in this pack.",
					"§r§7",
					"§r§l§ax1 §r§fDiamond Set §7(Prot VI, Unb III)",
					"§r§l§ax1 §r§fDiamond Sword §7(Sharp V, Unb III)",
					"§r§l§ax1 §r§fBow §7(Power V, Unb IV)",
					"§r§l§ax32 §r§fArrows",
					"§r§l§ax10 §r§fSimple Enchantment Books",
					"§r§l§ax10 §r§fElite Enchantment Books",
					"§r§l§ax10 §r§fUnique Enchantment Books",
					"§r§l§ax10 §r§fUltimate Enchantment Books",
					"§r§l§ax10 §r§fLegendary Enchantment Books",
					"§r§l§ax2 §r§fSoul Enchantment Books",
					"§r§l§ax2 §r§fHeroic Enchantment Books",
					"§r§l§ax2 §r§fMastery Enchantment Books",
					"§r§a§l1x §r§fRandom Key Pouch",
					"§r§a§l1x §r§fSlot-Bot Ticket(s)",
					"§r§a§l1x §r§fElite Cache Equipment",
					"§r§l§ax24 §r§fGolden Apples",
					"§r§l§ax64 §r§fSteak",
					"§r§l§ax1 §r§fMystery Box",
					"§r§l§a",
					"",
					"§r§l§cNOTE: §r§7Please have enough",
					"§r§7Inventory slot(s) before opening"
				]);
				$item->getNamedTag()->setString("starterpack", "true");

				break;
			case self::LOL:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,$amount);
				$item->setCustomName("HI");
				$item->getNamedTag()->setString("test", "true");
				break;
			case self::SIMPLE_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§fSimple Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§fsimple §r§7enchantment book.',
				]);
				$item->getNamedTag()->setString("simplebook","true");
				break;
			case self::UNIQUE_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§aUnique Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§aunique §r§7enchantment book.',
				]);
				break;
			case self::ELITE_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§bElite Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§belite §r§7enchantment book.',
				]);
				$item->getNamedTag()->setString("elitebook","true");
				break;

			case self::ULITMATE_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§eUltimate Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§eultimate §r§7enchantment book.',
				]);
				$item->getNamedTag()->setString("ultimatebook","true");
				break;
			case self::LEGENDARY_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§6§lLegendary Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§6legendary §r§7enchantment book.',
				]);
				$item->getNamedTag()->setString("legendarybook","true");
				break;
			case self::GODLY_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§c§lGsOUL Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§cgodly §r§7enchantment book.',
				]);
                $item->getNamedTag()->setString("godlybook", true);
				break;

			case self::DEMONIC_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§5§lDemonic Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§5demonic §r§7enchantment book.',
				]);
				$item->getNamedTag()->setString("demonicbook","true");
				break;

			case self::HEROIC_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§d§lHeroic Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§dheroic §r§7enchantment book.',
				]);
				break;

			case self::MASTERY_BOOK:
				$item = ItemFactory::getInstance()->get(ItemIds::BOOK, 0, $amount);
				$item->setCustomName("§r§4§lMastery Enchantment Book §r§7(Right-Click)");
				$item->setLore([
					'§r§7Examine to recieve a random',
					'§r§4mastery §r§7enchantment book.',
				]);
				break;
			case self::LOOTBOX:
				$item = ItemFactory::getInstance()->get(ItemIds::BEACON, 0, $amount);
				$item->setCustomName("§r§e§lLOOTBOX '§r§6§lContraband§e' §r§f(#$randomID)");
				$item->setLore([
					'§r§7§o"This Mysterious Satchel of Equipment is said',
					'§r§7§oto hold Untold treasures for whoever can redeem it"',
					 "\n",
					 "§r§f§lRandom Loot (§r§76 Items§r§f§l)",
				]);
				$item->getNamedTag()->setString("lootbox_contraband", "true");
				break;
			case self::ITEM_LORE_CRYSTAL:
				$item = ItemFactory::getInstance()->get(ItemIds::DYE, 1, $amount);
				$item->setCustomName("§r§6§lItem Lore Crystal §r§7(Right-Click) §r§f(#$randomID)");
				$item->setLore([
					"§r§7Apply a custom line of lore",
					"§r§7to customize your equipmen",
					"\n",
					"§r§6§l* §r§7Limited to 1 custom line of lore per item.",
				]);
				$item->getNamedTag()->setString("renamecrystal", "true");
				break;
			case self::MYSTERY_STASH_BOX:
				$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST,0,$amount);
				$item->setCustomName("§r§5§lMystery Stash Box §r§7(Right-Click) §r§f(#$randomID)");
				$item->setLore([
					"§r§7A Mysterious Box that contains",
					"§r§7great rewards.",
					"§r§7This box was crafted by the",
					"§r§7Damned of this planet",
					"",
					"§r§6§lLoot Table:",
					"§r§c§lGodly",
					"§r§c§l* §r§c100k-350k Dollar(s)",
					"§r§c§l* §r§cTier I",
					"",
					"§r§e§lHoly",
					"§r§e§l* §r§e350k-540k Dollar(s)",
					"§r§e§l* §r§eTier II",
					"",
					"§r§b§lElite",
					"§r§b§l* §r§b540k- 720k Dollar(s)",
					"§r§b§l* §r§bTier: III",
					"",
					"§r§6§lLegendary",
					"§r§6§l* §r§6720k-1.1 MIL Dollar(s)",
					"§r§6§l* §r§6Tier: IV",
					"",
					"§r§d§lHeroic",
					"§r§d§l* §r§d1.1 MIL - 2.3 MIL Dollar(s)",
					"§r§d§l* §r§dTier: V",
					"",
					"§r§4§lDemonic",
					"§r§4§l* §r§42.3 MIL - 5.3 MIL Dollar(s)",
					"§r§4§l* §r§4Tier: V",
					"",
				]);
				$item->getNamedTag()->setString("mysterystash", "true");
				break;
			case self::LIFE_STEAL_MASK:
				$item = ItemFactory::getInstance()->get(ItemIds::MOB_HEAD,0,$amount);
				$item->setCustomName("§r§2§lGrinder Mask");
				$item->getNamedTag()->setString("lifesteal_mask","true");
				$item->setLore([
					'§r§cWant to feel Immunity? Equip this Mask.',
					'§r§7Upon Activation you will be,',
					'§r§7gained buffs to help grind',
					'§r§6§l* §r§6Sage Map 1',
					'',
					'§r§7§oEquip this mask to recieve',
					'§r§7§ospecial benefits & perks!',
					'',
					'§r§7To equip, wear this head as a helmet',
					'§r§7Upon removing & activation you will be notified',
				]);
				$instance = VanillaEnchantments::PROTECTION();
				$unbreaking = VanillaEnchantments::UNBREAKING();
				$item->addEnchantment(new EnchantmentInstance($instance,10));
				$item->addEnchantment(new EnchantmentInstance($unbreaking,5));
				break;
			case self::ELITE_SPACE_CHEST:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,$amount);
				$item->setCustomName("§r§b§lElite Sage Space Chest §r§7(Right-Click) §r§f(#$randomID/0)");
				$item->setLore([
					'§r§7A cahce of equipment packaged by',
					'§r§7the Intergalactic Cosmonaut Station.',
					'',
					'§r§7Contains §r§b5 Elite Rarity §r§7items...',
					'§r§b§l* §r§7Elite Equipment',
					'§r§b... and much more!'
				]);
				$item->getNamedTag()->setString("spacechest","true");
				$item->getNamedTag()->setInt("space",1);
				break;
			case self::GODLY_SPACE_CHEST:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,$amount);
				$item->setCustomName("§r§c§lGodly Sage Space Chest §r§7(Right-Click) §r§f(#$randomID/0)");
				$item->setLore([
					'§r§7A cahce of equipment packaged by',
					'§r§7the Intergalactic Cosmonaut Station.',
					'',
					'§r§7Contains §r§c5 Godly Rarity §r§7items...',
					'§r§c§l* §r§7Godly Equipment',
					'§r§c... and much more!'
				]);
				$item->getNamedTag()->setString("spacechest","true");
				$item->getNamedTag()->setInt("space",2);
				break;
			case self::MEDKIT:
				$item = ItemFactory::getInstance()->get(ItemIds::DYE, 1, $amount)->
				setCustomName("§r§c§lMedkit")->
				setLore([
					'§r§7Use this item to clutch up',
					'§r§7You will get absorption and Regen',
					'§r§eAvailable at'. Loader::BUYCRAFT
				]);
				$item->getNamedTag()->setString("medkit","true");
				break;
			case self::EMP:
				$item = ItemFactory::getInstance()->get(ItemIds::REDSTONE_TORCH,0,$amount);
				$item->setCustomName("§r§3§lEMP PULSE §r§7(Right-Click)");
				$item->setLore([
						'§r§7Emits a large Electromagnetic Pulse,',
						'§r§7combat tagging ALL players within the',
						'§r§7devices radius. Players can only be',
						'§r§7affected by 1 EMP every 3 minutes',
						'',
						'§r§3Radius: §r§b7x128x7',
						'§r§3Soul Cost: §r§b300 souls',
						'§r§3Cooldown: §r§b300s'
					]
				);
				$item->getNamedTag()->setString("emppulse","true");
				break;
			case self::SPECIAL_EQUIPMET_BOX:
				$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST,0,$amount)
					->setCustomName("§r§a§lSpecial Equipment Lootbox §r§7(Right-Click)");
				$item->getNamedTag()->setString("specialbox","true");
				$item->setLore([
					'§r§7Right-Click to recieve a random special',
					'§r§7armor set or weapon from the following:',
					'',
					'§r§a§l* §dReaper Set Peices a/or Weapon',
					'§r§a§l* §fYeti Set Peices a/or Weapon',
					'§r§a§l* §cFantasy Set Peices a/or Weapon',
					'§r§a§l* §4Cupid Set Peices a/or Weapon',
					'§r§a§l* §bTraveler Set Peices a/or Weapon',
					'§r§a§l* §2Xmas Set Peices a/or Weapon',
					'§r§a§l* §eThor Set Peices a/or Weapon',
					'§r§a§l* §6Spooky Set Peices a/or Weapon',
					'',
					'§r§c§lWARNING: §r§7Please have enough',
					'§r§7inventory slot(s) before opening.'
				]);
				break;
			case self::MYSTERYMOB:
				$item = ItemFactory::getInstance()->get(ItemIds::MONSTER_SPAWNER,0,$amount);
				$item->setCustomName("§r§6§lMystery Spawner Generator");
				$item->setLore([
					'§r§7Right-Click to recieve a random spawner!'
				]);
				$item->getNamedTag()->setString("mysterymob","true");
				break;
			case self::BLAZE_GRAB_BAG:
				$item = ItemFactory::getInstance()->get(ItemIds::DISPENSER,0,$amount);
				$item->setCustomName("§r§6§lBlaze §eGrab Bag §r§7[§aTier 3§r§7] (Right-Click) §r§f(#$randomID)");
				$item->setLore([
					'§r§7Right Click this §r§e§lGrab Bag §r§7to recieve',
					'§r§7a random §r§aTier 3 §r§7Reward!',
					'',
					'§r§f§l(§a!§f) §r§7This is not a §lCrate Key §r§7this is a §7Lootbox §r§f§l(§a!)',
					'',
					'§r§f§lRandom Loot (§r§71 item§f§l)',
					'§r§f§l5x §r§b§lElite Space Chest',
					'§r§f§l2x §r§6§lMystery Mob Generator',
					'§r§f§l16x §r§4§lDemonic Enchantment Books',
					'§r§7... and much more!'
				]);
				$item->getNamedTag()->setString("blaze_grab","true");
				break;
			case self::RANDOM_MONEY_GENERATOR:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,$amount);
				$item->setCustomName("§r§6§lRandom Money Generator");
				$item->setLore([
					'§r§7Right-Click to recieve a random amount of cash!'
				]);
				$item->getNamedTag()->setString("randommoney","true");
				break;
			case self::ENCHANTRESS_BOX:
				$item = ItemFactory::getInstance()->get(ItemIds::ENDER_CHEST,0,$amount);
				$item->setCustomName("§r§e§lEnchantress Box §r§7(Right-Click) §r§f(#$randomID)");
				$item->setLore([
					'§r§7Right-Click to recieve a bundle of',
					'§r§7useful blacksmithing items',
					'',
					'§r§c§lWARNING: §r§7Please have enough',
					'§r§7inventory slot(s) before opening.'

				]);
				$item->getNamedTag()->setString("enchantbox","true");
				break;
			default:
				throw new \Exception('The Reward does not exist for this ID');
		}
		return  $item;
	}


	public static function getArmorCrystal(int $tier, int $amount = 1): ?Item{
		$item = ItemFactory::getInstance()->get(ItemIds::AIR);
		switch ($tier){
			case 0:
				$item = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR, 0, $amount);
				$percentage = 100;
				$item->getNamedTag()->setString("armorcrystal","true");
				$item->getNamedTag()->setInt("crystaltier",$tier);
				$item->
				setCustomName("§r§6§lWeapon Set Crystal (§4§lDEMONIC§6§l)")->
				setLore([
					'§r§a'.$percentage . '% Success rate',
					'§r§7Can be equipped to any non',
					'§r§7weapon set that is not',
					'§r§7already equipped with a',
					'§r§7bonus crystal to gain',
					'§r§7a passive advantage!',
					'',
					'§r§6§lCrystal Bonus:',
					'§r§l§4DEMONIC',
					'§r§4§l* §r§435% Chance to increase DMG by 15%',
					'§r§4§l* §r§410% Chance to proc FLAME ability',
					'§r§4§l* §r§45% Chance to make enemies in radius confused.',
				]);
				$item->getNamedTag()->setInt("enchuid",mt_rand(1, 918391));
				break;
			case 1:
				$item = ItemFactory::getInstance()->get(ItemIds::NETHER_STAR, 0, $amount);
				$percentage = 100;
				$item->getNamedTag()->setString("armorcrystal","true");
				$item->getNamedTag()->setInt("crystaltier",1);
				$item->
				setCustomName("§r§6§lWeapon Set Crystal (§d§lBLOODSUCK§6§l)")->
				setLore([
					'§r§a'.$percentage . '% Success rate',
					'§r§7Can be equipped to any non',
					'§r§7weapon set that is not',
					'§r§7already equipped with a',
					'§r§7bonus crystal to gain',
					'§r§7a passive advantage!',
					'',
					'§r§6§lCrystal Bonus:',
					'§r§l§dBLOODSUCK',
					'§r§d§l* §r§d9% Chance to Spawn Enemy in Cobwebs',
					'§r§d§l* §r§d35% Chance to Steal Enemy Souls & Bloodsuck HP',
					'§r§d§l* §r§d12% Chance to Set Enemies Hunger to 0% and Strike Lightning.',
				]);
				$item->getNamedTag()->setInt("enchuid",mt_rand(1, 918391));
				break;
		}
		return $item;
	}


	public static function getLuckyBlock(int $amount, string $type = "console") : ?Item{
		$lb = ItemFactory::getInstance()->get(379, 0, $amount);
		$lb->setCustomName("§r§l§eLucky Lootcrate");
		$lb->getNamedTag()->setString("lucky","true");
		$lb->setLore([
			"§r§fA §l§eLucky Lootcrate §r§ffound from Mining, Grinding and more...",
			"",
			"§r§l§eChance Loot:",
			"§r§l§e* §r§fRandom Tools",
			"§r§l§e* §r§fRandom Blocks",
			"§r§l§e* §r§fRandom Crate Keys",
			"§r§l§e* §r§fRandom Crates",
			"§r§l§e* §r§fRandom Bad Luck :(",
			"",
			"§r§l§cNOTE: §r§7Do not use in your base or around spawn",
			"§r§7  Some unlucky rewards can break and destroy blocks.",
			"",
			"§7(( §fPlace §7and §fBreak §7the lucky lootcrate to use. ))"
		]);
		return $lb;

	}

	/**
	 * @param Player|null $player
	 * @param int|null $amount
	 * @return Item|null
	 */
	public static function createMoneyNote(?Player $player = null, ?int $amount = null): ?Item{
		$signer = "Sage";
		$session = null;
		$amountlol = rand(1,10000000);
         if($player !== null){
			 $signer = $player->getName();
			 $session = Loader::getInstance()->getSessionManager()->getSession($player);
		 }
		 if($amount !== null){
			 $amountlol = $amount;
		 }
		 $item = ItemFactory::getInstance()->get(ItemIds::PAPER);
		 $item->getNamedTag()->setInt("moneynote", $amountlol);
		 $tag = $item->getNamedTag()->getInt("moneynote");
		 $item->setCustomName("§r§b§lMoney Note §r§7(Right-Click)");
		 $item->setLore(array(
			"§r§dValue §r§f$tag$",
			 "§r§dSigner §r§f$signer"
		 ));
		$session?->setBalance($session->getBalance() - $amountlol);
		 return $item;
	}

	/**
	 * @param Player|null $player
	 * @param float|null $amount
	 * @param int $count
	 * @param bool $subtract
	 * @return Item|null
	 */


	public static function createXPBottle(?Player $player = null, ?float $amount = null, int $count = 1, bool $subtract = false): ?Item
	{

		$signer = "Sage";
		$session = null;
		$amountlol = rand(1, 500000);
		if ($player !== null) {
			$signer = $player->getName();
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
		}

		if ($amount !== null) {
			$amountlol = $amount;
		}
			$item = ItemFactory::getInstance()->get(ItemIds::EXPERIENCE_BOTTLE,0,$count);
			$item->getNamedTag()->setInt("xpbottle", $amountlol);
			$tag = $item->getNamedTag()->getInt("xpbottle");
			$item->setCustomName("§r§a§lExperience Bottle §r§7(Throw)");
			$item->setLore(array(
				"§r§dValue §r§f$tag XP",
				"§r§dEnchanter §r§f$signer"
			));
			if($subtract) $session?->getPlayer()->getXpManager()->subtractXp($amount);
			return $item;
		}

		public static function createSoulVoucher(Player $player, int $souls): ?Item
		{
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			$session->setSouls($session->getSouls() - $souls);
			$rand = rand(1,1000);
			$vouch = ItemFactory::getInstance()->get(ItemIds::OBSERVER);
			$vouch->setCustomName("§r§c§lSouls Pouch §r§7(Right-Click) §r§f(#$rand)");
			$name = $player->getName();
			$vouch->getNamedTag()->setString("soulcontainer","true");
			$vouch->getNamedTag()->setInt("souls", $souls);
			$vouch->setLore(
				[
					"§r§7A mysterious pouch that can grant",
					"§r§7fortunes or disappointment",
					"",
					"§r§l§4Value:§r§7 " . $souls . " §r§7- Signed by ". $name,
					"§r§7Right-Click and or Place to Redeem the Souls Contained."
				]
			);
			return $vouch;
		}

	/**
	 * @param Player $player
	 * @param int $level
	 */
	public static function addSet(Player $player, int $level){
		$h = VanillaItems::DIAMOND_HELMET();
		$c = VanillaItems::DIAMOND_CHESTPLATE();
		$l = VanillaItems::DIAMOND_LEGGINGS();
		$b = VanillaItems::DIAMOND_BOOTS();
		foreach([$h, $c, $l, $b] as $gear){
			$gear->setCustomName("§r§b" . $gear->getName());
			$gear->addEnchantment(new EnchantmentInstance(VanillaEnchantments::PROTECTION(),$level));
			$gear->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(),$level));
			$player->getInventory()->addItem($gear);
		}
	}
}