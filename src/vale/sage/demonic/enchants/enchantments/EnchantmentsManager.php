<?php


namespace vale\sage\demonic\enchants\enchantments;

use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\item\Stick;
use vale\sage\demonic\enchants\enchantments\type\mastery\DemonicGateway;
use vale\sage\demonic\enchants\enchantments\type\elite\Cactus;
use pocketmine\item\Armor;
use pocketmine\item\Bow;
use pocketmine\item\Tool;
use vale\sage\demonic\enchants\enchantments\type\elite\Blind;
use vale\sage\demonic\enchants\enchantments\type\elite\Demonforged;
use vale\sage\demonic\enchants\enchantments\type\elite\Execute;
use vale\sage\demonic\enchants\enchantments\type\elite\Farcast;
use vale\sage\demonic\enchants\enchantments\type\elite\Frozen;
use vale\sage\demonic\enchants\enchantments\type\elite\Greatsword;
use vale\sage\demonic\enchants\enchantments\type\elite\Hardened;
use vale\sage\demonic\enchants\enchantments\type\elite\Infernal;
use vale\sage\demonic\enchants\enchantments\type\elite\Paralyze;
use vale\sage\demonic\enchants\enchantments\type\elite\Poison;
use vale\sage\demonic\enchants\enchantments\type\elite\Poisoned;
use vale\sage\demonic\enchants\enchantments\type\elite\Pummel;
use vale\sage\demonic\enchants\enchantments\type\elite\Reforged;
use vale\sage\demonic\enchants\enchantments\type\elite\RocketEscape;
use vale\sage\demonic\enchants\enchantments\type\elite\Shackle;
use vale\sage\demonic\enchants\enchantments\type\elite\Shockwave;
use vale\sage\demonic\enchants\enchantments\type\elite\Smokebomb;
use vale\sage\demonic\enchants\enchantments\type\elite\Snare;
use vale\sage\demonic\enchants\enchantments\type\elite\SpiritLink;
use vale\sage\demonic\enchants\enchantments\type\elite\Stormcaller;
use vale\sage\demonic\enchants\enchantments\type\elite\Teleportation;
use vale\sage\demonic\enchants\enchantments\type\elite\Trap;
use vale\sage\demonic\enchants\enchantments\type\elite\Trickster;
use vale\sage\demonic\enchants\enchantments\type\elite\Vampire;
use vale\sage\demonic\enchants\enchantments\type\elite\Venom;
use vale\sage\demonic\enchants\enchantments\type\elite\Voodoo;
use vale\sage\demonic\enchants\enchantments\type\elite\Wither;
use vale\sage\demonic\enchants\enchantments\type\mastery\FeignDeath;
use vale\sage\demonic\enchants\enchantments\type\heroic\AtomicDetonate;
use vale\sage\demonic\enchants\enchantments\type\heroic\BewitchedHex;
use vale\sage\demonic\enchants\enchantments\type\heroic\BidirectionalTeleportation;
use vale\sage\demonic\enchants\enchantments\type\heroic\BrutalBarbarian;
use vale\sage\demonic\enchants\enchantments\type\heroic\DemonicLifesteal;
use vale\sage\demonic\enchants\enchantments\type\heroic\DivineEnlightened;
use vale\sage\demonic\enchants\enchantments\type\heroic\EtherealDodge;
use vale\sage\demonic\enchants\enchantments\type\heroic\ExtremeInsanity;
use vale\sage\demonic\enchants\enchantments\type\heroic\GodlyOverload;
use vale\sage\demonic\enchants\enchantments\type\heroic\GuidedRocketEscape;
use vale\sage\demonic\enchants\enchantments\type\heroic\MaliciouslyCorrupt;
use vale\sage\demonic\enchants\enchantments\type\heroic\MartyrValor;
use vale\sage\demonic\enchants\enchantments\type\heroic\MasterBlacksmith;
use vale\sage\demonic\enchants\enchantments\type\heroic\MasterInquisitive;
use vale\sage\demonic\enchants\enchantments\type\heroic\MegaHeavy;
use vale\sage\demonic\enchants\enchantments\type\heroic\MightyCleave;
use vale\sage\demonic\enchants\enchantments\type\heroic\PerfectSolitude;
use vale\sage\demonic\enchants\enchantments\type\heroic\PermanentExecute;
use vale\sage\demonic\enchants\enchantments\type\heroic\PlanetaryDeathbringer;
use vale\sage\demonic\enchants\enchantments\type\heroic\PolymorphicMetaphysical;
use vale\sage\demonic\enchants\enchantments\type\heroic\ReflectiveBlock;
use vale\sage\demonic\enchants\enchantments\type\heroic\ReinforcedTank;
use vale\sage\demonic\enchants\enchantments\type\heroic\ShadowAssassin;
use vale\sage\demonic\enchants\enchantments\type\heroic\SoulHardened;
use vale\sage\demonic\enchants\enchantments\type\heroic\TitanTrap;
use vale\sage\demonic\enchants\enchantments\type\heroic\UnrestrainedEnrage;
use vale\sage\demonic\enchants\enchantments\type\heroic\VengefulDiminish;
use vale\sage\demonic\enchants\enchantments\type\legendary\Armored;
use vale\sage\demonic\enchants\enchantments\type\legendary\Barbarian;
use vale\sage\demonic\enchants\enchantments\type\legendary\Blacksmith;
use vale\sage\demonic\enchants\enchantments\type\legendary\BloodLust;
use vale\sage\demonic\enchants\enchantments\type\legendary\Clarity;
use vale\sage\demonic\enchants\enchantments\type\legendary\Deathbringer;
use vale\sage\demonic\enchants\enchantments\type\legendary\DeathCoffin;
use vale\sage\demonic\enchants\enchantments\type\legendary\DeathGod;
use vale\sage\demonic\enchants\enchantments\type\legendary\Destruction;
use vale\sage\demonic\enchants\enchantments\type\legendary\Diminish;
use vale\sage\demonic\enchants\enchantments\type\legendary\Disarmor;
use vale\sage\demonic\enchants\enchantments\type\legendary\DoubleStrike;
use vale\sage\demonic\enchants\enchantments\type\legendary\Drunk;
use vale\sage\demonic\enchants\enchantments\type\legendary\Enlightened;
use vale\sage\demonic\enchants\enchantments\type\legendary\Gears;
use vale\sage\demonic\enchants\enchantments\type\legendary\Hex;
use vale\sage\demonic\enchants\enchantments\type\legendary\Inquisitive;
use vale\sage\demonic\enchants\enchantments\type\legendary\Insanity;
use vale\sage\demonic\enchants\enchantments\type\legendary\Inversion;
use vale\sage\demonic\enchants\enchantments\type\legendary\KillAura;
use vale\sage\demonic\enchants\enchantments\type\legendary\Leadership;
use vale\sage\demonic\enchants\enchantments\type\legendary\Lifesteal;
use vale\sage\demonic\enchants\enchantments\type\legendary\Overload;
use vale\sage\demonic\enchants\enchantments\type\legendary\Overwhelm;
use vale\sage\demonic\enchants\enchantments\type\legendary\Protection;
use vale\sage\demonic\enchants\enchantments\type\legendary\Silence;
use vale\sage\demonic\enchants\enchantments\type\mastery\MarkOfTheBeast;
use vale\sage\demonic\enchants\enchantments\type\mastery\MortalCoil;
use vale\sage\demonic\enchants\enchantments\type\mastery\RotAndDecay;
use vale\sage\demonic\enchants\enchantments\type\simple\Aquatic;
use vale\sage\demonic\enchants\enchantments\type\simple\AutoSmelt;
use vale\sage\demonic\enchants\enchantments\type\simple\Confusion;
use vale\sage\demonic\enchants\enchantments\type\simple\Epicness;
use vale\sage\demonic\enchants\enchantments\type\simple\Experience;
use vale\sage\demonic\enchants\enchantments\type\simple\Glowing;
use vale\sage\demonic\enchants\enchantments\type\simple\Haste;
use vale\sage\demonic\enchants\enchantments\type\simple\Healing;
use vale\sage\demonic\enchants\enchantments\type\simple\Insomnia;
use vale\sage\demonic\enchants\enchantments\type\simple\Lightning;
use vale\sage\demonic\enchants\enchantments\type\simple\Obliterate;
use vale\sage\demonic\enchants\enchantments\type\simple\Oxygenate;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStack;
use vale\sage\demonic\enchants\enchantments\type\soul\DivineImmolation;
use vale\sage\demonic\enchants\enchantments\type\soul\Immortal;
use vale\sage\demonic\enchants\enchantments\type\soul\NaturesWrath;
use vale\sage\demonic\enchants\enchantments\type\soul\Paradox;
use vale\sage\demonic\enchants\enchantments\type\soul\Phoenix;
use vale\sage\demonic\enchants\enchantments\type\soul\Rogue;
use vale\sage\demonic\enchants\enchantments\type\soul\Sabotage;
use vale\sage\demonic\enchants\enchantments\type\soul\SoulTether;
use vale\sage\demonic\enchants\enchantments\type\soul\SoulTrap;
use vale\sage\demonic\enchants\enchantments\type\soul\Teleblock;
use vale\sage\demonic\enchants\enchantments\type\mastery\SoulSiphon;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Angelic;
use vale\sage\demonic\enchants\enchantments\type\ultimate\ArrowBreak;
use vale\sage\demonic\enchants\enchantments\type\ultimate\ArrowLifesteal;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Assassin;
use vale\sage\demonic\enchants\enchantments\type\ultimate\AvengingAngel;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Blessed;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Block;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Cleave;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Corrupt;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Detonate;
use vale\sage\demonic\enchants\enchantments\type\ultimate\DimensionRift;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Disintegrate;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Dodge;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Dominate;
use vale\sage\demonic\enchants\enchantments\type\ultimate\EagleEye;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Enrage;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Heavy;
use vale\sage\demonic\enchants\enchantments\type\ultimate\IceAspect;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Longbow;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Marksman;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Metaphysical;
use vale\sage\demonic\enchants\enchantments\type\ultimate\ObsidianShield;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Piercing;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Sticky;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Tank;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Unfocus;
use vale\sage\demonic\enchants\enchantments\type\ultimate\Valor;
use vale\sage\demonic\enchants\enchantments\type\unique\Berserk;
use vale\sage\demonic\enchants\enchantments\type\unique\Commander;
use vale\sage\demonic\enchants\enchantments\type\unique\Curse;
use vale\sage\demonic\enchants\enchantments\type\unique\DeepWounds;
use vale\sage\demonic\enchants\enchantments\type\unique\EnderShift;
use vale\sage\demonic\enchants\enchantments\type\unique\Explosive;
use vale\sage\demonic\enchants\enchantments\type\unique\FeatherWeight;
use vale\sage\demonic\enchants\enchantments\type\unique\Lifebloom;
use vale\sage\demonic\enchants\enchantments\type\unique\Molten;
use vale\sage\demonic\enchants\enchantments\type\unique\PlagueCarrier;
use vale\sage\demonic\enchants\enchantments\type\unique\Ragdoll;
use vale\sage\demonic\enchants\enchantments\type\unique\Ravenous;
use vale\sage\demonic\enchants\enchantments\type\unique\SelfDestruct;
use vale\sage\demonic\enchants\enchantments\type\unique\SkillSwipe;
use vale\sage\demonic\enchants\enchantments\type\unique\Telepathy;
use vale\sage\demonic\enchants\enchantments\type\unique\Virus;
use vale\sage\demonic\Loader;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;

