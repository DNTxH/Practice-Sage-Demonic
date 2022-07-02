<?php

declare(strict_types = 1);

namespace vale\sage\demonic\enchants\enchantments\type\simple;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;
use pocketmine\item\Item;
use pocketmine\crafting\FurnaceType;

class AutoSmelt extends CustomEnchant {

    public function __construct() {
        parent::__construct(
            "Auto Smelt",
            CustomEnchantIds::AUTOSMELT,
            "Ores are automatically smelted when mined.",
            1,
            ItemFlags::PICKAXE,
            self::SIMPLE,
            self::MINING,
            self::BREAK,
            self::PICKAXE
        );

        $this->callable = function (BlockBreakEvent $event, int $level) : void {
            $event->setDrops(array_map(function (Item $item) {
                $recipe = Loader::getInstance()->getServer()->getCraftingManager()->getFurnaceRecipeManager(FurnaceType::FURNACE())->match($item);
                if ($recipe !== null) $item = $recipe->getResult();
                return $item;
            }, $event->getDrops()));
        };
    }

}