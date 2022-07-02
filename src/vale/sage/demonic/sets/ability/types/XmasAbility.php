<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\task\BlocksReplaceTask;
use vale\sage\demonic\Loader;
use pocketmine\block\Opaque;
use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\player\Player;


/**
 * Class XmasAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 23:56
 * @project Genesis
 */
class XmasAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "xmas");
    }

    public function react(Player $player, ...$args): void{
        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new BlocksReplaceTask($player), 20 * 10);
    }
}