class EnchantmentsManager{
    use SingletonTrait;

    /** @var array */
    private static array $enchants;

    /** @var array */
    private static array $ids;

    /** @var array */
    private static array $tiers;
    
    /** @var array */
    private static array $equips;

    /** @var Loader */
	private Loader $plugin;

    /**
     * @param Loader $plugin
     */
    public function __construct(Loader $plugin) {
		$this->plugin = $plugin;
        $this->registerEnchants();
    }
	
    public function registerEnchants() : void
    {
        self::register(new Confusion());
        self::register(new Experience());
        self::register(new Insomnia());
        self::register(new Lightning());
        self::register(new Obliterate());
        self::register(new Haste());
        self::register(new Healing());
        self::register(new Epicness());
        self::register(new AutoSmelt());
        self::register(new Aquatic());
        self::register(new Glowing());
        self::register(new Oxygenate());
        self::register(new DeepWounds());
        self::register(new FeatherWeight());
        self::register(new Ragdoll());
        self::register(new Explosive());
        self::register(new Berserk());
        self::register(new PlagueCarrier());
        self::register(new SelfDestruct());
        self::register(new Molten());
        self::register(new Ravenous());
        self::register(new EnderShift());
        self::register(new SkillSwipe());
        self::register(new Curse());
        self::register(new Telepathy());
        self::register(new Lifebloom());
        self::register(new Greatsword());
        self::register(new Blind());
        self::register(new Paralyze());
        self::register(new Poison());
        self::register(new Shackle());
        self::register(new Poisoned());
        self::register(new Stormcaller());
        self::register(new Infernal());
        self::register(new Venom());
        self::register(new Teleportation());
        self::register(new Vampire());
        self::register(new Frozen());
        self::register(new Wither());
        self::register(new Farcast());
        self::register(new Snare());
        self::register(new Shockwave());
        self::register(new Cactus());
        self::register(new Voodoo());
        self::register(new Demonforged());
        self::register(new Smokebomb());
        self::register(new Execute());
        self::register(new Trickster());
        self::register(new Trap());
        self::register(new Marksman());
        self::register(new Block());
        self::register(new Detonate());
        self::register(new DimensionRift());
        self::register(new IceAspect());
        self::register(new Assassin());
        self::register(new Heavy());
        self::register(new Dodge());
        self::register(new ObsidianShield());
        self::register(new ArrowLifesteal());
        self::register(new Valor());
        self::register(new ArrowBreak());
        self::register(new Enrage());
        self::register(new Tank());
        self::register(new Blessed());
        self::register(new Longbow());
        self::register(new Angelic());
        self::register(new Piercing());
        self::register(new Corrupt());
        self::register(new Reforged());
        self::register(new Disintegrate());
        self::register(new DeathGod());
        self::register(new DoubleStrike());
        self::register(new Barbarian());
        self::register(new Lifesteal());
        self::register(new Enlightened());
        self::register(new Overload());
        self::register(new Clarity());
        self::register(new Inquisitive());
        self::register(new Inversion());
        self::register(new Armored());
        self::register(new Drunk());
        self::register(new Blacksmith());
        self::register(new Deathbringer());
        self::register(new Sticky());
        self::register(new Disarmor());
        self::register(new Gears());
        self::register(new Insanity());
        self::register(new Teleblock());
        self::register(new Immortal());
        self::register(new Phoenix());
        self::register(new Rogue());
        self::register(new SoulTrap());
        self::register(new DivineImmolation());
        self::register(new NaturesWrath());
        self::register(new Paradox());
        self::register(new Virus());
        self::register(new Commander());
        self::register(new Pummel());
        self::register(new SpiritLink());
        self::register(new Cleave());
        self::register(new EagleEye());
        self::register(new BloodLust());
        self::register(new Leadership());
        self::register(new Protection());
        self::register(new DeathCoffin());
        self::register(new GodlyOverload());
        self::register(new MasterInquisitive());
        self::register(new ShadowAssassin());
        self::register(new EtherealDodge());
        self::register(new PlanetaryDeathbringer());
        self::register(new AtomicDetonate());
        self::register(new DemonicLifesteal());
        self::register(new DivineEnlightened());
        self::register(new MasterBlacksmith());
        self::register(new BidirectionalTeleportation());
        self::register(new MightyCleave());
        self::register(new ReinforcedTank());
        self::register(new TitanTrap());
        self::register(new SoulHardened());
        self::register(new MegaHeavy());
        self::register(new ExtremeInsanity());
        self::register(new BrutalBarbarian());
        self::register(new UnrestrainedEnrage());
        self::register(new MartyrValor());
        self::register(new PermanentExecute());
        self::register(new Destruction());
        self::register(new ReflectiveBlock());
        self::register(new DemonicGateway());
        self::register(new FeignDeath());
        self::register(new MarkOfTheBeast());
        self::register(new MortalCoil());
        self::register(new RotAndDecay());
        self::register(new SoulSiphon());
        self::register(new RocketEscape());
        self::register(new Hardened());
        self::register(new Unfocus());
        self::register(new Metaphysical());
        self::register(new Dominate());
        self::register(new AvengingAngel());
        self::register(new Silence());
        self::register(new KillAura());
        self::register(new Diminish());
        self::register(new Hex());
        self::register(new Overwhelm());
        self::register(new Sabotage());
        self::register(new SoulTether());
        self::register(new VengefulDiminish());
        self::register(new GuidedRocketEscape());
        self::register(new PolymorphicMetaphysical());
        self::register(new BewitchedHex());
        self::register(new PerfectSolitude());
        self::register(new MaliciouslyCorrupt());
    }

