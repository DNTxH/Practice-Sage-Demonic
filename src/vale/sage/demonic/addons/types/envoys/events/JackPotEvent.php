<?php
namespace vale\sage\demonic\addons\types\envoys\events;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\EventManager;
use vale\sage\demonic\addons\types\envoys\EventBase;
use vale\sage\demonic\Loader;

class JackPotEvent extends EventBase
{
    /** @var int  */
	public int $prizepool = 0;

    /** @var int */
	public int $time = 30;

    /** @var JackPotEvent */
	public static JackPotEvent $instance;

    /** @var array $players */
	public array $players = [];

    /** @var int */
    public $default = 0;

    /**
     * @param EventManager $eventManager
     * @param $eventName
     */
	public function __construct(EventManager $eventManager, private $eventName)
	{
		self::$instance = $this;
		$this->eventManager = $eventManager;
		$this->setPrizePool(rand(100000, 8383838));
		parent::__construct($eventManager, "JackPot");
	}

    /**
     * @return JackPotEvent|null
     */
	public static function getInstance(): ?JackPotEvent
	{
		return self::$instance;
	}

	public function tick(): void
	{
		--$this->time;
		if ($this->time <= 0) {
			if (count($this->players) <= 0) {
				Server::getInstance()->broadcastMessage("§r§c§l(!) §r§cFailed to draw a winner, could not find any ticket(s).");
				$this->time = rand(4000,10000);
			}
			if (count($this->players) >= 1 && $this->time <= 0) {
				$this->drawWinner();
				$this->time = rand(4000,10000);
			}
		}
	}


    /**
     * @param $player
     * @param $amount
     * @return bool|\mysqli_result
     */
	public function addMoney($player, $amount)
	{
		$amount = (float)$amount;
		return Loader::getInstance()->getMysqlProvider()->getDatabase()->query("UPDATE players SET balance = balance + $amount WHERE username='" . Loader::getInstance()->getMysqlProvider()->getDatabase()->real_escape_string($player) . "'");
	}

    /**
     * @param $player
     * @param $amount
     * @return bool|\mysqli_result
     */
	public function addJackPotWins($player, $amount = 1)
	{
		$amount = (float)$amount;
		return Loader::getInstance()->getMysqlProvider()->getDatabase()->query("UPDATE players SET jackpotwins = jackpotwins + $amount WHERE username='" . Loader::getInstance()->getMysqlProvider()->getDatabase()->real_escape_string($player) . "'");
	}

    /**
     * @param $player
     * @param $amount
     * @return bool|\mysqli_result
     */
	public function addJackPotEarnings($player, $amount)
	{
		$amount = (float)$amount;
		return Loader::getInstance()->getMysqlProvider()->getDatabase()->query("UPDATE players SET jackpotearnings = jackpotearnings + $amount WHERE username='" . Loader::getInstance()->getMysqlProvider()->getDatabase()->real_escape_string($player) . "'");
	}

    /**
     * @param Player $player
     * @return void
     */
	public function formatStats(Player $player): void
	{
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$mytickets = $this->getTickets($player->getName());
		$wins = $session->getJackPotWins();
		$player->sendMessage("§r§d§lSage Jackpot Stats §r§7({$player->getName()})");
		$player->sendMessage("§r§bTotal Winnings: §r§d§l$" . "§r§d" . number_format($session->getJackPotEarnings(), 2));
		$player->sendMessage("§r§b§lTotal Tickets Purchased: §r§d$mytickets");
		$player->sendMessage("§r§b§lTotal Jackpot Wins: §r§d$wins");
	}

