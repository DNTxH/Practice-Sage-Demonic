<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use vale\sage\demonic\kits\GuiManager;

class KitCommand extends BaseCommand
{
    protected function prepare(): void
    {

    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!$sender instanceof Player) {
            return;
        }

        GuiManager::openGui($sender, "testing category");
    }
}