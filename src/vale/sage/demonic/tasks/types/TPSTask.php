<?php
namespace vale\sage\demonic\tasks\types;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class TPSTask extends Task
{
	public int $time = 40;

	public int $checks = 0;

	public bool $enabled = false;

    	public function onRun(): void{
        /** @var array $input */
        $input = [
        " §r§dGenesis§8PVP",
        "§l§5Gen§desis§8P§7V§8P",
		"§l§5Genesis§7PVP"
        ];
        $details = array_rand($input);
		Server::getInstance()->getNetwork()->setName($input[$details]);
    }
}