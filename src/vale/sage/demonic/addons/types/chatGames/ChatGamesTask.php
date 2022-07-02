<?php 

namespace vale\sage\demonic\addons\types\chatGames;

use pocketmine\scheduler\Task;
use pocketmine\utils\TextFormat as TF;
use vale\sage\demonic\Loader;

class ChatGamesTask extends Task {

    private array $words = [
        "genesis",
        "mcpe",
        "sumxprove",
        "fusion",
        "phoenix",
        "feline",
        "yousuck",
        "javaisbetter",
        "sumbestdev",
        "youtube",
        "boost",
        "free",
        "boomyourbang",
        "ancientlands"
    ];

    /** @var int */
    private int $time = 300;

    /** @var int */
    private int $solvingTime = 60;

    public function onRun(): void{
        if (Loader::getChatGames()->canSolve) {
            $this->solvingTime--;
            if ($this->solvingTime < 1) {
                Loader::getInstance()->getServer()->broadcastMessage("	\n§l§5*§8*§5*§dGenesis ChatGames§5*§8*§5*§r§7\n§7No one could unscramble the word: §5".Loader::getChatGames()->toSolve."§7 and the game has ended.");
                Loader::getChatGames()->canSolve = false;
                $this->solvingTime = 60;
                $this->time = 0;
            }
        } else {
            $this->time++;
            if ($this->time > 300) {
                $word = $this->words[array_rand($this->words)];
                $shuffled = str_shuffle($word);
                Loader::getChatGames()->toSolve = $word;
                Loader::getInstance()->getServer()->broadcastMessage("	\n§l§5*§8*§5*§dGenesis ChatGames§5*§8*§5*§r§7\n§7Unscramble the word: §5".$shuffled." §7to win a prize!");
                Loader::getChatGames()->canSolve = true;
                $this->time = 0;
            }
        }
    }

}
