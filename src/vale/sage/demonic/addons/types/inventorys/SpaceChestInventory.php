<?php
namespace vale\sage\demonic\addons\types\inventorys;

use BlockHorizons\Fireworks\item\Fireworks;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\world\sound\ChestOpenSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\addons\types\inventorys\task\SpaceChestTask;
use vale\sage\demonic\Loader;
use vale\sage\demonic\Utils;

class SpaceChestInventory
{

	/** @var array|int[] $OUTSIDE_GRID */
	public static array $OUTSIDE_GRID = [
		0, 1, 2, 3, 4, 5, 6, 7, 8, 17, 26, 35, 45, 53, 52, 51, 50, 49, 48, 47, 46, 45, 44, 36, 27, 18, 9
	];

	/** @var array|int[] $INSIDE_GRID */
	public static array $INSIDE_GRID = [
		10, 11, 12, 13, 14, 15, 16, 19, 20, 21, 22, 23, 24, 25, 28, 29, 30, 31, 32, 33, 34, 37, 38, 39, 40, 41, 42, 43
	];

    /** @var array */
	public array $clicked = [];

    /** @var array */
	public static array $underTask = [];

    /** @var array */
	public array $slots = [
	];

    /** @var array */
	public static array $slotsz = [];

	/** @var array $eliteChest */
	public static array $eliteChest = [];

