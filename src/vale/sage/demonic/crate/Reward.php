<?php

namespace vale\sage\demonic\crate;

use pocketmine\item\Item;

class Reward
{
    /**
     * @param Item $item
     * @param $callable
     * @param int $chance
     */
	public function __construct(private Item $item, private $callable, private int $chance){}

    /**
     * @return Item
     */
	public function getItem(): Item{
        return $this->item;
    }

    /**
     * @return callable
     */
    public function getCallback() : callable {
        return $this->callable;
    }

    /**
     * @return int
     */
    public function getChance(): int{
        return $this->chance;
    }
}