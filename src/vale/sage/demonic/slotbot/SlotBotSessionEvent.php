<?php namespace vale\sage\demonic\slotbot;

use vale\sage\demonic\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;

class SlotBotSessionEvent implements Listener {

    public function onLogin(PlayerLoginEvent $event) : void {
        $player = $event->getPlayer();
        Loader::getSlotBotManager()->slotBotSessionManager->createSlotBotSession($player);
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        Loader::getSlotBotManager()->slotBotSessionManager->closeSession($player);
    }

    public function onItemUse(PlayerItemUseEvent $event) : void {
        $player = $event->getPlayer();
        $item = $event->getItem();
    }

}