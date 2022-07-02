<?php

namespace vale\sage\demonic\ranks;

use pocketmine\Server;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\Loader;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;

class RankListener implements Listener
{

	/** @var Loader */
	public Loader $core;

	/**
	 * GroupListener constructor.
	 *
	 * @param Loader $core
	 */
	public function __construct(Loader $core)
	{
		$this->core = $core;
	}

	/**
	 * @priority HIGHEST
	 * @param PlayerChatEvent $event
	 */
	public function onPlayerChat(PlayerChatEvent $event): void
	{
		$session = Loader::getInstance()->getSessionManager()->getSession($event->getPlayer());
		$player = $session->getPlayer();
        $event->setFormat(Loader::getInstance()->getRankManager()->getChatFormat($session) . $event->getMessage());

	}
}