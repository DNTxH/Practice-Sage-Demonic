<?php
namespace vale\sage\demonic\addons\types\broadcast\task;


use pocketmine\scheduler\Task;
use pocketmine\Server;
use vale\sage\demonic\addons\types\broadcast\BroadcastManager;

class BroadcastTask extends Task{

    /**
     * @param BroadcastManager $broadcastManager
     * @param string|null $lastMessage
     */
	public function __construct(
		private BroadcastManager $broadcastManager,
		private ?string $lastMessage = ""
	){
	}

    /**
     * @param string $last
     * @return void
     */
	public function setLastMessage(string $last): void{
		$this->lastMessage = $last;
	}

	/**
	 * @return string
	 */
	public function getLastMessage(): string{
		return $this->lastMessage;
	}

	/**
	 * @return BroadcastManager|null
	 */
	public function getBroadCastManager(): ?BroadcastManager{
		return $this->broadcastManager;
	}

	public function onRun(): void
	{
		$messages = $this->getBroadCastManager()->getMessages();
		$random = array_rand($messages);
		if ($this->getLastMessage() !== $messages[$random]) {
			Server::getInstance()->broadcastMessage($messages[$random]);
			$this->setLastMessage($messages[$random]);
		}
	}
}