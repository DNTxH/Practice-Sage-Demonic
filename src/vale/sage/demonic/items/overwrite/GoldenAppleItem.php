<?php
namespace vale\sage\demonic\items\overwrite;
use vale\sage\demonic\Loader;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Living;
use pocketmine\item\GoldenApple;
use pocketmine\player\Player;


/**
 * Class GoldenAppleItem
 * @package vale\sage\demonic\items\overwrite
 * @author Jibix
 * @date 07.01.2022 - 16:58
 * @project Genesis
 */
class GoldenAppleItem extends GoldenApple{

    /**
     * Function onConsume
     * @param Living $consumer
     */
    public function onConsume(Living $consumer): void{
        parent::onConsume($consumer);
        if (!$consumer instanceof Player) return;
        if ($consumer->isCreative() || $consumer->isSpectator()) return;
        $inArray = Loader::getInstance()->cooldowns[$consumer->getName()] ?? null;
        if (!empty($inArray) && $inArray !== 1 && time() >= $inArray +10) {
            unset(Loader::getInstance()->cooldowns[$consumer->getName()]);
        }
        if (empty(Loader::getInstance()->cooldowns[$consumer->getName()])) {
            Loader::getInstance()->cooldowns[$consumer->getName()] = 1;
        } elseif (Loader::getInstance()->cooldowns[$consumer->getName()] == 1) {
            Loader::getInstance()->cooldowns[$consumer->getName()] = time();
            $consumer->sendMessage("§l§e(!)§r§e You are about to contract Golden Apple sickness!");
            $consumer->sendMessage("§7Don't eat anymore golden apples for 10s!");
        } else {
            $consumer->sendMessage("§cYou have Golden Apple sickness!");
            $consumer->sendMessage("§7Stop eating golden apples, before it's to late!");
            $consumer->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), 3 * 20, 4));
            $consumer->getEffects()->add(new EffectInstance(VanillaEffects::POISON(), 7 * 20, 3));
            Loader::getInstance()->cooldowns[$consumer->getName()] = time();
        }
    }
}