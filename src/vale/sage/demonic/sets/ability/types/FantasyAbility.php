<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\items\DamageInfo;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;


/**
 * Class FantasyAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 22:30
 * @project Genesis
 */
class FantasyAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "fantasy");
    }

    public function react(Player $player, ...$args): void{}

    public function attack(EntityDamageEvent $event): bool{
        if (!parent::attack($event)) return false;
        if ($event instanceof EntityDamageByEntityEvent) {
            $attacker = $event->getDamager();
            $entity = $event->getEntity();
            if ($attacker instanceof Player && $entity instanceof Player) {
                if ($attacker->getPosition()->distance($entity->getPosition()) < 0.5) {
                    $event->setModifier(($event->getFinalDamage() * 0.5), DamageInfo::CUSTOM_MODIFIER);
                } else {
                    $distance = $attacker->getPosition()->distance($entity->getPosition()) / 100;
                    $event->setModifier(($event->getFinalDamage() * $distance * 15), DamageInfo::CUSTOM_MODIFIER);
                }
            }
        }
        return true;
    }
}