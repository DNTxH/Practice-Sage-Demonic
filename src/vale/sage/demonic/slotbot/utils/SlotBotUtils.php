<?php namespace vale\sage\demonic\slotbot\utils;

use pocketmine\item\Item;
use pocketmine\nbt\NoSuchTagException;

class SlotBotUtils {

    public function isRealClaimableChest(Item $item) : bool {
        try {
            $e = $item->getNamedTag()->getInt("slotBotChestMoney");
            return $e !== null;
        } catch (NoSuchTagException $e) {
            return false;
        }
    }

    public function getMoneyFromClaimableChest(Item $item) : int {
        return $item->getNamedTag()->getInt("slotBotChestMoney");
    }

}