<?php namespace vale\sage\demonic\commands\defaults\admin;

use vale\sage\demonic\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class CommandSpy extends Command {

    public function __construct() {
        parent::__construct("cmdspy", "See what commands players are sending.");
    }

    private function betterunset(array $arr, string $fucu) : array  {
        $new = [];
        foreach ($arr as $val) {
            if ($val == $fucu) continue;
            $new[] = $val;
        }
        return $new;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (Server::getInstance()->isOp($sender->getName())) {
            if (in_array($sender->getName(), Loader::getInstance()->commandSpy)) {
                $sender->sendMessage("You have left commandspy.");
                Loader::getInstance()->commandSpy = $this->betterunset(Loader::getInstance()->commandSpy, $sender->getName());
            } else {
                $sender->sendMessage("You are now in commandspy.");
                Loader::getInstance()->commandSpy[] = $sender->getName();
            }
        }
    }

}