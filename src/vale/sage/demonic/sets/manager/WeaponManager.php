<?php
namespace vale\sage\demonic\sets\manager;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\weapon\type\CupidWeaponItem;
use vale\sage\demonic\items\weapon\type\DragonWeaponItem;
use vale\sage\demonic\items\weapon\type\KothAxeWeaponItem;
use vale\sage\demonic\items\weapon\type\KothSwordWeaponItem;
use vale\sage\demonic\items\weapon\type\PhantomWeaponItem;
use vale\sage\demonic\items\weapon\type\RangerWeaponItem;
use vale\sage\demonic\items\weapon\type\ReaperWeaponItem;
use vale\sage\demonic\items\weapon\type\SupremeWeaponItem;
use vale\sage\demonic\items\weapon\type\ThorWeaponItem;
use vale\sage\demonic\items\weapon\type\TravelerWeaponItem;
use vale\sage\demonic\items\weapon\type\YetiWeaponItem;
use vale\sage\demonic\items\weapon\type\YijkiWeaponItem;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\utils\SingletonTrait;


/**
 * Class WeaponManager
 * @package vale\sage\demonic\manager
 * @author Jibix
 * @date 06.01.2022 - 17:05
 * @project Genesis
 */
class WeaponManager{
    use SingletonTrait;

    private array $weapons = [];

    public function init(): void{
        $this->register(new KothAxeWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_AXE, 0)));
        $this->register(new KothSwordWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0)));
        $this->register(new PhantomWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0)));
        $this->register(new RangerWeaponItem(new ItemIdentifier(ItemIds::BOW, 0)));
        $this->register(new SupremeWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0)));
        $this->register(new TravelerWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_AXE, 0)));
        $this->register(new YetiWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_AXE, 0)));
        $this->register(new YijkiWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_AXE, 0)));
        $this->register(new ReaperWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0)));
        $this->register(new DragonWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_SWORD, 0)));
        $this->register(new ThorWeaponItem(new ItemIdentifier(ItemIds::DIAMOND_AXE, 0)));
        $this->register(new CupidWeaponItem(new ItemIdentifier(ItemIds::BOW, 0)));
    }

    public function register(BaseWeaponItem $item): void{
        $this->weapons[$item->getWeaponType()] = $item;
    }

    public function getWeapon(Item|string $weapon): ?BaseWeaponItem{
        if ($weapon instanceof Item && $this->isCustomWeapon($weapon)) {
            return $this->weapons[$weapon->getNamedTag()->getString("customWeapon", "koth")] ?? null;
        } elseif (!is_string($weapon)) {
            return null;
        }

        return $this->weapons[$weapon] ?? null;
    }

    public function isCustomWeapon(Item $item): bool{
        return $item->getNamedTag()->getTag("customWeapon") !== null;
    }
}