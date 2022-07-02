<?php
namespace vale\sage\demonic;
use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\item\VanillaItems;
use vale\sage\demonic\enchants\factory\EnchantFactory;
use vale\sage\demonic\GenesisPlayer;
use muqsit\invmenu\inventory\InvMenuInventory;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\session\InvMenuInfo;
use pocketmine\block\inventory\ChestInventory;
use pocketmine\block\inventory\DoubleChestInventory;
use pocketmine\block\ItemFrame;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\entity\animation\TotemUseAnimation;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Skin;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerChatEvent,
	PlayerCommandPreprocessEvent,
	PlayerCreationEvent,
	PlayerDeathEvent,
	PlayerInteractEvent,
	PlayerJoinEvent,
	PlayerMoveEvent,
	PlayerQuitEvent,
	PlayerLoginEvent};
use pocketmine\inventory\Inventory;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\TotemUseSound;
use vale\sage\demonic\addons\types\brag\Brag;
use vale\sage\demonic\addons\types\inventorys\SpaceChestInventory;
use vale\sage\demonic\addons\types\monthlycrates\task\MonthlyCrateTickTask;
use vale\sage\demonic\entitys\types\TinkererMerchant;
use vale\sage\demonic\entitys\utils\IEManager;
use vale\sage\demonic\factions\Faction;
use vale\sage\demonic\floatingtext\TManager;
use vale\sage\demonic\ranks\RankManager;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;
use vale\sage\demonic\rewards\Rewards;
use vale\sage\demonic\tasks\types\MysteryStashBoxTask;


class SageListener implements Listener
{
	/** @var array $move */
	public array $move = [];

	/**
	 * @param Loader $plugin
	 */
	public function __construct(
		private Loader $plugin
	)
	{
		$manager = $this->plugin->getServer()->getPluginManager();
		$manager->registerEvents($this, $this->plugin);
	}

	/**
	 * @param PlayerLoginEvent $event
	 */
	public function onLogin(PlayerLoginEvent $event): void
	{
		$player = $event->getPlayer();
		$this->plugin->getSessionManager()->createSession($player);
		$session = $this->plugin->getSessionManager()->getSession($player);
		$session->register();
	}

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
	public function onJoin(PlayerJoinEvent $event): void
	{
		$player = $event->getPlayer();
		$event->setJoinMessage("");
        	$olditem = $player->getInventory()->getItemInHand();
        	$player->getInventory()->setItemInHand(VanillaItems::TOTEM());
        	$player->broadcastAnimation(new TotemUseAnimation($player));
        	$player->broadcastSound(new TotemUseSound());
        	$player->getInventory()->setItemInHand($olditem);
        	$player->sendTitle(TextFormat::DARK_GREEN . "§r§l§8Genesis", "§r§7OP Factions server.");
        	if (!$player->hasPlayedBefore()) $event->setJoinMessage("§8[§a+§8] §7Welcome " . $player->getName() . " to §8Genesis§5Factions§7 S2");
        	else $event->setJoinMessage("§8[§a+§8] §7" . $player->getName());
		$this->move[$player->getName()] = $player;
		Utils::sendJoinMessage($player);
		$player->getInventory()->addItem(RedeemableAPI::getMCCrate(0,32,$player,null));
		$player->getInventory()->addItem(Rewards::get(Rewards::SPECIAL_EQUIPMET_BOX,2));
		$player->getInventory()->addItem(Rewards::get(Rewards::MYSTERYMOB,2));
		$player->getInventory()->addItem(Rewards::get(Rewards::BLAZE_GRAB_BAG,2));
		$player->getInventory()->addItem(Rewards::get(Rewards::RANDOM_MONEY_GENERATOR,2));
		$player->getInventory()->addItem(Rewards::get(Rewards::ENCHANTRESS_BOX,2));
		Loader::getInstance()->getSessionManager()->getSession($player)->load();
	}

