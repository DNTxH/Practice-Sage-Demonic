<?php

declare(strict_types = 1);

namespace vale\sage\demonic\levels;

use vale\sage\demonic\GenesisPlayer;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class LevelsListener implements Listener {

    /**
     * @param EntityDamageByEntityEvent $event
     * @priority HIGHEST
     */
    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $damager = $event->getDamager();
        $victim = $event->getEntity();

        if($damager instanceof GenesisPlayer && $victim instanceof GenesisPlayer) {
            if(mt_rand(1, 100) <= $victim->getDodgeTalentLevel()) {
                $event->setBaseDamage(0.0);
                $damager->sendMessage(TextFormat::RED . "**Opponent Evaded Your Attack**");
                $victim->sendMessage(TextFormat::LIGHT_PURPLE . "**You Evaded Your Opponents Attack**");
                return;
            }

            $multi = ($damager->getPvpOutgoingTalentLevel() / 100) - ($victim->getPvpIncomingTalentLevel() / 100);
            $final = $event->getBaseDamage() * (1 + $multi);
            $event->setBaseDamage($final);
        } else {
            if($damager instanceof GenesisPlayer && !$victim instanceof Player) {
                $multi = 1 + ($damager->getPveTalentLevel() / 100);
                $final = $event->getBaseDamage() * $multi;
                $event->setBaseDamage($final);
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event) : void {
        $player = $event->getPlayer();
        $cause = $player->getLastDamageCause();

        if(!$player instanceof GenesisPlayer) return;

        if($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if(!$damager instanceof GenesisPlayer) return;
            $damager->increaseLevelExperience(5);
        }
    }

    /**
     * @param EntityDeathEvent $event
     * @priority HIGHEST
     */
    public function onEntityDeath(EntityDeathEvent $event) : void {
        $entity = $event->getEntity();
        $cause = $entity->getLastDamageCause();

        if($entity instanceof GenesisPlayer) return;

        if($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if(!$damager instanceof GenesisPlayer) return;
            $damager->increaseLevelExperience();
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGH
     */
    public function onBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        $ores = [ItemIds::COAL_ORE, ItemIds::IRON_ORE, ItemIds::GOLD_ORE, ItemIds::LAPIS_ORE, ItemIds::REDSTONE_ORE, ItemIds::GLOWING_REDSTONE_ORE, ItemIds::LIT_REDSTONE_ORE, ItemIds::DIAMOND_ORE, ItemIds::EMERALD_ORE];

        if(!$player instanceof GenesisPlayer) return;

        if(in_array($event->getBlock()->getId(), $ores)) $player->increaseLevelExperience(2);

        if(mt_rand(1, 100) <= $player->getMinersFortuneTalentLevel()) {
            if(in_array($event->getBlock()->getId(), $ores)) {
                $player->sendPopup(TextFormat::GOLD . TextFormat::GOLD . "**Miners Fortune Talent Activated**");
                switch($event->getBlock()->getId()) {
                    case ItemIds::COAL_ORE:
                        $event->setDrops([VanillaBlocks::IRON_ORE()->asItem()]);
                        break;

                    case ItemIds::IRON_ORE:
                        $event->setDrops([VanillaBlocks::GOLD_ORE()->asItem()]);
                        break;

                    case ItemIds::GOLD_ORE:
                        $event->setDrops([VanillaItems::LAPIS_LAZULI()->setCount(2)]);
                        break;

                    case ItemIds::LAPIS_ORE:
                        $event->setDrops([VanillaItems::REDSTONE_DUST()->setCount(2)]);
                        break;

                    case ItemIds::REDSTONE_ORE:
                    case ItemIds::LIT_REDSTONE_ORE:
                    case ItemIds::GLOWING_REDSTONE_ORE:
                        $event->setDrops([VanillaItems::DIAMOND()->setCount(1)]);
                        break;

                    case ItemIds::DIAMOND_ORE:
                        $event->setDrops([VanillaItems::EMERALD()->setCount(1)]);
                        break;
                }
            }
        }

        if(mt_rand(0, 100) <= $player->getLuckyTalentLevel()) {
            if(in_array($event->getBlock()->getId(), $ores)) {
                $player->sendPopup(TextFormat::GOLD . TextFormat::GOLD . "**Lucky Talent Activated**");

                $newDrops = [];

                foreach ($event->getDrops() as $drop) {
                    $amount = mt_rand(1, 3);
                    $newDrops[] = $drop->setCount($drop->getCount() + $amount);
                }

                $event->setDrops($newDrops);
            }
        }

        $event->setXpDropAmount((int)round($event->getXpDropAmount() * (1 + ($player->getXpTalentLevel() / 100))));
    }

}