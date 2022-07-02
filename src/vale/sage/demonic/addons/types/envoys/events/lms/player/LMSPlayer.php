<?php
namespace vale\sage\demonic\addons\types\envoys\events\lms\player;
use pocketmine\player\Player;
use pocketmine\Server;
use vale\sage\demonic\addons\types\envoys\events\lms\LMSManager;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class LMSPlayer{

    /** @var int */
	public int $time = 0;

	public const TIMES = [
		100,
		200,
		300,
		400,
		500,
		600,
		700,
		800,
		900,
	];

    /** @var int */
	public int $cash = 0;

    /** @var int */
	public int $xp = 0;

    /** @var Player */
	public Player $player;

    /** @var int */
    public $increaserewards = 30;

    /** @var int */
    public $timeForReward = 100;

    /**
     * @param Player $player
     */
	public function __construct(Player $player){
		$this->player = $player;
		$this->player->sendMessage("§r§e§l(!) §r§eYou have entered the /lms area!");
		Server::getInstance()->broadcastMessage("§r§a§l(!) §r§a" .$player->getName() . " §r§ais now the /lms!");
		$this->player->sendMessage("§r§3§l** LAST MAN STANDING **");
		$this->player->sendMessage("§r§7To claim your pending rewards, walk off the LMS, and you will receive your XP and or Cash.");
	}

    /**
     * @return int
     */
	public function getTime(): int{
		return $this->time;
	}

	/**
	 * @param int $secs
	 * @return string
	 */
	public static
	function secondsToTime(int $secs) {
		$m = floor(($secs % 3600) / 60);

		return  "$m minutes";
	}

	public function update(): void{
		$this->increaserewards--;
		$this->timeForReward--;
		if($this->timeForReward <= 0){
			$items = [Rewards::get(Rewards::STARTER_BUNDLE),
				Rewards::get(Rewards::ITEM_LORE_CRYSTAL),
				Rewards::get(Rewards::LEGENDARY_BOOK)];
			$item = $items[array_rand($items)];
			$count = $item->getCount();
			$name = $item->getName();
			$this->player->sendMessage("§r§f§l* §r§fx$count . $name");
			$this->player->getInventory()->addItem($item);
			$this->timeForReward = 100;
		}
		if($this->increaserewards <= 0){
			$this->xp+= rand(1,100);
			$this->cash += rand(1,10000);
			$this->increaserewards = 30;
		}
		$this->time++;
		if(!$this->player->isOnline() || $this->player->isClosed() || $this->player === null){
			LMSManager::removeCurrentLMSCaptuer($this->player);
			return;
		}
		if(!LMSManager::isPositionInside($this->player->getPosition()) && LMSManager::isCurrentLastManStanding($this->player)){
			$session = Loader::getInstance()->getSessionManager()->getSession($this->player);
			$time = self::secondsToTime($this->time);
			$xp = number_format($this->xp,2);
			$money = number_format($this->cash, 2);
			$this->player->sendMessage("§r§3§l*** LMS RESULTS **");
			$this->player->sendMessage("§r§7Cash: $$money");
			$this->player->sendMessage("§r§7XP: {$xp} XP");
			$session->addBalance($this->cash);
			$session->getPlayer()->sendMessage("§r§a§l+ $$money");
			$session->getPlayer()->sendMessage("§r§a§l+ $xp");
			$session->getPlayer()->getXpManager()->addXp($this->xp);
			LMSManager::removeCurrentLMSCaptuer($this->player);
			return;
		}
		if(LMSManager::isPositionInside($this->player->getPosition())){
			$this->player->sendTip("TIME: $this->time");
		}
		if(in_array($this->time,self::TIMES)){
			$xp = number_format($this->xp,2);
			$money = number_format($this->cash, 2);
			$time = self::secondsToTime($this->time);
			Server::getInstance()->broadcastMessage("§r§3§l[LMS] §r§b{$this->player->getName()} has survived as the §r§3/lms §r§bfor §r§3{$time} \n §r§band has recieved $$money and {$xp}XP - §r§bcan anyone stop them?");
		}
	}
}