    /**
     * @param CustomEnchant $enchant
     * @return void
     */
    public static function register(CustomEnchant $enchant) {
        EnchantmentIdMap::getInstance()->register($enchant->getId(), $enchant);
        /** @var CustomEnchant $enchant */
        $enchant = EnchantmentIdMap::getInstance()->fromId($enchant->getId());
        self::$enchants[$enchant->getName()] = $enchant;
		self::$ids[$enchant->getId()] = $enchant;
        self::$tiers[$enchant->getTier()][] = $enchant;
        self::$tiers[$enchant->getEquipType()][] = $enchant;
    }

    /**
     * @param int $id
     * @return string
     */
    public static function translateId(int $id) : string {
		$result = match($id) {
			CustomEnchant::SIMPLE    => "Simple",
			CustomEnchant::UNIQUE    => "Unique",
			CustomEnchant::ELITE     => "Elite",
			CustomEnchant::ULTIMATE  => "Ultimate",
			CustomEnchant::LEGENDARY => "Legendary",
			CustomEnchant::SOUL      => "Soul",
			CustomEnchant::HEROIC    => "Heroic",
			CustomEnchant::MASTERY   => "Mastery"
		};
        return $result;
    }

    /**
     * @param int $lvl
     * @return string
     */
    public static function roman(int $lvl): string {
        $string = "";
		$romans = [
			'M' => 1000,
            'CM' => 900,
            'D' => 500,
            'CD' => 400,
            'C' => 100,
            'XC' => 90,
            'L' => 50,
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
		];
		while($lvl > 0) {
			foreach($romans as $roman => $int) {
				if($lvl >= $int) {
					$lvl -= $int;
					$string .= $roman;
					break;
				}
			}
		}
		return $string;
    }

