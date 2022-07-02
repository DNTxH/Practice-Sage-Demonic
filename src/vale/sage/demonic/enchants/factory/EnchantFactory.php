<?php
namespace vale\sage\demonic\enchants\factory;

use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\addons\types\customenchants\CELoader;
use vale\sage\demonic\addons\types\customenchants\CEManager;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class EnchantFactory
{

	public static array $godGem = [];

	/**
	 * @param Player $player
	 * @param string $tier
	 * @param bool $agreement
	 * @param int $success
	 * @param int $destroy
	 */
	public static function giveEnchantBook(Player $player, string $tier, bool $agreement = true, int $success = 100, int $destroy = 50): void
	{
        $simple = [];
        $unique = [];
        $elite = [];
        $ultimate = [];
        $legendary = [];
        $soul = [];
        $heroic = [];
        $mastery = [];
        
        foreach (EnchantmentsManager::getEnchantsByTier(CustomEnchant::SIMPLE) as $enchantment) {
            $simple[] = $enchantment->getId();
        }
        
        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::UNIQUE) as $enchantment) {
            $unique[] = $enchantment->getId();
        }

        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::ELITE) as $enchantment) {
            $elite[] = $enchantment->getId();
        }
        
        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::ULTIMATE) as $enchantment) {
            $ultimate[] = $enchantment->getId();
        }
        
        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::LEGENDARY) as $enchantment) {
            $legendary[] = $enchantment->getId();
        }

        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::SOUL) as $enchantment) {
            $soul[] = $enchantment->getId();
        }
        
        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::HEROIC) as $enchantment) {
            $heroic[] = $enchantment->getId();
        }
        
        foreach(EnchantmentsManager::getEnchantsByTier(CustomEnchant::MASTERY) as $enchantment) {
            $mastery[] = $enchantment->getId();
        }
        
		switch ($tier) {
			case "simple":
                $rand = $simple[array_rand($simple)];
                $message = "§l§e(!) §r§eYou examined the §r§7Simple Enchantment Book... §eand discovered ";
			break;
            
			case "unique":
                $rand = $unique[array_rand($unique)];
                $message = "§l§e(!) §r§eYou examined the §r§2Unique Enchantment Book... §eand discovered ";
            break;
                
			case "elite":
                $rand = $elite[array_rand($elite)];
                $message = "§l§e(!) §r§eYou examined the §r§bElite Enchantment Book... §eand discovered ";
            break;
            
			case "ultimate":
                $rand = $ultimate[array_rand($ultimate)];
                $message = "§l§e(!) §r§eYou examined the §r§eUltimate Enchantment Book... §eand discovered ";
			break;
            
			case "legendary":
                $rand = $legendary[array_rand($legendary)];
                $message = "§l§e(!) §r§eYou examined the §r§6Legendary Enchantment Book... §eand discovered ";
			break;
            
            case "godly":
                $rand = $soul[array_rand($soul)];
                $message = "§l§e(!) §r§eYou examined the §r§cSoul Enchantment Book... §eand discovered ";
            break;
            
            case "heroic":
                $rand = $heroic[array_rand($heroic)];
                $message = "§l§e(!) §r§eYou examined the §r§dHeroic Enchantment Book... §eand discovered ";
            break;
            
            case "mastery":
                $rand = $mastery[array_rand($mastery)];
                $message = "§l§e(!) §r§eYou examined the §r§dMastery Enchantment Book... §eand discovered ";
            break;    
            
            default:
                $rand = $simple[array_rand($simple)];
                $message = "§l§e(!) §r§eYou examined the §r§7Simple Enchantment Book... §eand discovered ";
            break;    
		}

        $enchant = EnchantmentsManager::getEnchantment($rand);
        $name = $enchant->getName();
        $level = mt_rand(1, $enchant->getMaxLevel());
        $book = ItemFactory::getInstance()->get(ItemIds::ENCHANTED_BOOK,0,1);
        $book->getNamedTag()->setInt("enchuid",mt_rand(1, 918391));
        $book->setCustomName("§r§l§b$name " . EnchantmentsManager::roman($level));
        self::addEnch($book, $rand, $level);
        $book->getNamedTag()->setInt("success", $success);
        $book->getNamedTag()->setInt("destroy", $destroy);
        self::setEnchantmentLore($book);
        if ($agreement) {
            $en = "§r§l§b$name " . EnchantmentsManager::roman($level);
            $player->sendMessage($message . $en);
        }
        self::addItem($player, $book);
	}

	/**
	 * @param string $tier
	 * @param int $amount
	 * @return Item|null
	 * @throws \Exception
	 */
	public static function giveRedeemableBook(string $tier, int $amount): ?Item{
		switch ($tier) {
			case "simple":
				return Rewards::get(Rewards::SIMPLE_BOOK,$amount);
				break;
			case "unique":
				return Rewards::get(Rewards::UNIQUE_BOOK,$amount);
				break;
			case "elite":
				return Rewards::get(Rewards::ELITE_BOOK,$amount);
				break;
			case "ultimate":
				return Rewards::get(Rewards::ULITMATE_BOOK,$amount);
				break;
            case "legendary":
                return Rewards::get(Rewards::LEGENDARY_BOOK, $amount);
            break;

            case "soul":
                return Rewards::get(Rewards::GODLY_BOOK, $amount);
            break;

            case "heroic":
                return Rewards::get(Rewards::HEROIC_BOOK, $amount);
            break;

            case "mastery":
                return Rewards::get(Rewards::MASTERY_BOOK, $amount);
            break;
		}
		return null;
	}

	/**
	 * @param Player $player
	 * @param string $type
	 * @param int $amount
	 */
	public static function giveEnchantUtilities(string $type, int $amount = 1): ?Item{
		$tier = rand(1,7);
		$rand = rand(1,500);
		switch ($type){
			case "blackscroll":
				$blackscroll = ItemFactory::getInstance()->get(ItemIds::DYE,0,$amount);
				$blackscroll->setCustomName("§r§f§lBlack Scroll §r§7(§r§f#{$rand}§r§7)");
				$blackscroll->getNamedTag()->setInt("percentage", rand(1,100));
				$blackscroll->getNamedTag()->setInt("enchuid",mt_rand(1, 918391));
				$blackscroll->getNamedTag()->setString("blackscroll",true);
				$tag = $blackscroll->getNamedTag()->getInt("percentage");
				$blackscroll->setLore([
                    '§r§7Removes a random enchantment',
					'§r§7from an item and converts',
					"§r§7it into a §r§f$tag% §r§7success book.",
					"§r§fPlace scroll on item to extract."
				]);
				return $blackscroll;
				break;
			case "transmong":
				$transmong = ItemFactory::getInstance()->get(ItemIds::PAPER,0,$amount);
				$transmong->setCustomName("§r§e§lTransmog Scroll");
				$transmong->setLore(
					[
						'§r§7Organizes enchants by §r§erarity §r§7on item',
						'§r§7and adds the §r§dlore §bcount §r§7to name.',
						'',
						'§r§e§oPlace scroll on item to apply.'
					]
				);
				$transmong->getNamedTag()->setString("transmong","true");
				return $transmong;
				break;
			case "extractor":
				$ex = ItemFactory::getInstance()->get(ItemIds::GHAST_TEAR,0,$amount);
				$ex->setCustomName("§r§6§lCrystal Extractor §r§f(#$rand)");
				$ex->setLore([
					'§r§7Removes a single crystal from',
					'§r§7an armor piece or weapon and converts it',
					'§r§7into its aplicable form:',
					'§r§f§l* §6§lWeapon Set Crystal (§r§f100% Success§r§6§l)',
					'§r§f§lOR',
					"§r§f§l* §r§6§lArmor Set Crystal (§r§f100% Success§r§6§l)",
					'§r§8minecraft:ghast_tear',
					'§r§8NBT: 3 tag(s)'
				]);
				$ex->getNamedTag()->setString("crystalextractor","true");
				return $ex;
				break;
			case "whitescroll":
				$whitescroll = ItemFactory::getInstance()->get(ItemIds::MAP,0,$amount);
				$whitescroll->setCustomName("§r§eWhite Scroll §r§7(§r§f#{$rand}§r§7)");
				$whitescroll->getNamedTag()->setInt("enchuid",mt_rand(1, 918391));
				$whitescroll->getNamedTag()->setString("whitescroll",true);
				$whitescroll->setLore([
					'§r§7Prevents an item from being destroyed',
					'§r§7due to a failed Enchantment Book.',
					"§r§ePlace scroll on item to apply."
				]);
				return $whitescroll;
				break;
			case "armourorb":
				$orb = ItemFactory::getInstance()->get(ItemIds::ENDER_EYE,0,$amount);
				$orb->getNamedTag()->setInt("orb",$tier);
				$value = $orb->getNamedTag()->getInt("orb");
				$orb->setCustomName("§r§6§lArmor Enchantment Orb [§r§a{$value}§r§6§l] §r§7(§r§f#{$rand}§r§7)");
				$orb->getNamedTag()->setString("armororb",true);
				$orb->setLore([
					'§r§a100% Success Rate',
					'',
					"§r§6+ {$value} Enchantment Slots.",
					'§r§6DEFAULT: 7 Max Enchant Slots §r§7(LIMIT: 15)',
					'',
					"§r§eIncreases the # of enchantment",
					"§r§eslots on a piece of armour by $value,",
					"§r§eup to a maximum of 15.",
					"§r§7Drag 'n Drop on a item to apply."
				]);
				return $orb;
				break;
			case "weaponorb":
				$orb = ItemFactory::getInstance()->get(ItemIds::ENDER_EYE,0,$amount);
				$orb->getNamedTag()->setInt("orb",$tier);
				$value = $orb->getNamedTag()->getInt("orb");
				$orb->setCustomName("§r§6§lWeapon Enchantment Orb [§r§a{$value}§r§6§l] §r§7(§r§f#{$rand}§r§7)");
				$orb->getNamedTag()->setString("weaponorb",true);
				$orb->setLore([
					'§r§a100% Success Rate',
					'',
					"§r§6+ {$value} Enchantment Slots.",
					'§r§67 Max Enchant Slots §r§7(LIMIT: 15)',
					'',
					"§r§eIncreases the # of enchantment",
					"§r§eslots on a piece of weapon by $value,",
					"§r§eup to a maximum of 15.",
					"§r§7Drag 'n Drop on a item to apply."
				]);
			return $orb;

				break;
		}
		return null;
	}

	/**
	 * @param string $identifier
	 * @param int $amount
	 * @return Item|null
	 */
	public static function getEnchantRandomizer(string $identifier, int $amount = 1): ?Item{
		$item = ItemFactory::getInstance()->get(ItemIds::AIR);
		$rand = rand(1,983);
		switch ($identifier){
			case "simple":
				$item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
				$item->setCustomName("§r§7§lSimple Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
				$item->setLore(array(
					"§r§7Apply to a(n) §r§7Simple enchantment book",
					"§r§7to reroll the success and destroy rates.",
					"",
					"§r§7Drag n' Drop onto enchantment book to apply"
				));
				$item->getNamedTag()->setInt("type", CustomEnchant::SIMPLE);
				$item->getNamedTag()->setString("randomizer", "true");
				break;
			case "elite":
				$item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
				$item->setCustomName("§r§b§lElite Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
				$item->setLore(array(
					"§r§7Apply to a(n) §r§bElite enchantment book",
					"§r§7to reroll the success and destroy rates.",
					"",
					"§r§7Drag n' Drop onto enchantment book to apply"
				));
				$item->getNamedTag()->setInt("type", CustomEnchant::ELITE);
				$item->getNamedTag()->setString("randomizer", "true");
				break;
			case "ultimate":
				$item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
				$item->setCustomName("§r§e§lUltimate Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
				$item->setLore(array(
					"§r§7Apply to a(n) §r§eUltimate enchantment book",
					"§r§7to reroll the success and destroy rates.",
					"",
					"§r§7Drag n' Drop onto enchantment book to apply"
				));
				$item->getNamedTag()->setInt("type", CustomEnchant::ULTIMATE);
				$item->getNamedTag()->setString("randomizer", "true");
				break;
			case "legendary":
				$item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
				$item->setCustomName("§r§6§lLegendary Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
				$item->setLore(array(
					"§r§7Apply to a(n) §r§6Legendary enchantment book",
					"§r§7to reroll the success and destroy rates.",
					"",
					"§r§7Drag n' Drop onto enchantment book to apply"
				));
				$item->getNamedTag()->setInt("type", CustomEnchant::LEGENDARY);
				$item->getNamedTag()->setString("randomizer", "true");
				break;

            case "soul":
                $item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
                $item->setCustomName("§r§6§lSoul Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
                $item->setLore(array(
                    "§r§7Apply to a(n) §r§cSoul enchantment book",
                    "§r§7to reroll the success and destroy rates.",
                    "",
                    "§r§7Drag n' Drop onto enchantment book to apply"
                ));
                $item->getNamedTag()->setInt("type", CustomEnchant::SOUL);
                $item->getNamedTag()->setString("randomizer", "true");
            break;

            case "heroic":
                $item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
                $item->setCustomName("§r§d§lHeroic Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
                $item->setLore(array(
                    "§r§7Apply to a(n) §r§dHeroic enchantment book",
                    "§r§7to reroll the success and destroy rates.",
                    "",
                    "§r§7Drag n' Drop onto enchantment book to apply"
                ));
                $item->getNamedTag()->setInt("type", CustomEnchant::HEROIC);
                $item->getNamedTag()->setString("randomizer", "true");
            break;

            case "mastery":
                $item = ItemFactory::getInstance()->get(ItemIds::EMPTY_MAP,0,$amount);
                $item->setCustomName("§r§d§lMastery Randomization Scroll §r§7(§r§f#{$rand}§r§7)");
                $item->setLore(array(
                    "§r§7Apply to a(n) §r§dMastery enchantment book",
                    "§r§7to reroll the success and destroy rates.",
                    "",
                    "§r§7Drag n' Drop onto enchantment book to apply"
                ));
                $item->getNamedTag()->setInt("type", CustomEnchant::MASTERY);
                $item->getNamedTag()->setString("randomizer", "true");
            break;
        }
		return $item;
	}

	public static function getSatchelOfDust(){

	}

	public static function getRandomEnchantDust(): ?Item{
		$lol = [
	    EnchantFactory::getEnchantDust("simple",true,rand(1,3),rand(1,8)),
			EnchantFactory::getEnchantDust("simple",true,rand(1,3),rand(1,8)),
				EnchantFactory::getEnchantDust("elite",true,rand(1,3),rand(1,30)),
		EnchantFactory::getEnchantDust("elite",false,rand(1,3),rand(1,8)),
		EnchantFactory::getEnchantDust("unique",true,rand(1,3),rand(1,30)),
        EnchantFactory::getEnchantDust("unique",false,rand(1,3),rand(1,8)),
		EnchantFactory::getEnchantDust("ultimate",true,rand(1,3),rand(1,30)),
		EnchantFactory::getEnchantDust("ultimate",false,rand(1,3),rand(1,8)),
		EnchantFactory::getEnchantDust("legendary",true,rand(1,3),rand(1,30)),
        EnchantFactory::getEnchantDust("legendary",false,rand(1,3),rand(1,8)),
		];
		return $lol[array_rand($lol)];
	}


	/**
	 * @param string $identifier
	 * @param bool $primal
	 * @param int $amount
	 * @param int $success
	 * @return Item
	 */
	public static function getEnchantDust(string $identifier, bool $primal = false, int $amount = 1, int $success = 10):Item
	{
		$tier = rand(1,15);
		if($primal){
			$item = ItemFactory::getInstance()->get(ItemIds::GLOWSTONE_DUST,0,$amount);
			$item->getNamedTag()->setInt("dust",$success);
			$item->getNamedTag()->setString("dustadder","true");
			$value = $item->getNamedTag()->getInt("dust");
		}
		if(!$primal) {
			$item = ItemFactory::getInstance()->get(ItemIds::SUGAR, 0, 1);
			$item->getNamedTag()->setInt("dust", $success);
			$item->getNamedTag()->setString("dustadder", "true");
			$value = $item->getNamedTag()->getInt("dust");
		}
		switch ($identifier){
			case "simple":
				if($primal){
					$item->setCustomName("§r§f§lSimple Primal Dust");
					$item->getNamedTag()->setInt("dustrarity",CustomEnchant::SIMPLE);
					$item->setLore([
						'§r§a§l+'. $value . "% Success",
						"§r§7Apply to a §r§f§lSimple §r§7Enchantment Book",
						"§r§7to increase its success rate by§r§f§l ". $value . "%",
						"",
						"§r§7Place dust on enchantment book."
					]);
					return $item;
				}
				$item->setCustomName("§r§fSimple Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::SIMPLE);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§fSimple §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§f ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "unique":
				if($primal){
					$item->setCustomName("§r§2§lUnique Primal Dust");
					$item->getNamedTag()->setInt("dustrarity",CustomEnchant::UNIQUE);
					$item->setLore([
						'§r§a§l+'. $value . "% Success",
						"§r§7Apply to a §r§2§lUnique §r§7Enchantment Book",
						"§r§7to increase its success rate by§r§2§l ". $value . "%",
						"",
						"§r§7Place dust on enchantment book."
					]);
					return $item;
				}
				$item->setCustomName("§r§2Unique Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::UNIQUE);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§2Unique §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§2 ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "elite":
				if($primal){
					$item->setCustomName("§r§b§lElite Primal Dust");
					$item->getNamedTag()->setInt("dustrarity",CustomEnchant::ELITE);
					$item->setLore([
						'§r§a§l+'. $value . "% Success",
						"§r§7Apply to a §r§b§lElite §r§7Enchantment Book",
						"§r§7to increase its success rate by§r§b§l ". $value . "%",
						"",
						"§r§7Place dust on enchantment book."
					]);
					return $item;
				}
				$item->setCustomName("§r§bElite Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::ELITE);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§bElite §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§b ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "ultimate":
				if($primal){
					$item->setCustomName("§r§e§lUltimate Primal Dust");
					$item->getNamedTag()->setInt("dustrarity",CustomEnchant::ULTIMATE);
					$item->setLore([
						'§r§a§l+'. $value . "% Success",
						"§r§7Apply to a §r§e§lUltimate §r§7Enchantment Book",
						"§r§7to increase its success rate by§r§e§l ". $value . "%",
						"",
						"§r§7Place dust on enchantment book."
					]);
					return $item;
				}
				$item->setCustomName("§r§eUltimate Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::ULTIMATE);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§eUltimate §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§e ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "legendary":
				if($primal){
					$item->setCustomName("§r§6§lLegendary Primal Dust");
					$item->getNamedTag()->setInt("dustrarity",CustomEnchant::LEGENDARY);
					$item->setLore([
						'§r§a§l+'. $value . "% Success",
						"§r§7Apply to a §r§6§lLegendary §r§7Enchantment Book",
						"§r§7to increase its success rate by§r§6§l ". $value . "%",
						"",
						"§r§7Place dust on enchantment book."
					]);
					return $item;
				}
				$item->setCustomName("§r§6Legendary Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::LEGENDARY);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§6Legendary §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§6 ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "godly":
				$item->setCustomName("§r§cSoul Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::SOUL);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§cSoul §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§c ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "heroic":
				$item->setCustomName("§r§dHeroic Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::HEROIC);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§dHeroic §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§d ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
			case "mastery":
				$item->setCustomName("§r§4Mastery Magic Dust");
				$item->getNamedTag()->setInt("dustrarity",CustomEnchant::MASTERY);
				$item->setLore([
					'§r§a+'. $value . "% Success",
					"§r§7Apply to a §r§4Mastery §r§7Enchantment Book",
					"§r§7to increase its success rate by§r§4 ". $value . "%",
					"",
					"§r§7Place dust on enchantment book."
				]);
				break;
		}
		return $item;
	}



	/**
	 * @param Item $book
	 */
	public static function setEnchantmentLore(Item $book): void
	{
		if ($book->hasEnchantments()) {
			foreach ($book->getEnchantments() as $enchants) {
				$successrate = $book->getNamedTag()->getInt("success");
				$destroyrate = $book->getNamedTag()->getInt("destroy");
				$lore = $book->getLore();
				$lore[] = "§r§a$successrate% Success Rate";
				$lore[] = "§r§c$destroyrate% Destroy Rate";
				$lore[] = "";
                if($enchants->getType() instanceof CustomEnchant) {
                    $lore[] = TextFormat::RESET . TextFormat::YELLOW . EnchantmentsManager::getEnchantment($enchants->getType()->getId())->getDescription();
                    $lore[] = TextFormat::RESET . TextFormat::WHITE . "Drag 'n Drop on a item to enchant.";
                }
                $book->setLore($lore);
			}
		}
	}

	public static function addItem(Player $player, Item $item): void{

		if(!$player->getInventory()->canAddItem($item)){
			$player->sendTitle("§l§4FULL INVENTORY", "§r§7your items will fall on the ground");
			$player->getWorld()->dropItem($player->getLocation()->asVector3(),$item);
		}else{
			$player->getInventory()->addItem($item);
		}
	}

	/**
	 * @param Player|null $player
	 * @param int $souls
	 * @return Item|null
	 */
	public static function createSoulGem(Player $player = null, int $souls = 100) : ?Item{
		$item = ItemFactory::getInstance()->get(ItemIds::EMERALD,0,1);
		$item->getNamedTag()->setInt("souls", $souls);
		$item->getNamedTag()->setString("soulgem","true");
		$item->setCustomName("§r§c§lSoul Gem");
		$item->setLore(array(
			"§r§c§l* §r§cClick this item to toggle Soul Mode",
			"",
			"§r§7While in Soul Mode your ACTIVE god tier",
			"§r§7enchantments will activate and drain souls",
			"§r§7for as long as this mode is enabled.",
		));
		return $item;
	}
	/**
	 * @param Item $item
	 * @param $enchant
	 * @param int $level
	 * @param string|null $name
	 */
	public static function addEnch(Item $item, $enchant, int $level, string $name = null): void{
		$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId($enchant), $level));
		$item->setCustomName("§r" . $item->getName());
		if($name != null){
			$item->setCustomName($name);
		}
	}

	/**
	 * @param int $tier
	 * @return string
	 */
	public static function determineCrystalLore(int $tier): string{
		$lore = "";
		switch ($tier){
			case 0:
				$lore = "§r§6§lWeapon Crystal (§r§4§lDEMONIC§r§6§l)";
				break;
			case 1:
				$lore = "§r§6§lWeapon Crystal (§r§d§lBLOODSUCK§r§d§l)";
				break;
		}
		return $lore;
	}

	/**
	 * @param int $rarity
	 * @param int $amount
	 * @param Player $player
	 * @return Item|null
	 */
    public static function giveDust(int $rarity, int $amount, Player $player): ?Item{
		return match ($rarity){
			CustomEnchant::SIMPLE => self::getEnchantDust("simple",false,$amount),
			CustomEnchant::ELITE => self::getEnchantDust("elite",false,$amount),
			CustomEnchant::ULTIMATE => self::getEnchantDust("ultimate", false, $amount),
			CustomEnchant::LEGENDARY => self::getEnchantDust("legendary",false,$amount),
			CustomEnchant::UNIQUE => self::getEnchantDust("unique",false,$amount),
            CustomEnchant::SOUL => self::getEnchantDust("godly",false,$amount),
            CustomEnchant::HEROIC => self::getEnchantDust("heroic",false,$amount),
            CustomEnchant::MASTERY => self::getEnchantDust("mastery",false,$amount),
		};
    }
}