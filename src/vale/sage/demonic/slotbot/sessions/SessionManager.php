<?php namespace vale\sage\demonic\slotbot\sessions;

use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

class SessionManager {

    /**
     * @var array<string, SlotBotSession>
     */
    private array $sessions = [];

    public function createSlotBotSession(Player $player) : void {
        $this->sessions[$player->getName()] = new SlotBotSession();
    }

    public function getSlotBotSession(Player $player) : SlotBotSession {
        return $this->sessions[$player->getName()];
    }

    public function closeSession(Player $player) : void {
        unset($this->sessions[$player->getName()]);
    }

}