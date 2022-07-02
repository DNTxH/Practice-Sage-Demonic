<?php namespace vale\sage\demonic\slotbot\tasks;

use vale\sage\demonic\slotbot\sessions\SlotBotSession;
use vale\sage\demonic\slotbot\utils\MappedRollingOrders;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class RollTask extends Task {

    private Player $player;

    private Inventory $inventory;

    private SlotBotSession $session;

    private array $slots;

    public function __construct(Player $player, Inventory $inventory, SlotBotSession $session, array $slots) {
        $this->player = $player;
        $this->inventory = $inventory;
        $this->session = $session;
        $this->slots = $slots;
    }

    public function onRun() : void {
        $player = $this->player;
        if ($player == null or !$player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }
        $inv = $this->inventory;
        $session = $this->session;
        $session->runningTime++;
        if ($session->runningTime > 12) {
            if (!$player->getCurrentWindow() == null) $player->removeCurrentWindow();
            $session->running = false;
            $session->runningTime = 0;
            foreach ($this->slots as $slot) {
                $player->getInventory()->addItem($session->generateRollingItem());
                $player->sendTitle("Received Rewards", "Enjoy");
            }
            $this->getHandler()->cancel();
            return;
        }
        foreach ($this->slots as $slot) {
            foreach(MappedRollingOrders::MAPPED_ROLLING_ORDERS[$slot] as $s => $mSlot) {
                $inv->setItem($mSlot, $session->generateRollingItem());
            }
        }
    }

}