<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use vale\sage\demonic\staff\StaffManager;
use vale\sage\demonic\Trojan\Task\AntiGlitchTask;
use vale\sage\demonic\Loader;

class AntiGlitchEvent implements Listener
{

    /** @noinspection PhpUnused */
    public function onInteract(PlayerInteractEvent $event)
    {
        $block = $event->getBlock();
        $delay = $event->getPlayer()->getNetworkSession()->getPing() / 2;
        if ($delay < 100) $distance = 1; else if ($delay < 200) $distance = 1.5; else $distance = 2;
        if ($block->getId() === BlockLegacyIds::FENCE_GATE && $event->getPlayer()->getPosition()->distance($block->getPosition()) < $distance && $event->isCancelled()) {
            $player = $event->getPlayer();
            if ($player instanceof Player) {
                switch ($player->getHorizontalFacing()) {
                    case 2:
                        $task = new AntiGlitchTask($player, 2);
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask($task, $delay);
                        break;

                    case 1:
                        $task = new AntiGlitchTask($player, 1);
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask($task, $delay);
                        break;

                    case 3:
                        $task = new AntiGlitchTask($player, 3);
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask($task, $delay);
                        break;

                    case 0:
                        $task = new AntiGlitchTask($player, 0);
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask($task, $delay);
                        break;
                }
            }
        }
    }

    /** @noinspection PhpUnused */
    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if($event->getCause() != EntityDamageEvent::CAUSE_PROJECTILE){
            if($event instanceof EntityDamageByEntityEvent and $entity instanceof Player){
                $damager = $event->getDamager();
                if($damager instanceof Player){
                    $distance = $damager->getPosition()->distance($entity->getPosition());
                    $max = 6;
                    if($distance >= $max){
                        $event->cancel();
                        $staffManager = new StaffManager();
                        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $staff){
                            if($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)){
                                $staff->sendMessage("§r§f§l<§r§4Trojan§f§l> §r§7The player " . $damager->getName() . " could be reaching (DISTANCE >= $distance). " . " §r§c§lPing §r§7{$entity->getNetworkSession()->getPing()} §r§7MS");
                            }
                        }
                        TrojanAPI::addFlag($damager->getName(), "Glitch");
                    }
                }
            }
        }
    }
    /** @noinspection PhpUnused */
    public function onMoveCheck(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        if ($player instanceof Player) {
            if (!Loader::getInstance()->getServer()->isOp($player->getName())) {
                $level = $player->getWorld();
                $block1 = $level->getBlock(new Vector3($event->getTo()->getX(), $event->getTo()->getY(), $event->getTo()->getZ()));
                $block2 = $level->getBlock(new Vector3($event->getTo()->getX(), $event->getTo()->getY() + 1, $event->getTo()->getZ()));
                if ($block1->getId() == BlockLegacyIds::TRAPDOOR) return;
                if ($block2->getId() == BlockLegacyIds::TRAPDOOR) return;
                if ($block1->getId() == BlockLegacyIds::OAK_FENCE_GATE) return;
                if ($block2->getId() == BlockLegacyIds::OAK_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::WOODEN_TRAPDOOR) return;
                if ($block2->getId() == BlockLegacyIds::WOODEN_TRAPDOOR) return;
                if ($block1->getId() == BlockLegacyIds::BIRCH_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::ACACIA_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::JUNGLE_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::SPRUCE_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::DARK_OAK_FENCE_GATE) return;
                if ($block1->getId() == BlockLegacyIds::PORTAL || $block2->getId() == BlockLegacyIds::PORTAL) return;
                if ($block2->isSolid() || $block1->getId() == BlockLegacyIds::SAND || $block1->getId() == BlockLegacyIds::GRAVEL || $block2->getId() == BlockLegacyIds::SAND || $block2->getId() == BlockLegacyIds::GRAVEL || $block2->getId() == BlockLegacyIds::COBBLESTONE || $block2->getId() == BlockLegacyIds::PLANKS) {
                    #$player->disableMovement(time() + 5);
                    $player->sendTip("§r§c§lNO CLIP ALERT \n  §r§7((We have detected unusual movement so we have teleported you back))");
                    TrojanAPI::addFlag($player->getName(), "Glitch");
                    #$player->knockBack($player, 0, $player->getX() - $block1->getX(), $playYer->getZ() - $block1->getZ(), 1);
                    $direction = $player->getDirectionVector();
                    $player->knockBack(0, $direction->getX() - $direction->getZ(),0.5);
                    foreach (Loader::getInstance()->getServer()->getOnlinePlayers() as $staff) {
                        if ($staff instanceof Player) {
                            $staffManager = new StaffManager();
                            if($staffManager->isInStaffMode($staff) || TrojanAPI::isAlert($staff)) {
                                $staff->sendTip("§r§f§l<§r§4Trojan§f§l> §r§7The player " . $player->getName() . " could be phasing / glitching. " . " §r§c§lPing §r§7{$player->getNetworkSession()->getPing()} §r§7MS");
                            }
                        }
                    }
                }
            }
        }
    }
}