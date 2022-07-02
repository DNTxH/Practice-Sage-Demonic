<?php
namespace vale\sage\demonic\sets\ability;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\utils\Utils;
use vale\sage\demonic\Loader;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;


/**
 * Class BaseAbility
 * @package vale\sage\demonic\ability
 * @author Jibix
 * @date 06.01.2022 - 18:57
 * @project Genesis
 */
abstract class BaseAbility{

    protected array $cooldowns = [];

    public function __construct(
        private float $chance = 0,
        private int $cooldown = 0,
        private string $name = ""
    ){}

    public function getName(): string{
        return $this->name;
    }

    public function getChance(): float{
        return $this->chance;
    }

    public function getCooldown(): float{
        return $this->cooldown;
    }

    abstract public function react(Player $player, ...$args): void;

    public function attemptReact(Player $player, ...$args): bool{
        $name = $player->getName();
        $cooldown = $this->cooldowns[$name] ?? null;
        if ($cooldown === null) {
            $cooldown = Utils::getRandomFloat(0, 100);
            if ($cooldown <= $this->getChance()) {
                $this->cooldowns[$name] = $this->getName();
                $this->react($player, ...$args);
                $item = Utils::wearFullArmorSet($player);
                if ($item instanceof BaseArmorItem) $armorName = $item->getColoredName();
                else $armorName = ucwords($this->getName());
                //Server::getInstance()->broadcastMessage("§l§a{$player->getDisplayName()} has activated their§b {$armorName}§a ability!");
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($name): void{
                    unset($this->cooldowns[$name]);
                }), $this->getCoolDown() * 20);
                return true;
            }
        }
        return false;
    }

    public function attack(EntityDamageEvent $event): bool{
        if ($event instanceof EntityDamageByEntityEvent) {
            if ($event->getDamager() instanceof Player) {
                if (!$this->attemptReact($event->getDamager())) {
                    return false;
                }
            }
        }
        return true;
    }
}