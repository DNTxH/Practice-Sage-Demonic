<?php

namespace vale\sage\demonic\enchants\utils;

use vale\sage\demonic\Loader;
use pocketmine\scheduler\Task;
use pocketmine\entity\Entity;
use pocketmine\player\Player;

class IndicatorRemoveTask extends Task
{
	/* @var yourmom */
	private Loader $plugin;
    private int $eid;
	private int $seconds;
	private string $message;
	private string $color;
	private Entity $entity;
	private Player $player;
	private int $start = 0;
    
    public function __construct(Loader $plugin, int $eid, int $seconds, string $message, string $color, Entity $entity, Player $player) {
		$this->plugin = $plugin;
        $this->eid = $eid;
		$this->seconds = $seconds;
		$this->message = $message;
		$this->color = $color;
		$this->entity = $entity;
		$this->player = $player;
    }
    public function onRun() : void
    {
		if($this->start === $this->seconds) {
			IndicatorManager::removeTag($this->eid);
            $this->getHandler()->cancel();
			return;
        }
        $this->seconds--;
        IndicatorManager::removeTag($this->eid);
		IndicatorManager::addTag($this->player, $this->entity, $this->message, $this->seconds, $this->color);
		$this->getHandler()->cancel();
		return;
    }
}