    /**
     * @return array
     */
    public static function getEnchants() : array {
        return self::$enchants;
    }

    /**
     * @param Player $player
     * @return array
     */
    public static function getPlayerEnchants(Player $player) : array {
        $enchants = [];
        $item = $player->getInventory()->getItemInHand();
        if ($item->hasEnchantments()) {
            foreach ($item->getEnchantments() as $ce) {
                if ($ce->getType() instanceof CustomEnchant) {
                    $enchant = EnchantmentIdMap::getInstance()->fromId($ce->getType()->getId());
                    $level = $item->getEnchantment($enchant)->getLevel();
                    $enchants[$enchant->getAction()][] = [$enchant, $level];
                }
            }
        }
        foreach ($player->getArmorInventory()->getContents() as $armor) {
            if ($armor instanceof Item) {
                foreach ($armor->getEnchantments() as $ce) {
                    if ($ce->getType() instanceof CustomEnchant) {
                        $enchant = EnchantmentIdMap::getInstance()->fromId($ce->getType()->getId());
                        $level = $armor->getEnchantment($enchant)->getLevel();
                        $enchants[$enchant->getAction()][] = [$enchant, $level];
                    }
                }
            }
        }
        return $enchants;
    }

    /**
     * @param string $name
     * @return false|string
     */
    public static function getEnchantDescription(string $name) {
        if (!isset(self::$enchants[$name])) return false;
        if (!self::$enchants[$name] instanceof CustomEnchant) return false;
        return self::$enchants[$name]->getDescription();
    }

