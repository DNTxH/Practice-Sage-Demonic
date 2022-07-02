<?php

declare(strict_types=1);

namespace vale\sage\demonic\commands\types\sender;

use CortexPE\Commando\args\BaseArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemIds;
use pocketmine\math\AxisAlignedBB;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use vale\sage\demonic\addons\types\envoys\Envoy;
use vale\sage\demonic\addons\types\warp\WarpForm;
use vale\sage\demonic\enchants\EnchantManager;
use vale\sage\demonic\Loader;

class WarpCommand extends BaseCommand
{

	public function prepare(): void
	{
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$menu = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
		$inv = $menu->getInventory();
		$menu->setName("Warps GUI");

		$grassPlainsPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $grassPlainsPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($grassPlainsPosition->getX() - 100, $grassPlainsPosition->getY() - 100, $grassPlainsPosition->getZ() - 100, $grassPlainsPosition->getX() + 100, $grassPlainsPosition->getY() + 100, $grassPlainsPosition->getZ() + 100));
		$grassPlainsCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$grassPlainsCount++;
			}
		}

		$snowyPlainsPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $snowyPlainsPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($snowyPlainsPosition->getX() - 100, $snowyPlainsPosition->getY() - 100, $snowyPlainsPosition->getZ() - 100, $snowyPlainsPosition->getX() + 100, $snowyPlainsPosition->getY() + 100, $snowyPlainsPosition->getZ() + 100));
		$snowyPlainsCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$snowyPlainsCount++;
			}
		}

		$icyHillsPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $icyHillsPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($icyHillsPosition->getX() - 100, $icyHillsPosition->getY() - 100, $icyHillsPosition->getZ() - 100, $icyHillsPosition->getX() + 100, $icyHillsPosition->getY() + 100, $icyHillsPosition->getZ() + 100));
		$icyHillsCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$icyHillsCount++;
			}
		}

		$endPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $endPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($endPosition->getX() - 100, $endPosition->getY() - 100, $endPosition->getZ() - 100, $endPosition->getX() + 100, $endPosition->getY() + 100, $endPosition->getZ() + 100));
		$endCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$endCount++;
			}
		}

		$netherPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $netherPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($netherPosition->getX() - 100, $netherPosition->getY() - 100, $netherPosition->getZ() - 100, $netherPosition->getX() + 100, $netherPosition->getY() + 100, $netherPosition->getZ() + 100));
		$netherCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$netherCount++;
			}
		}

		$desertPosition = new Position(100, 100, 100, Server::getInstance()->getWorldManager()->getWorldByName("world"));
		$entities = $desertPosition->getWorld()->getNearbyEntities(new AxisAlignedBB($desertPosition->getX() - 100, $desertPosition->getY() - 100, $desertPosition->getZ() - 100, $desertPosition->getX() + 100, $desertPosition->getY() + 100, $desertPosition->getZ() + 100));
		$desertCount = 0;
		foreach($entities as $entity){
			if($entity instanceof Player){
				$desertCount++;
			}
		}

		for($i = 0; $i <= 26; $i++){
			if(in_array($i, [0, 1, 7, 8, 9, 17, 18, 19, 25, 26])){
				$inv->setItem($i, EnchantManager::glint(VanillaBlocks::STAINED_GLASS()->setColor(DyeColor::BLUE())->asItem()));
			}elseif(in_array($i, [10, 11, 12, 13, 14, 15, 16])){
				$plainWarpsCoords =  "§r§e1041x,864z";
				$clicktoteleport = "§r§7Click to teleport to this warp";
				$inv->setItem(10, VanillaBlocks::GRASS()->asItem()->setCustomName("§r§a§lPlains /warp §r§f(#0012/0)")->setLore([
					$plainWarpsCoords,
					strval($grassPlainsCount),
					'',
					"§aA nice place to get your loot",
					'§r§aand run from everyone',
					'',
					$clicktoteleport
				]));
				$desertWarpCoords =  "§r§e1041x,864z";
				$inv->setItem(11, VanillaBlocks::SAND()->asItem()->setCustomName("§r§e§lDesert /warp §r§f(#0013/0)")->setLore([
					$desertWarpCoords,
					strval($desertCount),
					'',
					"§r§eA nice place to chill",
					'§r§eand afk or something idk',
					'',
					$clicktoteleport
				]));
				$snowCoords =  "§r§e1041x,864z";
				$inv->setItem(12, VanillaBlocks::SNOW()->asItem()->setCustomName("§r§b§lSnowyPlains /warp §r§f(#0013/0)")->setLore([
					$snowCoords,
					strval($snowyPlainsCount),
					'',
					"§r§bA nice place to chill",
					'§r§band afk or something idk',
					'',
					$clicktoteleport
				]));
				$inv->setItem(13, VanillaBlocks::BEACON()->asItem()->setCustomName("§l§dGuide Menu")->setLore([
					#todo online player count -> strval($playercount),
					"§8:: §5Warps Guide §8::",
					"§7Welcome to the Warp Menu as you can see",
					"§7There are 6 warps you can choose to teleport to",
					"§7Teleporting is really simple and easy here's the steps.",
					" ",
					"§71. §cClick/Tap §7the warp you would like to teleport to",
					"§7If you have simply clicked or tapped the §4wrong §7location",
					"§7You'll be able to §amove §7in time unless you have §axp §fon you",
					" ",
					"§7(§4!§7) If there are any bugs within this system please report it",
					"§4§lFailure §r§7to do so will result in a account §4§lTermination§r"
				]));
				$icyCoords =  "§r§e1041x,864z";
				$inv->setItem(14, VanillaBlocks::PACKED_ICE()->asItem()->setCustomName("§r§3§lIcyHills /warp §r§f(#0015/0)")->setLore([
					$icyCoords,
					strval($icyHillsCount),
					'',
					"§r§3A nice place to chill",
					'§r§3and afk or something idk',
					'',
					$clicktoteleport
				]));
				$endCoords =  "§r§e1041x,864z";
				$inv->setItem(15, VanillaBlocks::END_STONE()->asItem()->setCustomName("§r§d§lEnd /warp §r§f(#0016/0)")->setLore([
					$endCoords,
					strval($endCount),
					'',
					"§r§dA nice place to chill",
					'§r§dand afk or something idk',
					'',
					$clicktoteleport
				]));
				$netherCoords =  "§r§e1041x,864z";
				$inv->setItem(16, VanillaBlocks::NETHERRACK()->asItem()->setCustomName("§r§4§lNether /warp §r§f(#0017/0)")->setLore([
					$netherCoords,
					strval($netherCount),
					'',
					"§r§4A nice place to chill",
					'§r§4and afk or something idk',
					'',
					$clicktoteleport
				]));
			}else{
				$inv->setItem($i, EnchantManager::glint(VanillaBlocks::STAINED_GLASS()->setColor(DyeColor::BLACK())->asItem()));
			}
		}
		foreach ($inv->getContents() as $slot => $item){
				if($item->getId() === ItemIds::STAINED_GLASS){
					$item->setCustomName("''");
					$menu->getInventory()->setItem($slot, $item);
				}
		}
		$menu->send($sender);
		$menu->setListener(InvMenu::readonly());
		Loader::playSound($sender, "mob.endermen.portal");
	}
}