<?php
namespace CortexPE\Commando\args;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\player\Player;
use pocketmine\world\World;

class TestArg extends RawStringArgument {

	public function getNetworkType(): int
	{
		return AvailableCommandsPacket::ARG_TYPE_TARGET;
	}


	public function canParse(string $testString, CommandSender $sender): bool {
		return $testString !== "";
	}

	public function getTypeName(): string
	{
		return "XD";
	}
}