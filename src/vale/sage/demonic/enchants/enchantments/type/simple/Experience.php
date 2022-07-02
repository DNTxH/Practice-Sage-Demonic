<?php
namespace vale\sage\demonic\enchants\enchantments\type\simple;

use vale\sage\demonic\enchants\CustomEnchant;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\enchantment\ItemFlags;
use vale\sage\demonic\enchants\CustomEnchantIds;


/**
 * Class Experience
 * @package vale\sage\demonic\enchants\enchantments\simple
 * @author Jibix
 * @date 16.01.2022 - 21:48
 * @project Genesis-Workspace
 */
class Experience extends CustomEnchant{

    public function __construct(){
        parent::__construct(
            "Experience",
            CustomEnchantIds::EXPERIENE,
            "Gives more exp when mining blocks.",
            5,
            ItemFlags::TOOL,
            self::SIMPLE,
            self::MINING,
            self::BREAK,
            self::TOOL
        );
        $this->callable = function (BlockBreakEvent $event, int $level): void{
            $event->setXpDropAmount($event->getXpDropAmount() / 100 * (4 * $level));
        };
    }
}