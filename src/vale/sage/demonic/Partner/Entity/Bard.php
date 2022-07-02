<?php

namespace vale\sage\demonic\Partner\Entity;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Zombie;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\world\Position;
use vale\sage\demonic\Loader;

class Bard extends Zombie
{
    private $owner = null;
    private int $count_down = 50;//50 seconds
    private int $time = 0;
    private Position $pos;

    public function getName(): string
    {
        return "Bard";
    }

    public function spawnToAll(): void
    {
        parent::spawnToAll();
        $this->setMaxHealth(100);
        $this->setNameTagAlwaysVisible(true);
        $this->setCanSaveWithChunk(true);
        $this->setNameTag("§6§lPortable Bambe");
        $this->getArmorInventory()->setHelmet(ItemFactory::getInstance()->get(ItemIds::GOLD_HELMET, 0, 1));
        $this->getArmorInventory()->setChestplate(ItemFactory::getInstance()->get(ItemIds::GOLD_CHESTPLATE, 0, 1));
        $this->getArmorInventory()->setLeggings(ItemFactory::getInstance()->get(ItemIds::GOLD_LEGGINGS, 0, 1));
        $this->getArmorInventory()->setBoots(ItemFactory::getInstance()->get(ItemIds::GOLD_BOOTS, 0, 1));
    }

    public function onUpdate(int $currentTick): bool
    {
        if($this->time === 0 || time() - $this->time >= 1) {
            $this->time = time();
            $owner = Loader::getInstance()->getServer()->getPlayerExact($this->owner);
            foreach ($this->getWorld()->getNearbyEntities($this->getBoundingBox()->expandedCopy(10, 10, 10)) as $p) {
                if ($p instanceof Player) {
                    if ($p->getName() == $this->owner) {
                        if(\Partner\Item\Bard::isAllow($p)) {
                            if ($this->count_down <= (50) && $this->count_down >= (35)) {
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::RESISTANCE(), 20 * 2, 1));
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 20 * 2, 1));
                            }
                            if ($this->count_down <= (35) && $this->count_down >= (20)) {
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 20 * 2, 1));
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 20 * 2, 2));
                            }
                            if ($this->count_down <= (20) && $this->count_down >= (10)) {
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::INVISIBILITY(), 20 * 2, 0));
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 20 * 2, 1));
                            }
                            if ($this->count_down <= (10) && $this->count_down >= (5)) {
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::JUMP_BOOST(), 20 * 2, 7));
                            }
                            if ($this->count_down <= (5) && $this->count_down >= (0)) {
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::STRENGTH(), 20 * 2, 1));
                                $owner->getEffects()->add(new EffectInstance(VanillaEffects::REGENERATION(), 20 * 2, 1));
                            }
                        }
                    }
                }
            }
            if ($this->count_down > 0) {
                $this->count_down--;
            }
            if ($this->count_down <= 0) {
                $this->close();
            }
        }

        $this->teleport($this->pos);
        return parent::onUpdate($currentTick);
    }

    public function setOwner(Player $player)
    {
        $this->owner = $player->getName();
    }

    /**
     * @param Position $pos
     */
    public function setPos(Position $pos): void
    {
        $this->pos = $pos;
    }

    public function getOwner()
    {
        return $this->owner;
    }

}