<?php namespace vale\sage\demonic\koth\task;

use vale\sage\demonic\koth\Koth;
use vale\sage\demonic\Loader;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class KothRunTask extends Task {

    private Koth $koth;

    public function __construct() {
        $this->koth = Loader::getKoth();
        $this->koth->timeLeft = Koth::KOTH_TIME;
    }

    public function onRun() : void {
        $this->koth->timeLeft--;
        if ($this->koth->timeLeft < 1) {
            $winner = $this->koth->getCurrentKothWinner();
            if ($winner == null) {
                Server::getInstance()->broadcastMessage("§l§aKOTH §r§7> §fNo one participated in this §eKOTH§f! No one has won.");
            } else {
                $player = Server::getInstance()->getPlayerByPrefix($winner);
                if ($player == null) {
                    Server::getInstance()->broadcastMessage("§l§aKOTH §r§7> §fThe winner has logged out.");
                } else {
                    Server::getInstance()->dispatchCommand(new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage()), str_replace("{name}", $player->getName(), Koth::WINNER_COMMAND));
                    Server::getInstance()->broadcastMessage("§l§aKOTh §r§7> §f".Loader::getKoth()->getFaction($player)." has won the §eKOTH§f! §r§7(".$player->getName().")");
                }
            }
            $this->koth->timeLeft = 0;
            $this->koth->running = false;
            $this->koth->capturing = "";
            $this->koth->capture = 0;
            $this->getHandler()->cancel();
            return;
        }
        if ($this->koth->capture > 5) {
            $p = Server::getInstance()->getPlayerByPrefix($this->koth->capturing);
            if ($p == null) {
                $this->koth->capturing = "";
                $this->koth->capture = 0;
                return;
            }
            $this->koth->capture++;
        } else {
            if ($this->koth->capture > 0) {
                $p = Server::getInstance()->getPlayerByPrefix($this->koth->capturing);
                if ($p == null or !($this->koth->isInKothPosition($p->getPosition()))) {
                    $this->koth->capturing = "";
                    $this->koth->capture = 0;
                }
                $this->koth->capture++;
                return;
            }
            foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                if (!$this->koth->isInKothPosition($player->getPosition())) continue;
                $this->koth->capture++;
                $this->koth->capturing = $player->getName();
                break;
            }
        }
    }

}