    /**
     * @param string $description
     * @param int $characters
     * @return string[]
     */
    public static function splitSentence(string $description, int $characters = 40) {
        return explode("\n", wordwrap($description, $characters));
    }

    /**
     * @param Item $item
     * @param CustomEnchant $enchant
     * @param int $level
     * @return Item
     */
    public static function applyEnchant(Item $item, CustomEnchant $enchant, int $level) : Item {
		if($item->hasEnchantment($enchant)) {
			$item->removeEnchantment($enchant);
		}
        $item->addEnchantment(new EnchantmentInstance($enchant, $level));
        return $item;
    }

    /**
     * @param CustomEnchant $enchant
     * @return string
     */
    public static function getColor(CustomEnchant $enchant) : string {
        $tier = $enchant->getTier();
        $color = match($tier)
        {
            CustomEnchant::SIMPLE    => C::RESET.C::GRAY,
            CustomEnchant::UNIQUE    => C::RESET.C::GREEN,
            CustomEnchant::ELITE     => C::RESET.C::AQUA,
            CustomEnchant::ULTIMATE  => C::RESET.C::YELLOW,
            CustomEnchant::LEGENDARY => C::RESET.C::GOLD,
            CustomEnchant::SOUL      => C::RESET.C::RED,
            CustomEnchant::HEROIC    => C::RESET.C::LIGHT_PURPLE,
            CustomEnchant::MASTERY   => C::RESET.C::DARK_RED
        };
        return $color;
    }

