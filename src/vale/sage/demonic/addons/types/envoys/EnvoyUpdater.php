<?php
namespace vale\sage\demonic\addons\types\envoys;

use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\FloatingTextParticle;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\Loader;

class EnvoyUpdater
{

    /** @var int */
	public int $time = 30;

    /** @var FloatingTextParticle */
	public static FloatingTextParticle $floatingText;

    /** @var string */
	public string $text = "";

    /** @var array|string[] */
	public array $avaliable = ["kills", "credits"];

    /** @var EnvoyUpdater */
	public static EnvoyUpdater $instance;

    /** @var Vector3 */
	public Vector3 $pos;

    /** @var int */
    public int $wait = 0;

    /**
     * @param Vector3 $pos
     */
	public function __construct(Vector3 $pos)
	{
		self::$instance = $this;
		$this->pos = $pos;
		self::$floatingText = new FloatingTextParticle("");
		$lol = self::$floatingText;
		Loader::getInstance()->getServer()->getWorldManager()->getDefaultWorld()->addParticle($pos, $lol);
	}

    /**
     * @param string $ok
     * @return void
     */
	public function setText(string $ok)
	{
		$this->text = $ok;
	}

    /**
     * @return Vector3
     */
	public function getPos()
	{
		return $this->pos;
	}

    /**
     * @return static
     */
	public static function getInstance(): self
	{
		return self::$instance;
	}

    /**
     * @return FloatingTextParticle
     */
	public static function getFloatingText(): FloatingTextParticle{
		return self::$floatingText;
	}

	public function tick(): void
	{
		if (Envoy::getInstance()->isEnabled()) {
			return;
		}
		$this->wait++;
		if ($this->wait >= 5) {
			foreach (Server::getInstance()->getOnlinePlayers() as $player) {
				if (count(Server::getInstance()->getOnlinePlayers()) >= 1) {
					if ($player === null) {
						return;
					}
					$time = Loader::secondsToTime(Envoy::getInstance()->time);
					$particle = self::getFloatingText();
					$particle->setTitle("§r§6§lNEXT ENVOY STARTING IN \n \n§r§d$time");
					$particle->setText("\n §r§eWelcome to §r§6§lSage§r§e §lPvP §r§7[Demonic Realm] \n \n  §r§6§l/spawn §r§eis a safe area where you can't be hurt! \n \n §r§eHere you will find the §6§lSage§r§f-§e§lSlot /bot§r§f, §r§e§lNPC's §r§eand more! \n  \n §r§eYou can learn more by doing §r§e§l/help \n  \n §r§eStart your ADVENTURE §r§eby jumping down to the §r§6§lWarzone §r§ebelow! \n §r§e§l| \n §r§e§l| \n §r§e§l| \n §r§e§l| \n§r§e§lV");
					$player->getWorld()->addParticle($this->pos, $particle);
				}
			}
			$this->wait = 0;
		}
	}
}