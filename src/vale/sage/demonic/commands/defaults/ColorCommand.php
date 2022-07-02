<?php

namespace vale\sage\demonic\commands\defaults;

use vale\sage\demonic\Loader;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as C;
use pocketmine\color\Color;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\block\BlockLegacyIds;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class ColorCommand extends Command
{
    public function __construct() {
        parent::__construct("color");
        $this->setAliases(["changecolor"]);
        $this->setDescription("Change your leather armor's color!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
			return;
		}
		$menu = InvMenu::create(InvMenu::TYPE_CHEST);
		$menu->setName("Change Armor Color");
		$menu->getInventory()->setContents([
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 14, 1)->setCustomName(C::RESET . C::WHITE . "Red")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # red
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 1, 1)->setCustomName(C::RESET . C::WHITE . "Orange")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # orange
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 4, 1)->setCustomName(C::RESET . C::WHITE . "Yellow")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # yellow
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 5, 1)->setCustomName(C::RESET . C::WHITE . "Green")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # green
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 11, 1)->setCustomName(C::RESET . C::WHITE . "Blue")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # blue
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 10, 1)->setCustomName(C::RESET . C::WHITE . "Purple")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # purple
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 8, 1)->setCustomName(C::RESET . C::WHITE . "Gray")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # gray
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 12, 1)->setCustomName(C::RESET . C::WHITE . "Brown")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # brown
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 0, 1)->setCustomName(C::RESET . C::WHITE . "White")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # white
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 3, 1)->setCustomName(C::RESET . C::WHITE . "Aqua")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # aqua
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 13, 1)->setCustomName(C::RESET . C::WHITE . "Dark Green")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # dark green
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 15, 1)->setCustomName(C::RESET . C::WHITE . "Black")->setLore([C::RESET . C::GRAY . "Click to change color!"]), # black
			ItemFactory::getInstance()->get(BlockLegacyIds::WOOL, 6, 1)->setCustomName(C::RESET . C::WHITE . "Pink")->setLore([C::RESET . C::GRAY . "Click to change color!"]) # pink
		]);
		$menu->setListener(InvMenu::readonly(function(DeterministicInvMenuTransaction $transaction) : void
		{
			$sender = $transaction->getPlayer();
			$item = $transaction->getItemClicked();
			$hand = $sender->getInventory()->getItemInHand();
			$inv = $transaction->getAction()->getInventory();
			$armors = array(
				VanillaItems::LEATHER_BOOTS(),
				VanillaItems::LEATHER_CAP(),
				VanillaItems::LEATHER_TUNIC(),
				VanillaItems::LEATHER_PANTS()
			);
			if (!in_array($hand, $armors)) {
				$sender->removeCurrentWindow();
				$sender->sendMessage(Loader::PERM_PREFIX . "You must have a leather armor in your hand!");
				return;
			}
			$color = $this->getColor($item->getMeta());
			$hand->setCustomColor($color);
			$sender->getInventory()->setItemInHand($hand);
			self::sound($sender);
			$sender->removeCurrentWindow();
		}));
		$menu->send($sender);
		return;
    }
	
	public static function sound(Player $player) {
		$packet = new PlaySoundPacket();
		$packet->soundName = "armor.equip_leather";
		$packet->x = $player->getPosition()->getX();
		$packet->y = $player->getPosition()->getY();
		$packet->z = $player->getPosition()->getZ();
		$packet->volume = 1;
		$packet->pitch = 1;
		$player->getNetworkSession()->sendDataPacket($packet);
	}
	
	public function getColor(int $meta) : Color {
		$color = match($meta) {
			14 => new Color(255, 0, 0),
			1 => new Color(255, 128, 0),
			4 => new Color(255, 255, 0),
			5 => new Color(0, 255, 0),
			11 => new Color(0, 0, 255),
			10 => new Color(127, 0, 255),
			8 => new Color(128, 128, 128),
			12 => new Color(51, 25, 0),
			0 => new Color(255, 255, 255),
			3 => new Color(0, 255, 255),
			13 => new Color(0, 102, 0),
			15 => new Color(0, 0, 0),
			6 => new Color(255, 204, 229),
		};
		
		return $color;
	}
}
