<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\entity\ability\LightningBoltEntity;
use vale\sage\demonic\items\weapon\BaseWeaponItem;
use vale\sage\demonic\items\weapon\type\ThorWeaponItem;
use vale\sage\demonic\manager\WeaponManager;
use pocketmine\player\Player;


/**
 * Class ThorAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 07.01.2022 - 02:15
 * @project Genesis
 */
class ThorAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "thor");
    }

    public function react(Player $player, ...$args): void{
        $damage = 12;
        $time = 5;
        $item = $player->getInventory()->getItemInHand();
        $item = WeaponManager::getInstance()->getWeapon($item);
        if ($item instanceof ThorWeaponItem) {
            $damage *= 1.25;
            $time += 3;
        }

        $entity = new LightningBoltEntity($player->getLocation(), $damage, null, $time);
        $entity->setOwningEntity($player);
        $entity->spawnToAll();
    }
}