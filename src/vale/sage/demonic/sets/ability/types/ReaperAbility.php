<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\utils\Utils;
use vale\sage\demonic\Loader;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;


/**
 * Class ReaperAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 23:10
 * @project Genesis
 */
class ReaperAbility extends BaseAbility{

    private array $abilityCooldown = [];

    public function __construct(){
        parent::__construct(25, 60 * 3, "reaper");
    }

    public function attack(EntityDamageEvent $event): bool{
        if (!parent::attack($event)) return false;
        if ($event instanceof EntityDamageByEntityEvent) {
            if (!$event->getDamager() instanceof Player) return false;
            $name = $event->getDamager()->getName();
            $cooldown = $this->abilityCooldown[$name] ?? null;
            if ($cooldown === null) {
                $this->abilityCooldown[$name] = $this->getName();
                $event->setBaseDamage(mt_rand(5, 9) * 2);
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($name): void{
                    unset($this->abilityCooldown[$name]);
                }), 10 * 20);
                return true;
            }
        }
        return true;
    }

    public function react(Player $player, ...$args): void{}
}