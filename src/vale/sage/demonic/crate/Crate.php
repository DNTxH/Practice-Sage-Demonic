<?php

namespace vale\sage\demonic\crate;

use vale\sage\demonic\Loader;
use pocketmine\item\Item;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\Position;

abstract class Crate
{
	const SIMPLE = C::BOLD . C::GRAY . "Simple " . C::WHITE . "Crate" . C::RESET;
	const UNIQUE = C::BOLD . C::GREEN . "Unique " . C::WHITE . "Crate" . C::RESET;
	const ELITE = C::BOLD . C::AQUA . "Elite " . C::WHITE . "Crate" . C::RESET;
	const ULTIMATE = C::BOLD . C::YELLOW . "Ultimate " . C::WHITE . "Crate" . C::RESET;
	const LEGENDARY = C::BOLD . C::GOLD . "Legendary " . C::WHITE . "Crate" . C::RESET;
	const MASTERY = C::BOLD . C::DARK_RED . "Mastery " . C::WHITE . "Crate" . C::RESET;

    /**
     * @param string $name
     * @param Position $position
     * @param Item $key
     * @param array $rewards
     */
	public function __construct(private string $name, private Position $position, private Item $key, private array $rewards){}

    /**
     * @param Player $player
     * @return void
     */
	abstract public function spawnTo(Player $player): void;

    /**
     * @param Player $player
     * @return void
     */
	abstract public function despawnTo(Player $player): void;

    /**
     * @return string
     */
	public function getName(): string{
        return $this->name;
    }

    /**
     * @return Position
     */
    public function getPosition(): Position{
        return $this->position;
    }

    /**
     * @return Item
     */
	public function getKey(): Item{
		return $this->key;
	}

    /**
     * @return array
     */
	public function getRewards(): array{
		return $this->rewards;
	}

    /**
     * @param int $loop
     * @return Reward
     */
	public function getReward(int $loop = 0): Reward{
        $chance = mt_rand(0, 100);
        $reward = $this->rewards[array_rand($this->rewards)];
        if($loop >= 10) return $reward;
        if($reward->getChance() <= $chance) return $this->getReward($loop + 1);
        return $reward;
    }

    /**
     * @return Loader
     */
	public function getLoader(): Loader{
		return Loader::getInstance();
	}
}