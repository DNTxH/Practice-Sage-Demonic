<?php

namespace vale\sage\demonic\commands\defaults\level;

use pocketmine\lang\Translatable;
use vale\sage\demonic\commands\defaults\level\form\LevelUpCommandForm;
use vale\sage\demonic\GenesisPlayer;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\command\Command;
use pocketmine\plugin\PluginOwned;
use vale\sage\demonic\Loader;

class LevelUpCommand extends Command implements PluginOwned {

    public function __construct() {
        parent::__construct("levelup", "Level management command.", null, ["lvlup"]);
    }

    /**
     * @param CommandSender $sender
     * @param string $commandLabel
     * @param array $args
     * @return mixed|void
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if(!$sender instanceof GenesisPlayer) return;
        $sender->sendForm(new LevelUpCommandForm($sender));
    }

    /**
     * @return Plugin
     */
    public function getOwningPlugin(): Plugin {
        return Loader::getInstance();
    }
}