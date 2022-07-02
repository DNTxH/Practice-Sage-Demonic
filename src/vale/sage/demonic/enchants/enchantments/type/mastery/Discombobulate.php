<?php
namespace vale\sage\demonic\enchants\enchantments\type\mastery;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\CustomEnchantIds;
use vale\sage\demonic\Loader;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\Server;
use pocketmine\utils\TextFormat as C;


/**
 * Class Discombobulate
 * @package vale\sage\demonic\enchants\enchantments\type
 * @author Jibix
 * @date 16.01.2022 - 21:13
 * @project Genesis-Workspace
 */
class Discombobulate extends CustomEnchant{

    /** @var int */
    private int $maxLevel = 1;

    public function __construct(){
        parent::__construct(
            "Discombobulate",
            CustomEnchantIds::DISCOMBOBULATE,
            "Spoofs your distance and hides your name on all enemy and neutral /near lookups",
            1,
            ItemFlags::HEAD,
            self::MASTERY,
            self::DEFENSIVE,
            self::TODO,
            self::HELMET
        );

        $this->callable = function () : void {

        };
    }
}