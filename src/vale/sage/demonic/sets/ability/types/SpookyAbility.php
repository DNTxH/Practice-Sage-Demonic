<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\Loader;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;


/**
 * Class SpookyAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 07.01.2022 - 01:15
 * @project Genesis
 */
class SpookyAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "spooky");
    }

    public function react(Player $player, ...$args): void{
        $attacker = $args[0][0] ?? null;
        if ($attacker instanceof Player) {
            $helmet = $attacker->getArmorInventory()->getHelmet();
            $attacker->getArmorInventory()->setHelmet(VanillaBlocks::CARVED_PUMPKIN()->asItem());
            $attacker->getEffects()->add(new EffectInstance(VanillaEffects::BLINDNESS(), 20 * 6, 10));
            $attacker->getEffects()->add(new EffectInstance(VanillaEffects::MINING_FATIGUE(), 20 * 6, 5));

            Loader::getInstance()->getScheduler()->scheduleDelayedRepeatingTask(new ClosureTask(function () use ($attacker, $helmet): void{
                $attacker->getArmorInventory()->setHelmet($helmet);
                throw new CancelTaskException();
            }), 20 * 6, 20 * 6);
        }
    }
}