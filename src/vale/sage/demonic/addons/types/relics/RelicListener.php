<?php
namespace vale\sage\demonic\addons\types\relics;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use vale\sage\demonic\addons\AddonManager;
use vale\sage\demonic\addons\types\relics\types\UndiscoveredMeteor;
use vale\sage\demonic\crate\CrateKey;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class RelicListener implements Listener
{

    /** @var AddonManager */
	private AddonManager $addonManager;

	/** @var array|int[] $ids */
	public array $ids = [4, 3, 12, 1];

    /** @var array */
	public $canBreakWith = [];

    /**
     * @param AddonManager $addonManager
     */
	public function __construct(AddonManager $addonManager)
	{
		$this->addonManager = $addonManager;
		$this->addonManager->getLoader()->getServer()->getPluginManager()->registerEvents($this, $this->addonManager->getLoader());
	}

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
	public function onBreak(BlockBreakEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$areaManager = Loader::getInstance()->getRegionManager();
		$areas = $areaManager->getAreasInPosition($player->getPosition()->asPosition());
		if ($areas !== null){
			$player->sendMessage("§r§c§l(!) §r§cYou can't destroy in a warzone");
			$event->cancel();
		}
			if (Loader::getInstance()->getAddonManager()->getEventManager()->getMiningEvent()->isEnabled()) {
				return;
			}
		if(!$event->isCancelled() && in_array($block->getId(), $this->ids)){
			if (mt_rand(0, 50) == mt_rand(0, 50) && !$event->isCancelled()) {
				$ent = new UndiscoveredMeteor($player->getLocation(), null);
				$ent->setPos(new Position($block->getPosition()->getX() + 2, $block->getPosition()->getY() + 1, $block->getPosition()->getZ(), $block->getPosition()->getWorld()));
				$ent->spawnToAll();
				$player->sendPopup("§r§l§6+ 1 METEORS \n\n\n\n\n\n\n");
				$message = "§r§e§l(!) §r§e{$player->getName()} has discovered an §6§l*** UNDISCOVERED METEOR §r§6§l***\n§r§7You can discover these meteors from mining and win up to (5-6) multiple reward(s).";
				Server::getInstance()->broadcastMessage($message);
				var_dump("true1");
			}
			if (mt_rand(0, 50) == mt_rand(0, 50) && !$event->isCancelled()) {
				$keys = [CrateKey::getMasteryKey(), CrateKey::getLegendaryKey()];
				$key = $keys[array_rand($keys)];
				$name = $key->getName();
                $player->sendTip(TextFormat::GOLD . "+ 1 $name Keys \n §r§7You uncovered a $name §r§7key from excavating.");
				var_dump("true2");
			}
			if (mt_rand(0, 50) == mt_rand(0, 50) && !$event->isCancelled()) {
				$event->setDrops([Rewards::getLuckyBlock(1)]);
				$player->sendTip("\n§r§l§e+1 Lucky Lootcrate\n§r§7(Tip: Pick it up before it despawns)");
				var_dump("true3");
			}
		}
	}
}