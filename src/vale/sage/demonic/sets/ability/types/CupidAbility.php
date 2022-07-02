<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use pocketmine\player\Player;


/**
 * Class CupidAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 23:43
 * @project Genesis
 */
class CupidAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "cupid");
    }

    public function react(Player $player, ...$args): void{
        $player->teleport($args[0][0] ?? $player->getPosition());
    }
}