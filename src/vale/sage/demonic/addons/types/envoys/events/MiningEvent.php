<?php
namespace vale\sage\demonic\addons\types\envoys\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\Server;
use vale\sage\demonic\addons\EventManager;
use vale\sage\demonic\addons\types\crates\keys\KeyManager;
use vale\sage\demonic\addons\types\envoys\EventBase;
use vale\sage\demonic\addons\types\relics\types\UndiscoveredMeteor;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;
use vale\sage\demonic\crate\CrateKey;
use pocketmine\utils\TextFormat;

class MiningEvent extends EventBase
{

    /** @var bool */
    public bool $enabled = false;

    /** @var MiningEvent */
    public static MiningEvent $instance;

    /** @var array|int[] $ids */
    public array $ids = [4, 3, 12, 1];

    /**
     * @param EventManager $eventManager
     * @param $eventName
     */
    public function __construct(EventManager $eventManager, private $eventName)
    {
        self::$instance = $this;
        $this->setEnabled(false);
        $this->eventManager = $eventManager;
        parent::__construct($eventManager, "MiningEvent");
    }

    public function announce(): void
    {
        Server::getInstance()->broadcastMessage("§l§6** METEORS HAVE BEEN BUFFED ON THE DEMONIC REALM ** \n§r§o§7(The overall drops on the Demonic Realm have been buffed by 35% including \n §r§7§o(Lucky Lootcrates, Meteors, & Keys start excavating in the /wild!)");
    }


    public function disable()
    {
        Server::getInstance()->broadcastMessage("§r§6§l** METEOR EVENT DISABLED **");
    }


    /**
     * @param BlockBreakEvent $event
     */
    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        if (!$this->isEnabled()) {
            return;
        }
        $block = $event->getBlock();
        if (!$event->isCancelled() && in_array($block->getId(), $this->ids)) {
            if (mt_rand(0, 100) == mt_rand(0, 100) && !$event->isCancelled()) {
                $ent = new UndiscoveredMeteor($player->getLocation(), null);
                $ent->setPos($block->getPosition());
                $ent->spawnToAll();
                $player->sendPopup("§r§l§6+ 1 METEORS \n\n\n\n\n\n\n");
                $message = "§r§e§l(!) §r§evaqle has discovered an §6§l*** UNDISCOVERED METEOR §r§6§l***\n§r§7You can discover these meteors from mining and win up to (5-6) multiple reward(s).";
                Server::getInstance()->broadcastMessage($message);
                var_dump("true4");
                return;
            }
            if (mt_rand(0, 100) == mt_rand(0, 100) && !$event->isCancelled()) {
                $keys = [CrateKey::getLegendaryKey(), CrateKey::getMasteryKey()];
                $key = $keys[array_rand($keys)];
                $name = $key->getName();
                $player->sendTip(TextFormat::GOLD . "+ 1 $name Keys \n §r§7You uncovered a $name §r§7key from excavating.");
                var_dump("true5");
                return;
            }
            if (mt_rand(0, 100) == mt_rand(0, 100) && !$event->isCancelled()) {
                $event->setDrops([Rewards::getLuckyBlock(1)]);
                $player->sendTip("\n§r§l§e+1 Lucky Lootcrate\n§r§7(Tip: Pick it up before it despawns)");
                var_dump("true6");
            }
        }
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        return self::$instance;
    }
}