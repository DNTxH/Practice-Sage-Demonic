<?php namespace vale\sage\demonic\slotbot\sessions;

use vale\sage\demonic\Loader;
use vale\sage\demonic\slotbot\tasks\RollTask;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class SlotBotSession {

    public bool $running = false;

    public int $runningTime = 0;

    /**
     * @var array<Item>
     *
     * make sure this stops at 5
     * choose from 2
     */
    public array $rollingItems = [0 => [], 1 => [], 2 => [], 3 => [], 4 => []];

    public function generateRollingItem() : Item {
        $item = ItemFactory::getInstance()->get(ItemIds::CHEST);
        $item->setCount(mt_rand(1, 5));
        $type = mt_rand(1, 3);
        if ($type == 1) {
            $item->setCustomName("§r§l§bUncommon Money Pouch §8| §r§7(Right-Click)");
            $item->setLore(["§r§7Right-Click to open this money pouch\n§r§7and claim a random amount of money!!\n\n§r§bType: Uncommon\nAmount: $25,000 - $75,000"]);
            $item->getNamedTag()->setInt("slotBotChestMoney", mt_rand(25000, 75000));
            return $item;
        } else if ($type == 2) {
            $item->setCustomName("§r§l§aCommon Money Pouch §8| §r§7(Right-Click)");
            $item->setLore(["§r§7Right-Click to open this money pouch\n§r§7and claim a random amount of money!!\n\n§r§aType: Common\nAmount: $5,000 - $25,000"]);
            $item->getNamedTag()->setInt("slotBotChestMoney", mt_rand(5000, 25000));
            return $item;
        } else if ($type == 3) {
            $item->setCustomName("§r§l§6Legendary Money Pouch §8| §r§7(Right-Click)");
            $item->setLore(["§r§7Right-Click to open this money pouch\n§r§7and claim a random amount of money!!\n\n§r§6Type: Legendary\nAmount: $75,000 - $150,000"]);
            $item->getNamedTag()->setInt("slotBotChestMoney", mt_rand(75000, 150000));
            return $item;
        } else {
            $item->setCustomName("§r§l§aCommon Money Pouch §8| §r§7(Right-Click)");
            $item->setLore(["§r§7Right-Click to open this money pouch\n§r§7and claim a random amount of money!!\n\n§r§aType: Common\nAmount: $5,000 - $25,000"]);
            $item->getNamedTag()->setInt("slotBotChestMoney", mt_rand(5000, 25000));
            return $item;
        }
    }

    public function generateRollingArray(int $uh) : void {
        $this->rollingItems[$uh] = [];
        for ($i = 0; $i <= 5; $i++) {
            $this->rollingItems[$uh][$i] = $this->generateRollingItem();
        }
    }

    public function roll(Player $player, array $slots, Inventory $inventory) : void {
        $this->running = true;
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new RollTask($player, $inventory, Loader::getSlotBotManager()->slotBotSessionManager->getSlotBotSession($player), $slots), 5);
    }



}