    /**
     * @param int $tier
     * @return CustomEnchant[]
     */
    public static function getEnchantsByTier(int $tier) {
        return self::$tiers[$tier];
    }

    /**
     * @param string $name
     * @return mixed
     */
    public static function fromString(string $name) {
        return self::$enchants[$name];
    }

    /**
     * @param int $id
     * @return CustomEnchant|null
     */
	public static function getEnchantment(int $id): ?CustomEnchant
    {
        return self::$ids[$id] ?? null;
    }

        /**
     * @return CustomEnchant|null
     */
    public static function getEnchantmentByName(string $name) : ?CustomEnchant {
        /** @var CustomEnchant $enchant*/
        foreach(self::$enchants as $enchant) {
            if(strtolower($enchant->getName()) === strtolower($name)) return $enchant;
        }
        return null;
    }


    /**
     * @param ItemStack $itemStack
     * @return ItemStack
     */
    public static function displayEnchants(ItemStack $itemStack): ItemStack {
        $item = TypeConverter::getInstance()->netItemStackToCore($itemStack);
        if($item->getId() === ItemIds::ENCHANTED_BOOK) return TypeConverter::getInstance()->coreItemStackToNet($item);
        if (count($item->getEnchantments()) > 0) {
            $additionalInformation = Loader::getInstance()->getConfig()->getNested("enchants.position") === "name" ? TextFormat::RESET . TextFormat::WHITE . $item->getName() : "";
            foreach ($item->getEnchantments() as $enchantmentInstance) {
                $enchantment = $enchantmentInstance->getType();
                if ($enchantment instanceof CustomEnchant) {
                    $additionalInformation .= "\n" . TextFormat::RESET . self::getColor($enchantment) . $enchantment->getName() . " " . (Loader::getInstance()->getConfig()->getNested("enchants.roman-numerals") ? self::roman($enchantmentInstance->getLevel()) : $enchantmentInstance->getLevel());
                }
            }
            if ($item->getNamedTag()->getTag(Item::TAG_DISPLAY)) $item->getNamedTag()->setTag("OriginalDisplayTag", $item->getNamedTag()->getTag(Item::TAG_DISPLAY)->safeClone());
            if (Loader::getInstance()->getConfig()->getNested("enchants.position") === "lore") {
                $lore = array_merge(explode("\n", $additionalInformation), $item->getLore());
                array_shift($lore);
                $item = $item->setLore($lore);
            } else {
                $item = $item->setCustomName($additionalInformation);
            }
        }
        return TypeConverter::getInstance()->coreItemStackToNet($item);
    }

