<?php namespace vale\sage\demonic\koth\utils;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;

class KothScoreboardUtils {

    private array $line = [];

    public function showScoreboard(Player $player) : void {
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = $player->getName();
        $pk->displayName = "§l§aGENESIS §eKOTH";
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    public function addLine(string $line, Player $player) : void {
        $score = count($this->line) + 1;
        $this->setLine($score,$line,$player);
    }

    public function removeScoreboard(Player $player) : void {
        $objectiveName = $player->getName();
        $pk = new RemoveObjectivePacket();
        $pk->objectiveName = $objectiveName;
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    public function clearLines(Player $player) {
        for ($line = 0; $line <= 15; $line++) {
            $this->removeLine($line, $player);
        }
    }

    public function setLine(int $loc, string $msg, Player $player) : void {
        $pk = new ScorePacketEntry();
        $pk->objectiveName = $player->getName();
        $pk->type = $pk::TYPE_FAKE_PLAYER;
        $pk->customName = $msg;
        $pk->score = $loc;
        $pk->scoreboardId = $loc;
        if (isset($this->line[$loc])) {
            unset($this->line[$loc]);
            $pkt = new SetScorePacket();
            $pkt->type = $pkt::TYPE_REMOVE;
            $pkt->entries[] = $pk;
            $player->getNetworkSession()->sendDataPacket($pkt);
        }
        $pkt = new SetScorePacket();
        $pkt->type = $pkt::TYPE_CHANGE;
        $pkt->entries[] = $pk;
        $player->getNetworkSession()->sendDataPacket($pkt);
        $this->line[$loc] = $msg;
    }

    public function removeLine(int $line, Player $player) : void {
        $pk = new SetScorePacket();
        $pk->type = $pk::TYPE_REMOVE;
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $player->getName();
        $entry->score = $line;
        $entry->scoreboardId = $line;
        $pk->entries[] = $entry;
        $player->getNetworkSession()->sendDataPacket($pk);
        if (isset($this->line[$line])) {
            unset($this->line[$line]);
        }
    }

}