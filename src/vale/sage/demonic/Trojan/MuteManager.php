<?php

namespace vale\sage\demonic\Trojan;

use pocketmine\utils\Config;
use vale\sage\demonic\Loader;

class MuteManager
{
    private function getConfig(): Config
    {
        return new Config(Loader::getInstance()->getServer()->getDataPath() . "player_mute_list.yml", Config::YAML, array());
    }

    public function getMuteList(): array
    {
        return $this->getConfig()->getAll();
    }

    public function isMute(string $playerName): bool
    {
        $playerName = strtolower($playerName);
        return $this->getConfig()->exists($playerName);
    }

    public function setMute(string $playerName, string $muter,\DateTime $time = null,?string $reason = null): void
    {
        $playerName = strtolower($playerName);
        $config = $this->getConfig();
        if($time !== null){
            $time = $time->format("Y-m-d H:i:s");
        } else {
            $time = null;
        }
        $config->set($playerName, array("end time" => $time,"reason" => $reason,"muted by" => $muter,"mute by" => date("Y-m-d H:i:s")));
        $config->save();
    }

    public function removeMute(string $playerName): void
    {
        $playerName = strtolower($playerName);
        $config = $this->getConfig();
        $config->remove($playerName);
        $config->save();
    }

    public function getMuteEndTime(string $playerName): \DateTime|bool
    {
        $playerName = strtolower($playerName);
        if(isset($this->getConfig()->getAll()[$playerName]))
        {
            if($this->getConfig()->getAll()[$playerName]["end time"] !== null)
            return new \DateTime($this->getConfig()->getAll()[$playerName]["end time"]);
            else
            return false;
        }
        return false;
    }

    public function getMuteTimeLeft(string $playerName): float|bool
    {
        $playerName = strtolower($playerName);
        if(isset($this->getConfig()->getAll()[$playerName]))
        {
            if($this->getMuteEndTime($playerName) === false){
                return false;
            }
            $time = new \DateTime($this->getMuteEndTime($playerName)->format("Y-m-d H:i:s"));
            $now = new \DateTime();
            return $time->getTimestamp() - $now->getTimestamp();
        }
        return false;
    }

}