    /**
     * @param Player $player
     * @return void
     */
	public function formatMessage(Player $player): void
	{
		$percentage = 0;
		$value = number_format($this->getPrizePool(), 2);
		$mytickets = $this->getTickets($player->getName());
		$tickets = [];
		foreach ($this->players as $playera => $amount) {
			for ($i = 0; $i < $amount; $i++) {
				$tickets[] = $player;
			}
		}
		$ticketz = number_format(count($tickets), 2);
		if ($mytickets >= 1) $percentage = ($mytickets / count($this->players)) * 100;

		$player->sendMessage("§r§d§lSage Jackpot");
		$player->sendMessage("§r§b§lJackpot Value§r§b: §r§d$$value §r§7(-10% tax)");
		$player->sendMessage("§r§b§lTickets Sold§r§b: §r§e$ticketz");
		$player->sendMessage("§r§b§lYour Tickets§b: §r§a$mytickets §r§7($percentage%)");
		$player->sendMessage("\n");
		$player->sendMessage("§r§b§l(!) §r§bNext winner in " . Loader::secondsToTime($this->time));
	}

	public function drawWinner(): void
	{

		$tickets = [];
		foreach ($this->players as $player => $amount) {
			for ($i = 0; $i < $amount; $i++) {
				$tickets[] = $player;
			}
		}
		shuffle($tickets);
		$player = $tickets[array_rand($tickets)];
		$winning = Server::getInstance()->getPlayerExact($player);
		if (!$winning instanceof Player) {
			$this->addMoney($player, $this->getPrizePool());
			$pool = number_format($this->prizepool, 2);
			$mytickets = $this->getTickets($player);
			$ticketz = number_format(count($tickets), 2);
			Server::getInstance()->broadcastMessage("§r§a§l(!) §r§a{$player} has won the /jackpot and received \n §r§2$" . $pool . "§r§a! \n §r§aThey purchased {$mytickets} ticket(s) \n §r§aout of the $ticketz ticket(s) sold!");
			$this->addJackPotWins($player, 1);
			$this->addJackPotEarnings($player, $this->getPrizePool());
			$this->setPrizePool(rand(100000, 8383838));
			$this->players = array();
			return;
		}
		if ($winning instanceof Player && $winning->isOnline()) {
			$pool = number_format($this->prizepool, 2);
			$mytickets = number_format($this->getTickets($player), 2);
			$ticketz = number_format(count($tickets), 2);
			$session = Loader::getInstance()->getSessionManager()->getSession($winning);
			$session->addBalance($this->getPrizePool());
			$session->increaseEarnings($this->getPrizePool());
			$session->increaseWins();
			Server::getInstance()->broadcastMessage("§r§a§l(!) §r§a{$player} has won the /jackpot and received \n §r§2$" . $pool . "§r§a! \n §r§aThey purchased {$mytickets} ticket(s) \n §r§aout of the $ticketz ticket(s) sold!");
			$winning->sendMessage("§r§a§l+ $" . $this->getPrizePool());
			$winning->getWorld()->addSound($winning->getLocation(), new XpLevelUpSound(1000));
			$this->players = array();
			$this->setPrizePool(rand(100000, 8383838));
		}
	}

	/**
	 * @param $player
	 * @return int
	 */
	public function getTickets($player): int{
		if(!isset($this->players[$player])) return 0;
		return (int) $this->players[$player];
	}

	/**
	 * @param Player $player
	 * @param int $amount
	 * @param int $price
	 */
	public function addTickets(Player $player, int $amount = 1, int $price = 1): void{
	$reduction = 10000;
	$session = Loader::getInstance()->getSessionManager()->getSession($player);
	if($session->getBalance() < $price){
		$player->sendMessage("§r§c§l(!) §r§cYou don't have the required balance to purchase tickets.");
		return;
	}
     $tickets = $this->players[$player->getName()] ?? 0;
	 $session->setBalance($session->getBalance() - $price);
	 $player->sendMessage("§r§a§l(!) §r§aYou succesfully purchased x$amount Ticket(s).");
	 $player->getWorld()->addSound($player->getLocation(),new XpLevelUpSound(1000));
	 $this->players[$player->getName()] = $tickets + $amount;
	}

	/**
	 * @param int $prize
	 */
	public function setPrizePool(int $prize): void{
		$this->prizepool = $prize;
	}

	/**
	 * @return int
	 */
	public function getPrizePool(): int{
		return $this->prizepool;
	}
}