<?php

namespace vale\sage\demonic\commands\defaults\talent;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\plugin\PluginOwned;
use pocketmine\plugin\Plugin;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\levels\gui\TalentViewGui;
use vale\sage\demonic\Loader;

class TalentsCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("talents", "Talents management command.");
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof GenesisPlayer) return;
        new TalentViewGui($sender);
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}