<?php

namespace vale\sage\demonic\Trojan\command;

use vale\sage\demonic\Trojan\Task\MuteTask;
use vale\sage\demonic\Loader;

class CommandManager
{
    public static function init(){
        Loader::getInstance()->getServer()->getCommandMap()->registerAll("vale\sage\demonic\Trojan",[
            new Ban(Loader::getInstance(), "ban", "Ban a player"),
            new unban(Loader::getInstance(), "unban", "Unban a player"),
            new TempBan(Loader::getInstance(), "tempban", "Tempban a player"),
            new Mute(Loader::getInstance(), "mute", "Mute a player"),
            new unMute(Loader::getInstance(), "unmute", "Unmute a player"),
            new tempMute(Loader::getInstance(), "tempmute", "Tempmute a player"),
            new Kick(Loader::getInstance(), "kick", "Kick a player"),
            new warn(Loader::getInstance(), "warn", "Warn a player"),
            new warns(Loader::getInstance(), "warns", "Check player's warns"),
            new BlackList(Loader::getInstance(), "blacklist", "Blacklist a player"),
            new unBlackList(Loader::getInstance(), "unblacklist", "Unblacklist a player"),
            new violation(Loader::getInstance(), "violation", "Check player's violations"),
            new SetAC(Loader::getInstance(), "setac", "Enable or Disable cheat alert"),
        ]);
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new MuteTask(), 20);
    }
}