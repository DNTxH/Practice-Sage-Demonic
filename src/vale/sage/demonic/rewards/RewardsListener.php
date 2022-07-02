<?php
namespace vale\sage\demonic\rewards;
use pocketmine\block\Air;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\BlockBreakSound;
use pocketmine\world\sound\XpCollectSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\types\inventorys\SpaceChestInventory;
use vale\sage\demonic\addons\types\inventorys\task\SpaceChestTask;
use vale\sage\demonic\addons\types\monthlycrates\MonthlyCrateInventory;
use vale\sage\demonic\enchants\factory\EnchantFactory;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\redeemable\RedeemableAPI;
use vale\sage\demonic\sets\manager\ArmorManager;
use vale\sage\demonic\spawner\SpawnerManager;
use vale\sage\demonic\spawner\SpawnerUtils;
use vale\sage\demonic\tasks\types\FlareDropTask;
use vale\sage\demonic\tasks\types\LootboxTask;
use vale\sage\demonic\tasks\types\LuckyBlockTask;
use vale\sage\demonic\tasks\types\MysteryStashBoxTask;
use vale\sage\demonic\tasks\types\SatchelBoxTask;
use vale\sage\demonic\tasks\types\VoteLootboxTask;

class RewardsListener implements Listener
{

	/** @var array $itemRenamer */
	public array $itemRenamer = [];

	/** @var array $lorerenamer */
	public array $lorerenamer = [];

	/** @var array $satchelCooldown */
	public array $satchelCooldown = [];

	/** @var array $lootBoxCooldown */
	public array $lootBoxCooldown = [];

	public array $spaceChestCooldown = [];

	public array $mcCoodlown = [];

	public function __construct(
		private Loader $plugin
	)
	{

	}

