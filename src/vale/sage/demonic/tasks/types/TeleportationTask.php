<?php

namespace vale\sage\demonic\tasks\types;

use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;
use pocketmine\world\Position;
use pocketmine\utils\TextFormat as C;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\player\Player;
use pocketmine\entity\effect\VanillaEffects;

class TeleportationTask extends Task {

    /**
     * @var Position
     */
    private Position $origin;

    /**
     * @param Loader $loader
     * @param Player $player
     * @param Position $position
     * @param int $exp
     * @param int $time
     */
    public function __construct(private Loader $loader, private Player $player, private Position $position, private int $exp, private int $time){
        $this->origin = $player->getPosition();
        $player->getEffects()->add(new EffectInstance(VanillaEffects::NAUSEA(), ($time + 5) * 20));
        $player->sendMessage(C::YELLOW . C::BOLD . "(!) " . C::RESET . C::YELLOW . "You will be teleported in " . $time . "s... DON'T MOVE!" . C::EOL . C::GRAY . "Decrease this wait time by holding more vanilla XP.");
    }

    public function onRun(): void{
        if(!$this->player->isOnline()){
            $this->player->getXpManager()->addXp($this->exp);
            $this->player->getEffects()->remove(VanillaEffects::NAUSEA());
            $this->getHandler()->cancel();
            return;
        }
        if($this->origin->distance($this->player->getPosition()) >= 1){
            $this->player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "Pending teleportation request cancelled due to movement");
            $this->player->getXpManager()->addXp($this->exp);
            $this->player->getEffects()->remove(VanillaEffects::NAUSEA());
            $this->getHandler()->cancel();
            return;
        }
        if($this->time <= 0){
            $this->player->teleport($this->position);
            $this->player->getEffects()->remove(VanillaEffects::NAUSEA());
            $this->getHandler()->cancel();
        }
        $this->time--;
    }

}