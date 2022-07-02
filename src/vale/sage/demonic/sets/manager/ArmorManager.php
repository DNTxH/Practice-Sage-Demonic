<?php
namespace vale\sage\demonic\sets\manager;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\items\armor\CustomArmorItem;
use vale\sage\demonic\items\armor\type\CupidArmorItem;
use vale\sage\demonic\items\armor\type\DragonArmorItem;
use vale\sage\demonic\items\armor\type\FantasyArmorItem;
use vale\sage\demonic\items\armor\type\KothArmorItem;
use vale\sage\demonic\items\armor\type\PhantomArmorItem;
use vale\sage\demonic\items\armor\type\RangerArmorItem;
use vale\sage\demonic\items\armor\type\ReaperArmorItem;
use vale\sage\demonic\items\armor\type\SpookyArmorItem;
use vale\sage\demonic\items\armor\type\SupremeArmorItem;
use vale\sage\demonic\items\armor\type\ThorArmorItem;
use vale\sage\demonic\items\armor\type\TravelerArmorItem;
use vale\sage\demonic\items\armor\type\XmasArmorItem;
use vale\sage\demonic\items\armor\type\YetiArmorItem;
use vale\sage\demonic\items\armor\type\YijkiArmorItem;
use vale\sage\demonic\utils\Utils;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\serializer\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionProperty;


/**
 * Class ArmorManager
 * @package vale\sage\demonic\manager
 * @author Jibix
 * @date 06.01.2022 - 17:01
 * @project Genesis
 */
class ArmorManager{
    use SingletonTrait;

    private array $armors = [];

    public function init(): void{
        $this->register(new DragonArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new DragonArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new DragonArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new DragonArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new KothArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new KothArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new KothArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new KothArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new PhantomArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new PhantomArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new PhantomArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new PhantomArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new RangerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new RangerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new RangerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new RangerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new SupremeArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new SupremeArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new SupremeArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new SupremeArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new TravelerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new TravelerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new TravelerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new TravelerArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new YetiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new YetiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new YetiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new YetiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new YijkiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new YijkiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new YijkiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new YijkiArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new FantasyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new FantasyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new FantasyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new FantasyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new ReaperArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new ReaperArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new ReaperArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new ReaperArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new CupidArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new CupidArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new CupidArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new CupidArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new XmasArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new XmasArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new XmasArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new XmasArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new SpookyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new SpookyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new SpookyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new SpookyArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));

        $this->register(new ThorArmorItem(new ItemIdentifier(ItemIds::DIAMOND_HELMET, 0)));
        $this->register(new ThorArmorItem(new ItemIdentifier(ItemIds::DIAMOND_CHESTPLATE, 0)));
        $this->register(new ThorArmorItem(new ItemIdentifier(ItemIds::DIAMOND_LEGGINGS, 0)));
        $this->register(new ThorArmorItem(new ItemIdentifier(ItemIds::DIAMOND_BOOTS, 0)));
    }

    public function register(BaseArmorItem $item): void{
        $this->armors[$item->getArmorName()][$item->getArmorType()] = $item;
    }


    public function getArmor(Item|array $armor): ?BaseArmorItem{
        if ($armor instanceof Armor && $this->isCustomArmor($armor)) {
            return $this->armors[$armor->getNamedTag()->getString("customArmor", "dragon")][Utils::armorSlotToType($armor->getArmorSlot())] ?? null;
        } elseif (!is_array($armor)) {
            return null;
        }

        return $this->armors[$armor[0]][$armor[1] ?? ""] ?? null;
    }

    public function isCustomArmor(Item $item): bool{
        return $item->getNamedTag()->getTag("customArmor") !== null;
    }

    public function getArmors(): array{
        return $this->armors;
    }
}