	public function okk(BlockPlaceEvent $event): void
	{
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$hand = $player->getInventory()->getItemInHand();
		$positon = $block->getPosition();
		if ($hand->getNamedTag()->getString("lucky", "") === "") {
			return;
		}
		if ($block->getPosition()->getWorld()->getBlock($block->getPosition()->subtract(0, 1, 0))->getId() !== BlockLegacyIds::BEDROCK) {
			$player->sendTip("\n§r§l§eLucky Lootcrate\n§r§7(Tip: Place this on bedrock to claim rewards!)");
			$event->cancel();
			return;
		}
		Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new LuckyBlockTask($player, $positon, $positon->getWorld()), 20);
		$hand->setCount($hand->getCount() - 1);
		$player->getInventory()->setItemInHand($hand);
	}


	public function onClick(PlayerItemUseEvent $event)
	{

		$player = $event->getPlayer();
		$hand = $event->getItem();
		$nbt = $hand->getNamedTag();
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		if ($nbt->getString("satchelol", "") !== "") {
			if (isset($this->satchelCooldown[$player->getName()]) && microtime(true) - $this->satchelCooldown[$player->getName()] <= 15) {
				$delayMessage = round(15 - abs($this->satchelCooldown[$player->getName()] - microtime(true)), 2);
				$player->sendMessage("§r§c§l(!) §r§cYou cannot open another satchel for $delayMessage SEC(s).");
				$event->cancel();
				return;
			}
			$event->cancel();
			$id = $hand->getNamedTag()->getInt("satchel");
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SatchelBoxTask($player, null, $id), 10);
			Loader::playSound($player, "beacon.activate");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$this->satchelCooldown[$player->getName()] = microtime(true);
		}
		if ($nbt->getString("mccratelol", "") !== "") {
			if (isset($this->mcCoodlown[$player->getName()]) && microtime(true) - $this->mcCoodlown[$player->getName()] <= 30) {
				$delayMessage = round(30 - abs($this->mcCoodlown[$player->getName()] - microtime(true)), 2);
				$player->sendMessage("§r§c§l(!) §r§cYou cannot open another monthly crate for $delayMessage SEC(s).");
				$event->cancel();
				return;
			}
			$event->cancel();
			$id = $hand->getNamedTag()->getInt("mcrate");
			MonthlyCrateInventory::open($player, RedeemableAPI::getMCByID($id), $id);
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$this->mcCoodlown[$player->getName()] = microtime(true);
		}
		if ($nbt->getString("lootboxlol", "") !== "") {
			if (isset($this->lootBoxCooldown[$player->getName()]) && microtime(true) - $this->lootBoxCooldown[$player->getName()] <= 15) {
				$delayMessage = round(15 - abs($this->lootBoxCooldown[$player->getName()] - microtime(true)), 2);
				$player->sendMessage("§r§c§l(!) §r§cYou cannot open another lootbox for $delayMessage SEC(s).");
				$event->cancel();
				return;
			}

			$id = $hand->getNamedTag()->getInt("lootbox");
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new LootboxTask($player, null, $id), 10);
			$player->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(200));
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$this->lootBoxCooldown[$player->getName()] = microtime(true);
			$event->cancel();
		}

		if ($nbt->getString("mysterystash", "") !== "") {
			if (isset($this->lootBoxCooldown[$player->getName()]) && microtime(true) - $this->lootBoxCooldown[$player->getName()] <= 15) {
				$delayMessage = round(15 - abs($this->lootBoxCooldown[$player->getName()] - microtime(true)), 2);
				$player->sendMessage("§r§c§l(!) §r§cYou cannot open another lootbox for $delayMessage SEC(s).");
				$event->cancel();
				return;
			}
			Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new MysteryStashBoxTask($player, 25, null), 10);
			$player->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(200));
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$this->lootBoxCooldown[$player->getName()] = microtime(true);
			$event->cancel();
		}
		if ($nbt->getString("godly", "") !== "") {
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
			return;
		}

		if ($nbt->getString("soulcontainer", "") !== "") {
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$value = $nbt->getInt("souls");
			$session->addSouls($value);
			$player->sendMessage("§r§e§l+ $value Souls(s)");
			return;
		}
		if ($nbt->getString("holy", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
			return;
		}
		if ($nbt->getString("elite", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
			return;
		}
		if ($nbt->getString("legendary", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
			return;
		}
		if ($nbt->getString("hero", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
			return;
		}
		if ($nbt->getString("demonic", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$winning = rand(100000, 350000);
			$formated = number_format($winning, 2);
			$player->sendMessage("§r§a§l+ §r§a$$formated");
			$session->addBalance($winning);
		}
		if ($nbt->getString("specialbox", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			//get random armor sets add them to array
			$armor = array();
			foreach (ArmorManager::getInstance()->getArmors() as $type => $name) {
				foreach ($name as $idk => $item) {
					$armor[] = $item->asItem();
				}
			}
			$player->getInventory()->addItem($armor[array_rand($armor)]);
			$player->getInventory()->addItem($armor[array_rand($armor)]);
		}
		if ($nbt->getString("mysterymob", "") !== "") {
			$random = [];
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$player->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(100));
			foreach (SpawnerUtils::getEntityArrayList() as $entity) {
				var_dump($entity);
				$object = Loader::getInstance()->getSpawnerManager()->getSpawner($entity);
				$random[] = $object;
			}
			$player->getInventory()->addItem($random[array_rand($random)]);
		}
		if($nbt->getString("blaze_grab","") !== "") {
			$random = [];
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			foreach ([Rewards::createXPBottle(null, 100000),EnchantFactory::giveEnchantUtilities("blackscroll",rand(1,10)),EnchantFactory::giveEnchantUtilities("whitescroll",rand(1,10)), Rewards::createMoneyNote(null,rand(10000,399999)),
						 Rewards::createXPBottle(null,rand(1,19999)),EnchantFactory::getEnchantRandomizer("elite"),EnchantFactory::getEnchantRandomizer("simple"),EnchantFactory::getEnchantRandomizer("legendary"), RedeemableAPI::getMCCrate(0), RedeemableAPI::getRandomStashBox()] as $item) {
				$random[] = $item;
			}
			$player->getInventory()->addItem($random[array_rand($random)]);
		}
		if($nbt->getString("randommoney","") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$player->getInventory()->addItem(Rewards::createMoneyNote(null,rand(10000,rand(10000,4499999))));
		}
		if($nbt->getString("enchantbox","") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			Loader::playSound($player, "mob.wither.break_block");
			$player->getInventory()->addItem(EnchantFactory::getEnchantRandomizer("elite",10));
			$player->getInventory()->addItem(EnchantFactory::getEnchantRandomizer("simple",10));
			$player->getInventory()->addItem(EnchantFactory::getEnchantRandomizer("legendary",10));
			$player->getInventory()->addItem(EnchantFactory::getRandomEnchantDust());
			$player->getInventory()->addItem(EnchantFactory::getRandomEnchantDust());
			$player->getInventory()->addItem(EnchantFactory::getRandomEnchantDust());
			$player->getInventory()->addItem(EnchantFactory::getRandomEnchantDust());
			$player->getInventory()->addItem(EnchantFactory::giveEnchantUtilities("blackscroll",rand(1,10)));
			$player->getInventory()->addItem(EnchantFactory::giveEnchantUtilities("whitescroll",rand(1,10)));
		}
	}

	public function onBlockPlaceEvent(BlockPlaceEvent $event): void
	{
		$player = $event->getPlayer();
		$hand = $player->getInventory()->getItemInHand();
		$nbt = $hand->getNamedTag();
		if($nbt->getString("spacechest","") !== ""){
			$event->cancel();
		}
		if($nbt->getString("satchelol","") !== ""){
			$event->cancel();
		}
		if($nbt->getString("stash","") !== ""){
			$event->cancel();
		}
		if($nbt->getString("lootboxlol","") !== ""){
			$event->cancel();
		}
		if($nbt->getString("mysterystash","") !== ""){
			$event->cancel();
		}
		if($nbt->getString("mccratelol","") !== ""){
			$event->cancel();
		}
	}


	/**
	 * @param PlayerItemUseEvent $event
	 */
	public function onItemUseEvent(PlayerItemUseEvent $event)
	{
		$player = $event->getPlayer();
		$session = Loader::getInstance()->getSessionManager()->getSession($player);
		$item = $event->getItem();
		$nbt = $item->getNamedTag();
		$hand = $player->getInventory()->getItemInHand();
		$name = $item->getCustomName();

		if ($session === null) {
			return;
		}

		if ($nbt->getString("renamecrystal", "") !== "") {
			if (isset($this->lorerenamer[$player->getName()])) {
				$player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a lore rename tag type cancel to remove it!");
				return;
			}
			if (isset($this->itemRenamer[$player->getName()])) {
				$player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a item tag type cancel to remove it!");
				return;
			}
			$this->lorerenamer[$player->getName()] = $player;
			$player->sendMessage("    §r§6§lLore Rename Usage");
			$player->sendMessage("§r§61. §r§7Hold the ITEM you'd like to edit.");
			$player->sendMessage("§r§62. §r§7Send the new name as a chat message §lwith & color codes§r§7.");
			$player->sendMessage("§r§63. §r§7Confirm the preview of the new name that is displayed.");
			Loader::playSound($player, "mob.enderdragon.flap", 2);
			$item->pop();
			return;
		}
		if ($nbt->getString("rename", "") !== "") {
			if (isset($this->itemRenamer[$player->getName()])) {
				$player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a item tag type cancel to remove it!");
				return;
			}
			if (isset($this->lorerenamer[$player->getName()])) {
				$player->sendMessage("§r§c§l(!) §r§cYou are already in queue for a lore rename tag type cancel to remove it!");
				return;
			}
			$this->itemRenamer[$player->getName()] = $player;
			$player->sendMessage("    §r§6§lRename-Tag Usage");
			$player->sendMessage("§r§61. §r§7Hold the ITEM you'd like to rename.");
			$player->sendMessage("§r§62. §r§7Send the new name as a chat message §lwith & color codes§r§7.");
			$player->sendMessage("§r§63. §r§7Confirm the preview of the new name that is displayed.");
			Loader::playSound($player, "mob.enderdragon.flap", 2);
			$item->pop();
			return;
		}
		if ($nbt->getString("starterpack", "") !== "") {
			$common = Rewards::get(Rewards::SIMPLE_BOOK, 5);
			$unq = Rewards::get(Rewards::UNIQUE_BOOK, 5);
			$elie = Rewards::get(Rewards::ELITE_BOOK, 5);
			$ul = Rewards::get(Rewards::ULITMATE_BOOK, 5);
			$leg = Rewards::get(Rewards::LEGENDARY_BOOK, 5);
			$soul = Rewards::get(Rewards::GODLY_BOOK, 2);
			$her = Rewards::get(Rewards::HEROIC_BOOK, 2);
			$mas = Rewards::get(Rewards::MASTERY_BOOK, 5);
			Loader::playSound($player, "dig.soul_soil", 2);
			$player->sendMessage("§r§e§l(!) You've CLAIMED your §r§6§lSTARTER §eBundle!");
			$player->sendMessage("§r§7Congratulations! Enjoy the perks that come with this bundle.");
			Rewards::addSet($player, 6);
			$player->getInventory()->addItem($common);
			$player->getInventory()->addItem($unq);
			$player->getInventory()->addItem($elie);
			$player->getInventory()->addItem($ul);
			$player->getInventory()->addItem($leg);
			$player->getInventory()->addItem($soul);
			$player->getInventory()->addItem($her);
			$player->getInventory()->addItem($mas);
			$player->getInventory()->addItem(Rewards::get(Rewards::LOOTBOX, 1));
			$item->pop();
			return;
		}

		if ($nbt->getString("soulgem", "") !== "") {
			if(isset(EnchantFactory::$godGem[$player->getName()])){
				$player->sendMessage("§r§c§l** SOUL MODE: OFF **");
				$player->sendMessage("§r§7Soul enchantments will no longer drain soul gems.");
				unset(EnchantFactory::$godGem[$player->getName()]);
				Loader::playSound($player, "mob.enderdragon.flap",2);
				return;
			}
			if(!isset(EnchantFactory::$godGem[$player->getName()])){
				EnchantFactory::$godGem[$player->getName()] = $player;
				$player->sendMessage("§r§a§l** SOUL MODE: ON **");
				$player->sendMessage("§r§7Active soul enchantments will now drain soul gems.");
				Loader::playSound($player, "random.levelup",2);
				return;
			}
		}
		if ($nbt->getInt("moneynote", 0) !== 0) {
			$event->cancel();
			$value = $nbt->getInt("moneynote");
			$formatted = number_format($value,2);
			$session->getPlayer()->sendMessage("§r§a§l+ $formatted$");
			$session->getPlayer()->getWorld()->addSound($session->getPlayer()->getLocation(),new XpCollectSound());
			$session->addBalance($value);
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
		}
		if ($nbt->getInt("xpbottle", 0) !== 0) {
			$event->cancel();
			$value = $nbt->getInt("xpbottle");
			$formatted = number_format($value,1);
			$session->getPlayer()->sendMessage("§r§a§l+ $formatted xp");
			$session->getPlayer()->getWorld()->addSound($session->getPlayer()->getLocation(),new BlockBreakSound(VanillaBlocks::GLASS()));
			$session->getPlayer()->getXpManager()->addXp($value);
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
		}
		if($nbt->getInt("space",0) !== 0){
			if (isset($this->spaceChestCooldown[$player->getName()]) && microtime(true) - $this->spaceChestCooldown[$player->getName()] <= 15) {
				$delayMessage = round(15 - abs($this->spaceChestCooldown[$player->getName()] - microtime(true)), 2);
				$player->sendMessage("§r§c§l(!) §r§cYou cannot open another spacechest for $delayMessage SEC(s).");
				$event->cancel();
				return;
			}
			$value = $nbt->getInt("space");
			$inv = new SpaceChestInventory();
			$inv->openInventory($player, $value);
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			$this->spaceChestCooldown[$player->getName()] = microtime(true);
		}
	}

	/**
	 * @param PlayerItemUseEvent $event
	 */
	public function onInteract(PlayerItemUseEvent $event): void
	{
		$player = $event->getPlayer();
		$hand = $player->getInventory()->getItemInHand();
		$nbt = $hand->getNamedTag();
		if ($hand->getId() === ItemIds::ENCHANTED_BOOK) {
			$player->sendMessage("§r§e§l(§r§e!§e§l) §r§eTo apply this enchantment to an item, simply drag n' drop the book \n§r§eonto the item you'd like to enchant in your inventory!\n§r§7The §r§bSuccess Rate §r§7is the chance of the book succesfully being applied \n§r§7to your equipment. §r§7The §r§bDestruction Chance §r§7is the percent\n §r§7chance of your piece of equipment being §r§o§7DESTROYED §r§7if the book fails to apply.");
			return;
		}
		if($nbt->getString("armororb","") !== ""){
			$player->sendMessage("§r§e§l(!) §r§eTo apply this enchantment orb to an item, simply drag n' \n §r§edrop the orb onto the piece of equipment you'd like to \n §r§eincrease the enchantment slots of in your inventory!");
			return;
		}
		if($nbt->getString("weaponorb","") !== ""){
			$player->sendMessage("§r§e§l(!) §r§eTo apply this enchantment orb to an item, simply drag n' \n §r§edrop the orb onto the piece of equipment you'd like to \n §r§eincrease the enchantment slots of in your inventory!");
		}
	}

	/** @var array $message */
	public array $message = [];
	public function onItemRename(PlayerChatEvent $event): void
	{
		$player = $event->getPlayer();
		if (!isset($this->itemRenamer[$player->getName()])) {
			return;
		}

		$message = $event->getMessage();
		$hand = $player->getInventory()->getItemInHand();
		$event->cancel();
		if ($message === "cancel") {
			$player->sendMessage("§r§c§l** §r§cYou have unqueued your Itemtag for this usage.");
			Loader::playSound($player,"mob.enderdragon.flap",2);
			$player->getInventory()->addItem(Rewards::get(0, 1));
			unset($this->itemRenamer[$player->getName()]);
			if (isset($this->message[$player->getName()])) unset($this->message[$player->getName()]);
		}
		if ($event->getMessage() === "confirm" && isset($this->message[$player->getName()])) {
			$player->sendMessage("§r§e§l(!) §r§eYour ITEM has been renamed to: '{$this->message[$player->getName()]}§e'");
			$player->getLocation()->getWorld()->addSound($player->getLocation(),new XpLevelUpSound(100));
			$hand->setCustomName($this->message[$player->getName()]);
			$player->getInventory()->setItemInHand($hand);
			unset($this->itemRenamer[$player->getName()]);
			unset($this->message[$player->getName()]);
		}
		if (strlen($event->getMessage()) > 26) {
			$player->sendMessage("§r§cYour custom name exceeds the 36 character limit.");
			return;
		}
		if (!isset($this->message[$player->getName()]) && $event->getMessage() !== "cancel" &&  $event->getMessage() !== "confirm") {
			$formatted = TextFormat::colorize($message);
			$player->sendMessage("§r§e§l(!) §r§eItem Name Preview: $formatted");
			$player->sendMessage("§r§7Type '§r§aconfirm§7' if this looks correct, otherwise type '§ccancel§7' to start over.");
			$this->message[$player->getName()] = $formatted;
		}
	}

    /** @var array $messages */
	public array $messages = [];

	public function onLoreRename(PlayerChatEvent $event): void
	{
		$player = $event->getPlayer();
		if (!isset($this->lorerenamer[$player->getName()])) {
			return;
		}
		$message = $event->getMessage();
		$hand = $player->getInventory()->getItemInHand();
		$event->cancel();
		if ($message === "cancel") {
			$player->sendMessage("§r§c§l** §r§cYou have unqueued your Lore-Renamer for this usage.");
			Loader::playSound($player,"mob.enderdragon.flap",2);
			$player->getInventory()->addItem(Rewards::get(Rewards::ITEM_LORE_CRYSTAL, 1));
			unset($this->lorerenamer[$player->getName()]);
			if (isset($this->messages[$player->getName()])) unset($this->messages[$player->getName()]);
		}
		if ($event->getMessage() === "confirm" && isset($this->messages[$player->getName()])) {
			$player->sendMessage("§r§e§l(!) §r§eYour ITEM's lore has been set to: '{$this->messages[$player->getName()]}§e'");
			$player->getLocation()->getWorld()->addSound($player->getLocation(),new XpLevelUpSound(100));
			$lore = $hand->getLore();
			$lore[] = $this->messages[$player->getName()];
			$hand->setLore($lore);
			$player->getInventory()->setItemInHand($hand);
			unset($this->lorerenamer[$player->getName()]);
			unset($this->messages[$player->getName()]);
		}
		if (strlen($event->getMessage()) > 18) {
			$player->sendMessage("§r§cYour custom lore exceeds the 18 character limit.");
			return;
		}
		if (!isset($this->messages[$player->getName()]) && $event->getMessage() !== "cancel" &&  $event->getMessage() !== "confirm") {
			$formatted = TextFormat::colorize($message);
			$player->sendMessage("§r§e§l(!) §r§eItem Name Preview: $formatted");
			$player->sendMessage("§r§7Type '§r§aconfirm§7' if this looks correct, otherwise type '§ccancel§7' to start over.");
			$this->messages[$player->getName()] = $formatted;
		}
	}


	public function getLoader(): ?Loader{
		return $this->plugin;
	}
}