<?php

namespace vale\sage\demonic\Trojan\command;

use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use vale\sage\demonic\Trojan\TrojanAPI;

class violation extends BaseCommand
{
    public function prepare(): void
    {
        $this->registerArgument(0, new TestArg("player", true));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if(!(isset($args["player"]))) {
            $target = $sender->getName();
        } else {
            $target = $args["player"];
        }
        TrojanAPI::seeLog($sender, $target);
    }
}