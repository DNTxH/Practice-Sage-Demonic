<?php
namespace vale\sage\demonic\addons\types\envoys\events\lms;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use vale\sage\demonic\addons\EventManager;
use vale\sage\demonic\addons\types\envoys\EventBase;
use vale\sage\demonic\addons\types\envoys\events\lms\player\LMSPlayer;

class LMSManager extends EventBase
{

    /** @var bool */
	public bool $enabled = false;

    /** @var LMSManager */
	public static LMSManager $instance;

    /** @var array $lastMan */
    public static array $lastMan = [];

    /**
     * @param EventManager $eventManager
     * @param $eventName
     */
	public function __construct(EventManager $eventManager, private $eventName)
	{
		self::$instance = $this;
		$this->setEnabled(false);
		$this->eventManager = $eventManager;
		#$this->eventName = $eventName;
		parent::__construct($eventManager, "LMS");
	}

	public function disable()
	{
		Server::getInstance()->broadcastMessage("§r§3§l** LAST MAN STANDING DISABLED **");
	}

	public function tick(): void
	{
		if (!$this->isEnabled()) {
			return;
		}
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if (self::isCurrentLastManStanding($player)) {
				$session = self::getCurrentLastMan($player);
				$session->update();
			}
			if(self::isPositionInside($player->getPosition()) && empty(self::$lastMan)){
				self::setLastMandStanding($player);
			}
			if(!self::isPositionInside($player->getPosition()) && self::isCurrentLastManStanding($player)){
				self::removeCurrentLMSCaptuer($player);
			}
		}
	}

    /**
     * @return LMSManager|null
     */
	public static function getInstance(): ?LMSManager
	{
		return self::$instance;
	}

	/**
	 * @param Player $player
	 * @return mixed|LMSPlayer
	 */
	public static function setLastMandStanding(Player $player)
	{
		if (!isset(self::$lastMan[$player->getName()])) {
			self::$lastMan[$player->getName()] = new LMSPlayer($player);
		}
		#Server::getInstance()->broadcastMessage($player->getName() . " IS CAPPING");
		return self::$lastMan[$player->getName()];
	}

    /**
     * @return LMSPlayer|null
     */
	public static function getCurrentLastManCaptuer(): ?LMSPlayer{
		foreach (Server::getInstance()->getOnlinePlayers() as $player){
			if(self::isCurrentLastManStanding($player)){
				return self::getCurrentLastMan($player);
			}
		}
		return null;
	}

	/**
	 * @param Player $player
	 * @return LMSPlayer|null
	 */
	public static function getCurrentLastMan(Player $player): ?LMSPlayer
	{
		if (self::isCurrentLastManStanding($player)) {
			return self::$lastMan[$player->getName()];
		}
		return null;
	}

	/**
	 * @param Player $player
	 */
	public static function removeCurrentLMSCaptuer(Player $player)
	{
		if (!isset(self::$lastMan[$player->getName()])) {
			return;
		}
		#Server::getInstance()->broadcastMessage($player->getName() . " NO LONGER CAPTURING");
		unset(self::$lastMan[$player->getName()]);
	}

	public function announce(): void
	{
		Server::getInstance()->broadcastMessage("§l§3** LAST MAN STANDING HAS INITIATED ** \n§r§o§7(LMS: Last Man Standing are you capable of withstanding it? Contest and Capture to win rewards such as \n §r§7§o(XP, Cash, Keys, CCS & more!)");
	}

	/**
	 * @param Player $player
	 * @return bool
	 */
	public static function isCurrentLastManStanding(Player $player): bool
	{
		return isset(self::$lastMan[$player->getName()]);
	}

    /**
     * @param Position $position
     * @return bool
     */
	public static function isPositionInside(Position $position): bool
	{
		$level = $position->getWorld();
		$firstPosition = new Position(0,96,75, $level);
		$secondPosition = new Position(-5,96,70, $level);
		$minX = min($firstPosition->getX(), $secondPosition->getX());
		$maxX = max($firstPosition->getX(), $secondPosition->getX());
		$minZ = min($firstPosition->getZ(), $secondPosition->getZ());
		$maxZ = max($firstPosition->getZ(), $secondPosition->getZ());
		return $minX <= $position->getX() and $maxX >= $position->getFloorX() and
			$minZ <= $position->getZ() and $maxZ >= $position->getFloorZ() and
			$firstPosition->getWorld()->getFolderName() === Server::getInstance()->getWorldManager()->getDefaultWorld()->getFolderName();
	}
}
