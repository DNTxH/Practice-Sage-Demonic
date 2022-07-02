<?php 

namespace vale\sage\demonic\addons\types\chatGames;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use vale\sage\demonic\Loader;

class ChatGames {

    /** @var bool */
    public bool $canSolve = false;

    /** @var string  */
    public string $toSolve = "";

    public function startGames() : void {
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new ChatGamesTask(), 20);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function win(Player $player) : void {
        $this->canSolve = false;
        $this->toSolve = "";
        $random = mt_rand(100,300);
        Loader::getInstance()->getServer()->broadcastMessage("	\n§l§5*§8*§5*§dGenesis ChatGames§5*§8*§5*§r§7\n§5".$player->getName()." has successfully unscrambled the word and has won §d$".$random."!");
        //todo reward($player, $random);
    }

}