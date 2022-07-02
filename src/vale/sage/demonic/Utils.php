<?php
namespace vale\sage\demonic;

use BlockHorizons\Fireworks\item\Fireworks;
use BlockHorizons\Fireworks\entity\FireworksRocket;
use muqsit\invmenu\InvMenu;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

class Utils
{
	public static int $default = 0;
	/**
	 * @param Location $pos
	 * @param $color
	 * @param int $type
	 */
	public static function spawnFirework(Location $pos, $color, int $type = Fireworks::TYPE_SMALL_SPHERE)
	{
		$fw = new Fireworks(new ItemIdentifier(ItemIds::FIREWORKS,0));
		$fw->addExplosion(Fireworks::TYPE_HUGE_SPHERE, $color, "", false, false);
		$fw->setFlightDuration(1);
		$entity = new FireworksRocket($pos, $fw);
		$entity->spawnToAll();
	}

    /**
     * @return string
     */
	public static function sendRankPercentages(): string{
		$online = count(Server::getInstance()->getOnlinePlayers());
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			if($session->getRank() === 0){
				self::$default++;
			}
			$cp = self::$default / count(Server::getInstance()->getOnlinePlayers()) * 100;
			$currentplayers = round($cp, 1);
			$defaut = self::$default;
			$message = "§r§f* Trainee§r§7: §r§b{$defaut} §r§7[§r§7{$currentplayers}%§r§7]";
		}
		return $message;
	}

	/**
	 * @param Location $location
	 */
	public static function Lightning(Location $location): void
	{
		$light = new AddActorPacket();
		$light->type = "minecraft:lightning_bolt";
		$light->actorUniqueId = $light->actorRuntimeId = Entity::nextRuntimeId();
		$light->metadata = [];
		$light->motion = null;
		$light->yaw = $location->getYaw();
		$light->pitch = $location->getPitch();
		$light->position = new Vector3($location->getX(), $location->getY(), $location->getZ());
		$sound = new PlaySoundPacket();
		$sound->soundName = "ambient.weather.thunder";
		$sound->x = $location->getX();
		$sound->y = $location->getY();
		$sound->z = $location->getZ();
		$sound->volume = 0.50;
		$sound->pitch = 1;
		$players = Server::getInstance()->getOnlinePlayers();
		foreach ($players as $player){
			$player->getNetworkSession()->sendDataPacket($light);
			$player->getNetworkSession()->sendDataPacket($sound);
		}
	}


    /**
     * @param Player $player
     */
	public static function sendJoinMessage(Player $player){
		$buycraft = Loader::BUYCRAFT;
		$player->sendMessage("    §r§fWelcome, §r§e§l{$player->getName()} §r§fto §6§lSage§ePvP!");
		$player->sendMessage("§r§7''Explore our galaxy and realms we have to offer.''");
		$player->sendMessage("§8                                                             §6");
		$player->sendMessage("§r§e§lSTORE: §r§f{$buycraft}");
		$player->sendMessage(" §r§6§lDISCORD: §r§fdiscord.gg/sagepvp");
		$player->sendMessage("  §r§e§lTWITTER: §r§f@SagePvP");
		$player->sendMessage("§8                                                             §6");
		$player->sendMessage("§r§7§o((October Crate, Halloween GKit, and Spooky Bundle released! \n §r§7§o))");
		$player->sendMessage("§eYou are now connected to the §6Factions Realm!\n");
		$naesua = new EffectInstance(VanillaEffects::NAUSEA(), 20 * 7, 2);
		$player->getEffects()->add($naesua);
		$player->sendMessage(TextFormat::colorize("&r&e&l(!) §r§ePlease move foward to initate commands on the server&6."));
		$player->sendMessage("§r§7We do this in order to prevent players from duping.");
	}

	/**
	 * @return Vector3
	 */
	public static function getRandomVector() : Vector3
	{
		$x = 0; $y = 0; $z = 0;
		$x = rand()/getrandmax() * 2 - 1;
		$y = rand()/getrandmax() * 2 - 1;
		$z = rand()/getrandmax() * 2 - 1;
		$v = new Vector3($x, $y, $z);
		return $v->normalize();
	}

	/**
	 * @param Player $player
	 * @return bool
	 */
	public static function isOnline(Player $player): bool{
		return $player instanceof Player;
	}

	/**
	 * @param int $slot
	 * @param InvMenu $menu
	 * @param Player $player
	 */
	public static function animateBySlot(int $slot, InvMenu $menu, Player $player)
	{
		switch ($slot) {
			case 32:
				$toSet = [5, 27, 28, 29, 35, 34, 33, 41, 50];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 31:
				$toSet = [4, 40, 38, 37, 36, 42, 43, 44];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 30:
				$toSet = [3, 29, 28, 27, 33, 34, 35, 39, 48];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 23:
				$toSet = [24, 25, 26, 20, 19, 18, 41, 50, 5];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 22:
				$toSet = [40, 20, 19, 18, 24, 25, 26, 5];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 21:
				$toSet = [3, 20, 19, 18, 24, 25, 26, 5, 39, 48];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 14:
				$toSet = [5, 15, 16, 17, 11, 10, 9, 41, 50];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
			case 13:
				$toSet = [4, 11, 10, 9, 48, 15, 16, 17];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;

			case 12:
				$toSet = [3, 11, 10, 9, 39, 48, 15, 16, 17];
				foreach ($toSet as $set) {
					$menu->getInventory()->setItem($set, ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE, rand(1, 15)));
					Loader::playSound($player, "note.bell", 1, 5);
				}
				break;
		}
	}

	public static function postoString(Position $position): string{
		return "$position->x:$position->y:$position->z";
	}

	public static function strToPos(string $pos): Position{
		$ex = explode(":", $pos);
		return new Position($ex[0],$ex[1],$ex[2],Server::getInstance()->getWorldManager()->getDefaultWorld());
	}

    /**
     * @param Player $player
     * @param Item $item
     */
	public static function addItem(Player $player, Item $item): void{
		if(!$player->getInventory()->canAddItem($item)){
			$player->getWorld()->dropItem($player->getLocation(),$item);
			Loader::playSound($player, "random.pop");
		}else{
			$player->getInventory()->addItem($item);
		}
	}
}