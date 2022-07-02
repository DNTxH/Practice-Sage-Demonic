<?php

namespace vale\sage\demonic\crate;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;

class FloatingTextParticle extends \pocketmine\world\particle\FloatingTextParticle {

    /** @var World */
	private $world;

    /**
     * @param Position $pos
     * @param string $identifier
     * @param string $message
     */
    public function __construct(private Position $pos, private string $identifier, private string $message){
        parent::__construct("", "");
        $this->world = $pos->getWorld();
        $this->update();
    }

    /**
     * @return string
     */
    public function getMessage(): string{
        return $this->message;
    }

    /**
     * @return World
     */
    public function getWorld(): World{
        return $this->world;
    }

    /**
     * @param string|null $message
     * @return void
     */
    public function update(?string $message = null): void{
        $this->message = $message ?? $this->message;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string{
        return $this->identifier;
    }

    public function sendChangesToAll(): void{
        foreach(Server::getInstance()->getOnlinePlayers() as $player) {
            $this->sendChangesTo($player);
        }
    }

    /**
     * @param Position $position
     * @return void
     */
    public function move(Position $position): void{
        $this->pos = new Position($position->getX(), $position->getY(), $position->getZ(), $this->world);
        $this->sendChangesToAll();
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendChangesTo(Player $player): void{
        $this->setTitle($this->message);
        $world = $player->getPosition()->getWorld();
        if($this->world->getDisplayName() !== $world->getDisplayName()) return;
        $this->world->addParticle($this->pos, $this, [$player]);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function spawn(Player $player): void{
        $this->setInvisible(false);
        $world = $player->getPosition()->getWorld();
        $this->world->addParticle($this->pos, $this, [$player]);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function despawn(Player $player): void{
        $this->setInvisible(true);
        $world = $player->getPosition()->getWorld();
        $this->world->addParticle($this->pos, $this, [$player]);
    }
}