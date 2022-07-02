<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityEffectAddEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\entity\ProjectileHitEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\inventory\CallbackInventoryListener;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\ArmorInventory;
use pocketmine\item\Item;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\enchants\event\BleedDamageEvent;
use vale\sage\demonic\enchants\event\EnchantmentActivationEvent;
use vale\sage\demonic\enchants\event\MetaphysicalEvent;
use vale\sage\demonic\enchants\event\PlayerDisarmorEvent;
use vale\sage\demonic\enchants\event\SoulTrapEvent;
use vale\sage\demonic\enchants\utils\CustomPrimedTnt;
use vale\sage\demonic\Loader;

class CustomEnchantListener implements Listener {

    /**
     * @param EntityDamageByEntityEvent $event
     * @priority HIGH
     */
    public function onEntityDamage(EntityDamageByEntityEvent $event) : void {
        if($event->isCancelled()) return;

        $damager = $event->getDamager();
        $entity = $event->getEntity();

        if($damager instanceof Player) {
            foreach($damager->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant) {
                    if($type->getEventType() === CustomEnchant::ENTITY_DAMAGE_BY_ENTITY && $type->getAction() === CustomEnchant::OFFENSIVE) {
                        $ev = new EnchantmentActivationEvent($damager, $entity);
                        $ev->call();

                        if($ev->isCancelled()) return;
                        $type->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }

            foreach($damager->getArmorInventory()->getContents() as $content) {
                foreach($content->getEnchantments() as $enchantment) {
                    $type = $enchantment->getType();
                    if($type instanceof CustomEnchant) {
                        if($type->getEventType() === CustomEnchant::ENTITY_DAMAGE_BY_ENTITY && $type->getAction() === CustomEnchant::OFFENSIVE) {
                            $ev = new EnchantmentActivationEvent($damager, $entity);
                            $ev->call();

                            if ($ev->isCancelled()) return;

                            $type->getCallable()($event, $enchantment->getLevel());
                        }
                    }
                }
            }
        }

        if($entity instanceof Player) {
            foreach($entity->getArmorInventory()->getContents() as $content) {
                foreach($content->getEnchantments() as $enchantment) {
                    $type = $enchantment->getType();
                    if($type instanceof CustomEnchant) {
                        if($type->getEventType() === CustomEnchant::ENTITY_DAMAGE_BY_ENTITY && $type->getAction() === CustomEnchant::DEFENSIVE) {
                            $ev = new EnchantmentActivationEvent($damager, $entity, true);
                            $ev->call();

                            if ($ev->isCancelled()) return;

                            $type->getCallable()($event, $enchantment->getLevel());
                        }
                    }
                }
            }

            foreach($entity->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant) {
                    if($type->getEventType() === CustomEnchant::ENTITY_DAMAGE_BY_ENTITY && $type->getAction() === CustomEnchant::DEFENSIVE) {
                        $ev = new EnchantmentActivationEvent($damager, $entity, true);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $type->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();

        foreach($player->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
            $type = $enchantment->getType();
            if($type instanceof CustomEnchant) {
                if($type->getEventType() === CustomEnchant::BREAK) {
                    $ev = new EnchantmentActivationEvent($player, null);
                    $ev->call();

                    if ($ev->isCancelled()) return;

                    $type->getCallable()($event, $enchantment->getLevel());
                }
            }
        }
    }

    /**
     * @param ProjectileHitEvent $event
     * @priority HIGH
     */
    public function onProjectileHit(ProjectileHitEvent $event) : void {
        $owner = $event->getEntity()->getOwningEntity();

        if($owner instanceof Player) {
            foreach($owner->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant) {
                    if($type->getEventType() === CustomEnchant::PROJECTILE) {
                        $ev = new EnchantmentActivationEvent($owner, null);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $type->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }
    }

    /**
     * @param ProjectileHitEntityEvent $event
     * @priority HIGH
     */
    public function onProjectileHitEntity(ProjectileHitEntityEvent $event) : void {
        $owner = $event->getEntity()->getOwningEntity();
        $hit = $event->getEntityHit();

        if($owner instanceof Player && $hit instanceof Player) {
            foreach($owner->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant) {
                    if($type->getEventType() === CustomEnchant::PROJECTILE_ENTITY) {
                        $ev = new EnchantmentActivationEvent($owner, $hit);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $type->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }
    }

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event) : void
    {
        $onSlot = function (Inventory $inventory, int $slot, Item $oldItem): void {
            if (!$inventory instanceof ArmorInventory) return;
            $holder = $inventory->getHolder();
            if ($holder instanceof Player) {
                foreach ($oldItem->getEnchantments() as $enchantment) {
                    $type = $enchantment->getType();
                    if ($type instanceof CustomEnchant) {
                        if ($type->getEffect() !== null) {
                            if ($holder->getEffects()->has($type->getEffect()) && $holder->getEffects()->get($type->getEffect())->getAmplifier() === $type->getAmplifier()) {
                                $holder->getEffects()->remove($type->getEffect());
                            }
                        } else {
                            if($type->getId() === CustomEnchantIds::OVERLOAD) {
                                $holder->setMaxHealth($holder->getMaxHealth() + 2 * $enchantment->getLevel() * (-1));
                                $holder->setHealth($holder->getHealth() * ($holder->getMaxHealth() / ($holder->getMaxHealth() - 2 * $enchantment->getLevel() * (-1))));
                            }
                            if($type->getId() === CustomEnchantIds::GODLYOVERLOAD) {
                                $holder->setMaxHealth($holder->getMaxHealth() + 2 * $enchantment->getLevel() * (-2));
                                $holder->setHealth($holder->getHealth() * ($holder->getMaxHealth() / ($holder->getMaxHealth() - 2 * $enchantment->getLevel() * (-2))));
                            }
                        }
                    }
                }

                $newItem = $inventory->getItem($slot);

                if ($newItem->getId() !== ItemIds::AIR) {
                    foreach ($newItem->getEnchantments() as $enchantment) {
                        $type = $enchantment->getType();
                        if ($type instanceof CustomEnchant) {
                            if ($type->getEffect() !== null) {
                                $holder->getEffects()->add(new EffectInstance($type->getEffect(), 2147483647, $type->getAmplifier()));
                            } else {
                                if($type->getId() === CustomEnchantIds::OVERLOAD) {
                                    $holder->setMaxHealth($holder->getMaxHealth() + 2 * $enchantment->getLevel() * (1));
                                }
                                if($type->getId() === CustomEnchantIds::GODLYOVERLOAD) {
                                    $holder->setMaxHealth($holder->getMaxHealth() + 2 * $enchantment->getLevel() * (2));
                                }
                            }
                        }
                    }
                }
            }

        };

        /**
         * @param Item[] $oldContents
         */
        $onContent = function (Inventory $inventory, array $oldContents) use ($onSlot): void {
            foreach ($oldContents as $slot => $oldItem) {
                if ($inventory instanceof ArmorInventory) $onSlot($inventory, $slot, $oldItem);
            }
        };

        $event->getPlayer()->getArmorInventory()->getListeners()->add(new CallbackInventoryListener($onSlot, $onContent));

        foreach ($event->getPlayer()->getArmorInventory()->getContents() as $c) {
            foreach ($c->getEnchantments() as $e) {
                if ($e->getType() instanceof CustomEnchant) {
                    if ($e->getType()->getEffect() !== null) {
                        $event->getPlayer()->getEffects()->add(new EffectInstance($e->getType()->getEffect(), 2147483647, $e->getType()->getAmplifier()));
                    } else {
                        if($e->getType()->getId() === CustomEnchantIds::OVERLOAD) {
                            $event->getPlayer()->setMaxHealth($event->getPlayer()->getMaxHealth() + 2 * $e->getLevel() * (1));
                        }
                        if($e->getType()->getId() === CustomEnchantIds::GODLYOVERLOAD) {
                            $event->getPlayer()->setMaxHealth($event->getPlayer()->getMaxHealth() + 2 * $e->getLevel() * (2));
                        }
                    }
                }
            }
        }
    }

    /**
     * @param ExplosionPrimeEvent $event
     * @return void
     */
    public function onPrimeExplosion(ExplosionPrimeEvent $event) : void {
        $entity = $event->getEntity();

        if($entity instanceof CustomPrimedTnt) $event->setBlockBreaking(false);
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onPureDamage(EntityDamageEvent $event) : void {
        $entity = $event->getEntity();

        if($entity->getLastDamageCause() instanceof EntityDamageByEntityEvent && !$entity instanceof Player) {
            $damager = $entity->getLastDamageCause()->getDamager();
            if(!$damager instanceof Player) return;
            if(!$damager->getInventory()->getItemInHand()->hasEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::KILLAURA))) return;

            if($entity->getHealth() - $event->getFinalDamage() <= 0) {
                foreach($damager->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                    if($enchantment->getType() instanceof CustomEnchant && $enchantment->getType()->getId() === CustomEnchantIds::KILLAURA) {
                        $ev = new EnchantmentActivationEvent($damager, null);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $enchantment->getType()->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }

        if(!$entity instanceof Player) return;

        foreach($entity->getArmorInventory()->getContents() as $content) {
            foreach($content->getEnchantments() as $e) {
                if($e->getType() instanceof CustomEnchant && $e->getType()->getEventType() === CustomEnchant::ENTITY_DAMAGE) {
                    $ev = new EnchantmentActivationEvent($entity, null);
                    $ev->call();

                    if ($ev->isCancelled()) return;

                    $e->getType()->getCallable()($event, $e->getLevel());
                }
            }
        }
    }

    /**
     * @param PlayerDeathEvent $event
     * @return void
     */
    public function onDeath(PlayerDeathEvent $event) : void {
        foreach ($event->getPlayer()->getArmorInventory()->getContents() as $c) {
            foreach ($c->getEnchantments() as $e) {
                if ($e->getType() instanceof CustomEnchant) {
                    if($e->getType()->getId() === CustomEnchantIds::AVENGINGANGEL) continue;
                    if ($e->getType()->getEventType() === CustomEnchant::PLAYER_DEATH) {
                        $ev = new EnchantmentActivationEvent($event->getPlayer(), null);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $e->getType()->getCallable()($event, $e->getLevel());
                    }
                }
            }
        }

        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            foreach($player->getArmorInventory()->getContents() as $content) {
                foreach($content->getEnchantments() as $enchantment) {
                    $type = $enchantment->getType();
                    if($type instanceof CustomEnchant) {
                        if($enchantment->getId() === CustomEnchantIds::AVENGINGANGEL && $player->getPosition()->distance($event->getPlayer()->getPosition()) <= $enchantment->getLevel() * 25) {
                            $ev = new EnchantmentActivationEvent($player, null);
                            $ev->call();

                            if ($ev->isCancelled()) return;

                            $type->getCallable()($player, $enchantment->getLevel());
                        }
                    }
                }
            }
        }
    }

    /**
     * I fucked up making this system so now we have checks for some ces that depend on levels for
     * effect amplifiers.
     *
     * @param EntityEffectAddEvent $event
     * @return void
     */
    public function onEffect(EntityEffectAddEvent $event) : void {
        $entity = $event->getEntity();

        if(!$entity instanceof Player) return;

        foreach($entity->getArmorInventory()->getContents() as $content) {
            foreach($content->getEnchantments() as $e) {
                $type = $e->getType();
                if($type instanceof CustomEnchant) {
                    if($type->getEventType() === CustomEnchant::EFFECT) $type->getCallable()($event, $e->getLevel());
                }
            }
        }

        $boots = $entity->getArmorInventory()->getBoots();

        foreach($boots->getEnchantments() as $enchantment) {
            if($enchantment->getType() instanceof CustomEnchant) {
                if($enchantment->getType()->getId() === CustomEnchantIds::SPRINGS) {
                    $entity->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 2147483647, $enchantment->getLevel()));
                }
                if($enchantment->getType()->getId() === CustomEnchantIds::GEARS) {
                    $entity->getEffects()->add(new EffectInstance(VanillaEffects::SPEED(), 2147483647, $enchantment->getLevel()));
                }
            }
        }

    }

    /**
     * @param EntityDeathEvent $event
     * @return void
     */
    public function onEntityDeath(EntityDeathEvent $event) : void {
        if($event->getEntity()->getLastDamageCause() instanceof EntityDamageByEntityEvent) {
            if($event->getEntity()->getLastDamageCause()->getDamager() instanceof Player) {
                foreach($event->getEntity()->getLastDamageCause()->getDamager()->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                    if($enchantment->getType() instanceof CustomEnchant && $enchantment->getType()->getEventType() === CustomEnchant::ENTITY_DEATH) {
                        $enchantment->getType()->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }
    }

    /**
     * @param PlayerDisarmorEvent $event
     * @return void
     */
    public function onDisarmor(PlayerDisarmorEvent $event) : void {
        foreach ($event->getPlayer()->getArmorInventory()->getContents() as $content) {
            foreach ($content->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant && $type->getEventType() === CustomEnchant::DISARMOR) {
                    $ev = new EnchantmentActivationEvent($event->getPlayer(), null);
                    $ev->call();

                    if ($ev->isCancelled()) return;

                    $type->getCallable()($event, $enchantment->getLevel());
                }
            }
        }
    }

    /**
     * @param BleedDamageEvent $event
     * @return void
     */
    public function onBleed(BleedDamageEvent $event) : void {
        foreach($event->getPlayer()->getServer()->getOnlinePlayers() as $player) {
            foreach($player->getArmorInventory()->getContents() as $content) {
                foreach ($content->getEnchantments() as $enchantment) {
                    $type = $enchantment->getType();
                    if($type instanceof CustomEnchant && $type->getEventType() === CustomEnchant::BLEED) {
                        $ev = new EnchantmentActivationEvent($event->getPlayer(), null);
                        $ev->call();

                        if ($ev->isCancelled()) return;

                        $type->getCallable()($event, $enchantment->getLevel(), $player);
                    }
                }
            }
        }
    }

    /**
     * @param SoulTrapEvent $event
     * @return void
     */
    public function onSoulTrap(SoulTrapEvent $event) : void {
        foreach ($event->getPlayer()->getArmorInventory()->getContents() as $content) {
            foreach ($content->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant && $type->getEventType() === CustomEnchant::SOULTRAP) {
                    $ev = new EnchantmentActivationEvent($event->getPlayer(), null);
                    $ev->call();

                    if ($ev->isCancelled()) return;

                    $type->getCallable()($event, $enchantment->getLevel());
                }
            }
        }
    }

    /**
     * @param MetaphysicalEvent $event
     * @return void
     */
    public function onMetaphysical(MetaphysicalEvent $event) : void {
        foreach ($event->getPlayer()->getArmorInventory()->getContents() as $content) {
            foreach ($content->getEnchantments() as $enchantment) {
                $type = $enchantment->getType();
                if($type instanceof CustomEnchant && $type->getEventType() === CustomEnchant::METAPHYSICAL) {
                    $ev = new EnchantmentActivationEvent($event->getPlayer(), null);
                    $ev->call();

                    if ($ev->isCancelled()) return;

                    $type->getCallable()($event, $enchantment->getLevel());
                }
            }
        }
    }

    /**
     * @param EnchantmentActivationEvent $event
     * @return void
     * All this for the fucking silence CE.
     */
    public function onEnchantmentProc(EnchantmentActivationEvent $event) : void {
        if(!$event->shouldCheck()) return;

        if($event->getVictim() instanceof Player) {
            if($event->getPlayer()->getInventory()->getItemInHand()->hasEnchantment(EnchantmentsManager::getEnchantment(CustomEnchantIds::SILENCE))) {
                foreach($event->getPlayer()->getInventory()->getItemInHand()->getEnchantments() as $enchantment) {
                    if($enchantment->getType() instanceof CustomEnchant && $enchantment->getType()->getEventType() === CustomEnchant::SILENCE) {
                        $enchantment->getType()->getCallable()($event, $enchantment->getLevel());
                    }
                }
            }
        }
    }
}