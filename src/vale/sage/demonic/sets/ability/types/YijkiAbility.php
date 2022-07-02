<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\entity\ability\LightningBoltEntity;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\manager\WeaponManager;
use pocketmine\player\Player;


/**
 * Class YijkiAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 19:35
 * @project Genesis
 */
class YijkiAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "yijki");
    }

    public function react(Player $player, ...$args): void{
        $damage = 12;
        $item = $player->getInventory()->getItemInHand();
        $item = WeaponManager::getInstance()->getWeapon($item);
        if ($item instanceof BaseWeaponItem) {
            if ($item->getWeaponType() === $this->getName()) $damage *= 1.25;
        }

        $entity = new LightningBoltEntity($player->getLocation(), $damage);
        $entity->setOwningEntity($player);
        $entity->spawnToAll();
    }
}