    /**
     * @param PlayerChatEvent $event
     * @return void
     */
	public function onChat(PlayerChatEvent $event): void
	{
		$player = $event->getPlayer();
		$message = $event->getMessage();
		$hand = $event->getPlayer()->getInventory()->getItemInHand();
        	if (Loader::getChatGames()->canSolve) {
           	if (Loader::getChatGames()->toSolve == $message) {
               	Loader::getChatGames()->win($player);
			}	
		}	
		if (strpos($message, "brag]") !== false) {
			if (Brag::isBragging($player)) {
				$player->sendMessage("§r§c§l(!) §r§cPlease wait a while before using [BRAG] !");
				$event->cancel();
				return;
			}
			Brag::setBragging($player);
			$reset = TextFormat::colorize("&r");
			$event->setMessage(str_replace("[brag]", "§r§o§6{$event->getPlayer()->getName()}'s Inventory $reset", $event->getMessage()));
			return;
		}
		if (strpos($message, "hand]") !== false) {
			$customname = $hand->hasCustomName() ? $hand->getCustomName() : $hand->getVanillaName();
			$count = $hand->getCount();
			$event->setMessage("$customname §r§6x$count");
		}
		if ($event->getMessage() === "tinkerer") {
			$player->sendMessage("SPAWNED TINKERER");
			$manager = new IEManager(Loader::getInstance(), "tinkerer.png");
			$skin = $manager->skin;
			$entity = new TinkererMerchant($player->getLocation(), $skin);
			$entity->spawnToAll();
		}
	}



	/**
	 * @param PlayerMoveEvent $event
	 */
	public function onMove(PlayerMoveEvent $event): void
	{
		$player = $event->getPlayer();
		if (!isset($this->move[$player->getName()])) {
			return;
		}
		$player->sendMessage("§r§e§lYou §r§emay now run commands.");
		unset($this->move[$player->getName()]);
	}

    /**
     * @param PlayerCommandPreprocessEvent $event
     * @return void
     */
    public function cmdPreProcess(PlayerCommandPreprocessEvent $event) : void {
        $player = $event->getPlayer();
        $message = $event->getMessage();
        if ($message[0] == "/") {
            foreach (Loader::getInstance()->commandSpy as $cmd) {
                $np = Server::getInstance()->getPlayerByPrefix($cmd);
				$np?->sendMessage("§7(§bCS§7) §f" . $player->getName() . " §eSend the command: §g\"" . $message . "\"");
            }
        }
    }

    /**
     * @param PlayerCreationEvent $event
     */
    public function onCreation(PlayerCreationEvent $event): void{
        $event->setPlayerClass(GenesisPlayer::class);
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlace(BlockPlaceEvent $event) {
        if ($event->getBlock() instanceof ItemFrame) $event->cancel();
    }

    /**
     * @param PlayerDeathEvent $event
     */
    public function onDeath(PlayerDeathEvent $event) {
        $cause = $event->getPlayer()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            $player = $event->getPlayer();
            if ($damager instanceof Player) {
                $damager->sendTitle(" §7", "§c§lKILLED§r§7 " . $player->getName(), 10, 20, 10);
                $weapon = $damager->getInventory()->getItemInHand()->getId() !== 0 ? ($damager->getInventory()->getItemInHand()->hasCustomName() ? $damager->getInventory()->getItemInHand()->getCustomName() : $damager->getInventory()->getItemInHand()->getName()) : "his fists";
                $event->setDeathMessage(TextFormat::DARK_RED . $damager->getName() ." §r§7killed " . TextFormat::RED . $player->getName() . "§r§7 using $weapon");
            }
        }
    }

    /**
     * @param PlayerCommandPreprocessEvent $event
     * @return void
     */
	public function onCommand(PlayerCommandPreprocessEvent $event): void
	{
		$player = $event->getPlayer();
		if (isset($this->move[$player->getName()])) {
			$player->sendMessage("§r§c§l(!) §r§cYou may not have access to this command!");
			$player->sendMessage("§r§7Take a step foward to proccess commands.");
			$player->getLocation()->getWorld()->addSound($player->getLocation()->asVector3(), new AnvilFallSound());
			$event->cancel();
		}
	}

	/**
	 * @param InventoryCloseEvent $event
	 */
	public function onClose(InventoryCloseEvent $event): void
	{
		$player = $event->getPlayer();
		$menu = $event->getInventory();
			if ($menu instanceof InvMenuInventory) {
				$lol = InvMenuHandler::getPlayerManager()->get($player)->getCurrent()->menu;
				$this->check($player, $lol);
			}
		}

    /**
     * @param Player $player
     * @param $lol
     * @return void
     */
	public function check(Player $player, $lol)
	{
		if (isset(MonthlyCrateTickTask::$recieve[$player->getName()])) {
			Loader::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($player, $lol): void {
				if ($player != null || $player->isOnline() || !$player->isClosed()) {
					$lol->send($player);
				}
			}), 10);
		}
	}

    /**
     * @param PlayerQuitEvent $event
     */
	public function onQuit(PlayerQuitEvent $event){
		$event->setQuitMessage("");
		$player = $event->getPlayer();
		if(isset($this->move[$player->getName()])){
			unset($this->move[$player->getName()]);
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$session->save();
        $this->plugin->getSessionManager()->removeSession($player);
	}
}