    /**
     * @param Player $player
     * @param int $rarity
     * @return void
     */
	public function openInventory(Player $player, int $rarity): void
	{
		if(isset(SpaceChestInventory::$eliteChest[$player->getName()])){
			unset(SpaceChestInventory::$eliteChest[$player->getName()]);
		}
		$player->getWorld()->addSound($player->getLocation(),new ChestOpenSound());
	    $this->setSpecificClicks($player,"elite");
	    self::$underTask[$player->getName()] = $player;
		$name = $this->getNameByRarity($rarity);
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
		$menu->setName($name);
		$this->fillGrid($menu, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS, $this->determinemeta($rarity), 1), "inside", $rarity);
		$this->fillGrid($menu, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, 7, 1), "outside", $rarity);
		$menu->send($player);
		$menu->setInventoryCloseListener(function () use ($player, $rarity){
			if(isset(self::$underTask[$player->getName()])){
				$player->sendMessage("You recieved rewards earlier coz u closed inv");
				unset(self::$underTask[$player->getName()]);
			}
			var_dump($rarity);
			if($rarity === 1) Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(),$player->getWorld(),0,0),Fireworks::COLOR_LIGHT_AQUA);
			if($rarity === 2) Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(),$player->getWorld(),0,0),Fireworks::COLOR_RED);
			$player->getLocation()->getWorld()->addSound($player->getLocation(), new XpLevelUpSound(100));
		});
		$menu->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction) use ($player, $menu, $rarity) {
			if (isset(self::$eliteChest[$player->getName()]) && self::$eliteChest[$player->getName()] < 5) {
				if ($transaction->getItemClicked()->getNamedTag()->getInt("reward", 0) !== 0) {
					$this->slots[] = $transaction->getAction()->getSlot();
					if (!in_array($player->getName(), self::$eliteChest)) {
						array_push(self::$eliteChest, $player->getName());
						self::$eliteChest[$player->getName()] = 0;
					} elseif (in_array($player->getName(), self::$eliteChest)) {
						self::$eliteChest[$player->getName()]++;
					}

					Loader::playSound($player, "random.levelup", 2);
					$this->setChest($menu, $transaction->getAction()->getSlot(), $rarity);
					return;
				}
			}
			if (isset(self::$eliteChest[$player->getName()]) && self::$eliteChest[$player->getName()] >= 4){
				Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpaceChestTask($menu,$player,$rarity,$this->slots),15);
			}
			if($transaction->getItemClicked()->getNamedTag()->getInt("tier",0) !== 0) {
				if (!isset(self::$underTask[$player->getName()])) {
					$tag = $transaction->getItemClicked()->getNamedTag()->getInt("tier");
					$reward = $this->handleReward($menu, $tag);
					$menu->getInventory()->setItem($transaction->getAction()->getSlot(), $reward[array_rand($reward)]);
					$player->getInventory()->addItem($menu->getInventory()->getItem($transaction->getAction()->getSlot()));
					Loader::playSound($player, "mob.enderdragon.flap", 2);
				}
			}
		}));
	}

    /**
     * @param Player $player
     * @param string $type
     * @return void
     */
	public function setSpecificClicks(Player $player, string $type){
		switch ($type){
			case "elite":
				if(!in_array($player->getName(), self::$eliteChest)){
					array_push(self::$slotsz, $player->getName());
					array_push(self::$eliteChest, $player->getName());
					self::$eliteChest[$player->getName()] = 0;
					self::$slotsz[$player->getName()][] = 99;
				}elseif(in_array($player->getName(), self::$eliteChest)){
					self::$eliteChest[$player->getName()] = 0;
				}
				break;
		}
	}



	/**
	 * @param InvMenu $menu
	 * @param Item $item
	 */
	public function fillGrid(InvMenu $menu, Item $item, string $type, int $rartity): void
	{
		$name = $this->getNameByRarity($rartity);
		$color = $this->getColorByRarity($rartity);
		switch ($type) {
			case "outside":
				foreach (self::$OUTSIDE_GRID as $id) {
					$menu->getInventory()->setItem($id, $item->setCustomName("§r§8' '"));
				}
				break;
			case "inside":
				$slot = 0;
				foreach (self::$INSIDE_GRID as $id) {
					$item->setCustomName("{$color}§l??? §r§7#" . $slot)->setLore($this->getLoreFromRarity($rartity));
					$item->getNamedTag()->setInt("reward", $rartity);
					$menu->getInventory()->setItem($id,$item);
					$slot++;
				}
				break;
		}
	}

    /**
     * @param InvMenu $menu
     * @param int $slot
     * @param int $rarity
     * @return void
     */
	public function setChest(InvMenu $menu, int $slot, int $rarity): void{
		switch ($rarity){
			case 1:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,1);
				$item->setCustomName("§r§b§lMystery Item #$slot");
				$item->setLore([
					'§r§7You have selected this mystery item'
				]);
				$item->getNamedTag()->setInt("tier", $rarity);
				$menu->getInventory()->setItem($slot, $item);
				break;
			case 2:
				$item = ItemFactory::getInstance()->get(ItemIds::CHEST,0,1);
				$item->setCustomName("§r§c§lMystery Item #$slot");
				$item->setLore([
					'§r§7You have selected this mystery item'
				]);
				$item->getNamedTag()->setInt("tier", $rarity);
				$menu->getInventory()->setItem($slot, $item);
				break;
		}
	}

    /**
     * @param InvMenu $menu
     * @param int $rarity
     * @return array
     */
	public function handleReward(InvMenu $menu, int $rarity): array{
		$rewards = [];
		switch ($rarity){
			case 1:
				$rewards = [
					VanillaItems::APPLE(),
					VanillaItems::COOKED_CHICKEN(),
					VanillaItems::HEART_OF_THE_SEA(),
					VanillaItems::STICK()
				];
				break;
			case 2:
				$rewards = [
					VanillaItems::DIAMOND_SWORD(),
					VanillaItems::DIAMOND(),
					VanillaItems::GOLDEN_AXE(),
					VanillaItems::DIAMOND_BOOTS(),
					VanillaItems::BROWN_BED()
				];
				break;
		}
		return $rewards;
	}

	/**
	 * @param int $rarity
	 * @return string
	 */
	public function getColorByRarity(int $rarity): string{
		$colorcode = "";
		if($rarity === 1) $colorcode = "§r§b";
		if($rarity === 2)  $colorcode = "§r§c";
		return $colorcode;
	}

	/**
	 * @param int $rarity
	 * @return string
	 */
	public function getNameByRarity(int $rarity): string{
		$name = "";
		switch ($rarity){
			case 1:
				$name = "Elite";
				break;
			case 2:
				$name = "Godly";
				break;
		}
		return $name;
	}

    /**
     * @param int $rartity
     * @return array|string[]
     */
	private function getLoreFromRarity(int $rartity): array{
		$array = [];
		switch ($rartity){
			case 1:
				$array = [
				"§r§7Choose §r§b5 mystery items §r§7and,",
				"§r§7your §r§b§lElite §r§7loot will be revealed."
				];
				break;
			case 2:
				$array = ["§r§7Choose §r§c5 mystery items §r§7and,",
				"§r§7your §r§c§lGodly §r§7loot will be revealed."
				];
				break;
		}
		return $array;
	}

    /**
     * @param int $rarity
     * @return int
     */
	private function determinemeta(int $rarity){
		$meta = 0;
		switch ($rarity){
			case 1:
				$meta = 3;
				break;
			case 2:
				$meta = 14;
				break;
		}
		return $meta;
	}
}