    /**
     * @param ItemStack $itemStack
     * @return ItemStack
     */
    public static function filterDisplayedEnchants(ItemStack $itemStack): ItemStack {
        $item = TypeConverter::getInstance()->netItemStackToCore($itemStack);
        if($item->getId() === ItemIds::ENCHANTED_BOOK) return TypeConverter::getInstance()->coreItemStackToNet($item);
        $tag = $item->getNamedTag();
        if (count($item->getEnchantments()) > 0) $tag->removeTag(Item::TAG_DISPLAY);
        if ($tag->getTag("OriginalDisplayTag") instanceof CompoundTag) {
            $tag->setTag(Item::TAG_DISPLAY, $tag->getTag("OriginalDisplayTag"));
            $tag->removeTag("OriginalDisplayTag");
        }
        $item->setNamedTag($tag);
        return TypeConverter::getInstance()->coreItemStackToNet($item);
    }

    /**
     * @param CustomEnchant $enchant
     * @param Item $item
     * @return bool
     */
    public static function check(CustomEnchant $enchant, Item $item) : bool {
            switch($enchant->getEquipType()) {
                case CustomEnchant::HELMET:
                return in_array($item->getId(), [ItemIds::LEATHER_HELMET, ItemIds::CHAIN_HELMET, ItemIds::GOLD_HELMET, ItemIds::IRON_HELMET, ItemIds::DIAMOND_HELMET]);
            break;

                case CustomEnchant::CHESTPLATE:
                return in_array($item->getId(), [ItemIds::LEATHER_TUNIC, ItemIds::CHAIN_CHESTPLATE, ItemIds::GOLD_CHESTPLATE, ItemIds::IRON_CHESTPLATE, ItemIds::DIAMOND_CHESTPLATE]);
            break;

                case CustomEnchant::LEGGINGS:
                return in_array($item->getId(), [ItemIds::LEATHER_LEGGINGS, ItemIds::CHAIN_LEGGINGS, ItemIds::GOLD_LEGGINGS, ItemIds::IRON_LEGGINGS, ItemIds::DIAMOND_LEGGINGS]);
            break;

                case CustomEnchant::BOOTS:
                return in_array($item->getId(), [ItemIds::LEATHER_BOOTS, ItemIds::CHAINMAIL_BOOTS, ItemIds::GOLD_BOOTS, ItemIds::IRON_BOOTS, ItemIds::DIAMOND_BOOTS]);
            break;

                case CustomEnchant::TOOL:
                return $item instanceof Tool;
            break;

                case CustomEnchant::PICKAXE:
                return in_array($item->getId(), [ItemIds::WOODEN_PICKAXE, ItemIds::STONE_PICKAXE, ItemIds::GOLD_PICKAXE, ItemIds::IRON_PICKAXE, ItemIds::DIAMOND_PICKAXE]);
            break;

                case CustomEnchant::SWORD:
                return in_array($item->getId(), [ItemIds::WOODEN_SWORD, ItemIds::STONE_SWORD, ItemIds::GOLD_SWORD, ItemIds::IRON_SWORD, ItemIds::DIAMOND_SWORD]);
            break;

                case CustomEnchant::AXE:
                return in_array($item->getId(), [ItemIds::WOODEN_AXE, ItemIds::STONE_AXE, ItemIds::GOLDEN_AXE, ItemIds::IRON_AXE, ItemIds::DIAMOND_AXE]);
            break;

                case CustomEnchant::ARMOR:
                return $item instanceof Armor;
            break;

                case CustomEnchant::BOW_2:
                return $item instanceof Bow;
            break;

            default:
                return false;
            break;
        }
    }

    /**
     * @param EnchantmentInstance[] $enchants
     * @return EnchantmentInstance[]
     */
    public static function prioritize(array $enchants) : array {
        $new = [];

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::SIMPLE) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::UNIQUE) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::ELITE) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::ULTIMATE) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::LEGENDARY) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::SOUL) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::HEROIC) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        foreach($enchants as $enchant) {
            if($enchant->getType() instanceof CustomEnchant && $enchant->getType()->getRarity() === CustomEnchant::MASTERY) $new[] = new EnchantmentInstance($enchant->getType(), $enchant->getLevel());
        }

        return $new;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public static function isSoulTrapped(Player $player) : bool {
        if(in_array($player->getUniqueId()->toString(), SoulTrap::$soulTrapped)) {
            return true;
        } else {
            return false;
        }
    }
}