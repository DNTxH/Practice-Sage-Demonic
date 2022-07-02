<?php

declare(strict_types=1);

namespace vale\sage\demonic\enchants\factory;

use BlockHorizons\Fireworks\item\Fireworks;
use pocketmine\entity\Location;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\Armor;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\Tool;
use pocketmine\network\mcpe\protocol\InventoryContentPacket;
use pocketmine\network\mcpe\protocol\InventorySlotPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\MobEquipmentPacket;
use pocketmine\network\mcpe\protocol\types\inventory\ItemStackWrapper;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\HugeExplodeSeedParticle;
use pocketmine\world\sound\AnvilFallSound;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\XpCollectSound;
use pocketmine\world\sound\XpLevelUpSound;
use vale\sage\demonic\enchants\enchantments\type\heroic\HeroicCustomEnchant;
use vale\sage\demonic\enchants\factory\EnchantFactory;
use vale\sage\demonic\enchants\CustomEnchant;
use vale\sage\demonic\enchants\enchantments\EnchantmentsManager;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;
use vale\sage\demonic\Utils;

class EnchantFactoryListener implements Listener
{

	/**
	 * @param InventoryTransactionEvent $event
	 * @priority HIGH
	 * @ignoreCancelled true
	 */
	public function onEnchantOrb(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = $transaction->getActions();
		$oldToNew = isset(array_keys($actions)[0]) ? $actions[array_keys($actions)[0]] : null;
		$newToOld = isset(array_keys($actions)[1]) ? $actions[array_keys($actions)[1]] : null;
		if ($oldToNew instanceof SlotChangeAction && $newToOld instanceof SlotChangeAction) {
			$itemClicked = $newToOld->getSourceItem();
			$itemClickedWith = $oldToNew->getSourceItem();
			if (($orb = $itemClickedWith->getNamedTag()->getString("armororb", "") !== "") && $itemClicked->getId() !== ItemIds::AIR && $itemClickedWith->getCount() === 1) {
				if (!$itemClicked instanceof Armor) {
					return;
				}
				$lore = $itemClicked->getLore();
				if ($itemClicked->getNamedTag()->getInt("orb", 0) !== 0) {
					if ($itemClickedWith->getNamedTag()->getInt("orb") <= $itemClicked->getNamedTag()->getInt("orb")) {
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cThe current orb you are trying to apply is less then or equal to the current orb on the item!");
						$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
						$event->cancel();
						return;
					}
				}
				if ($itemClicked->getNamedTag()->getInt("orb", 0) !== 0) {
					if ($itemClicked->getNamedTag()->getInt("orb") >= 12) {
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cArmor Orbs can only apply up to 12 slots!");
						$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
						return;
					}
				}
				if ($itemClickedWith->getNamedTag()->getInt("orb", 0) !== 0) {
					foreach ($lore as $key => $line) {
						if (str_contains($line, " Enchantment Slots")) {
							unset($lore[$key]);
							break;
						}
					}
				}

				$lore[] = "\n" . TextFormat::RESET . TextFormat::BOLD . TextFormat::GREEN . "+ " . $itemClickedWith->getNamedTag()->getInt("orb") . " Enchantment Slots";
				$itemClicked->setLore($lore);
				$itemClicked->getNamedTag()->setInt("orb", $itemClickedWith->getNamedTag()->getInt("orb"));
				$newToOld->getInventory()->setItem($newToOld->getSlot(), $itemClicked);
				$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new XpLevelUpSound(100));

				$event->cancel();
				$oldToNew->getInventory()->setItem($oldToNew->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));

			}
			if (($orb = $itemClickedWith->getNamedTag()->getString("weaponorb", "") !== "") && $itemClicked->getId() !== ItemIds::AIR && $itemClickedWith->getCount() === 1) {
				if (!$itemClicked instanceof Tool) {
					return;
				}
				$lore = $itemClicked->getLore();
				if ($itemClicked->getNamedTag()->getInt("orb", 0) !== 0) {
					if ($itemClickedWith->getNamedTag()->getInt("orb") <= $itemClicked->getNamedTag()->getInt("orb")) {
						$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cThe current orb you are trying to apply is less then or equal to the current orb on the item!");
						$event->cancel();
						return;
					}
				}
				if ($itemClicked->getNamedTag()->getInt("orb", 0) !== 0) {
					if ($itemClicked->getNamedTag()->getInt("orb") >= 12) {
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cWeapon Orbs can only apply up to 12 slots!");
						$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
						return;
					}
				}
				if ($itemClickedWith->getNamedTag()->getInt("orb", 0) !== 0) {
					foreach ($lore as $key => $line) {
						if (str_contains($line, " Enchantment Slots")) {
							unset($lore[$key]);
							break;
						}
					}
				}

				$lore[] = "\n" . TextFormat::RESET . TextFormat::BOLD . TextFormat::GREEN . "+ " . $itemClickedWith->getNamedTag()->getInt("orb") . " Enchantment Slots";
				$itemClicked->setLore($lore);
				$itemClicked->getNamedTag()->setInt("orb", $itemClickedWith->getNamedTag()->getInt("orb"));
				$newToOld->getInventory()->setItem($newToOld->getSlot(), $itemClicked);
				$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new XpLevelUpSound(100));

				$event->cancel();
				$oldToNew->getInventory()->setItem($oldToNew->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));

			}
		}
	}


	/**
	 * @param InventoryTransactionEvent $event
	 * @priority HIGH
	 * @ignoreCancelled true
	 */
	public function onRandomizer(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = $transaction->getActions();
		$oldToNew = isset(array_keys($actions)[0]) ? $actions[array_keys($actions)[0]] : null;
		$newToOld = isset(array_keys($actions)[1]) ? $actions[array_keys($actions)[1]] : null;
		if ($oldToNew instanceof SlotChangeAction && $newToOld instanceof SlotChangeAction) {
			$itemClicked = $newToOld->getSourceItem();
			$itemClickedWith = $oldToNew->getSourceItem();
			if ($itemClickedWith->getNamedTag()->getString("randomizer", "") !== "" && $itemClicked->getId() === ItemIds::ENCHANTED_BOOK && $itemClickedWith->getCount() === 1) {
				if (count($itemClicked->getEnchantments()) < 1) {
					return;
				}
				if ($itemClickedWith->getNamedTag()->getInt("type", 0) === 0) {
					return;
				}
				foreach ($itemClicked->getEnchantments() as $itemClickedEnchants) {
					if ($itemClickedEnchants->getType()->getRarity() !== $itemClickedWith->getNamedTag()->getInt("type")) {
						$type = EnchantmentsManager::translateId($itemClickedEnchants->getType()->getRarity());
						$transaction->getSource()->sendMessage("§r§c§l(!) §r§cYou need a(n) {$type} rarity scroll to reroll that book.");
						$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asVector3(), new AnvilFallSound());
						return;
					}
					$arr = $itemClicked->getLore();
					foreach ($arr as $key => $data) {
						unset($arr[$key]);
						$itemClicked->setLore($arr);
					}
					$itemClicked->getNamedTag()->setInt("success", rand(1, 100));
					$itemClicked->getNamedTag()->setInt("destroy", rand(1, 100));
					EnchantFactory::setEnchantmentLore($itemClicked);
					$newToOld->getInventory()->setItem($newToOld->getSlot(), $itemClicked);
					$event->cancel();
					$oldToNew->getInventory()->setItem($oldToNew->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
					$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new XpCollectSound());
				}
			}
		}
	}


	/**
	 * @priority HIGHEST
	 */
	public function onWhiteScroll(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				$ids = [276, 279];
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() !== ItemIds::AIR && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR && in_array($itemClicked->getId(), $ids) && $itemClickedWith->getCount() === 1 && $itemClickedWith->getNamedTag()->getString("whitescroll", "") !== "") {
					if ($itemClicked->getNamedTag()->getString("protected", "") !== "") {
						$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new AnvilFallSound());
						return;
					}
					$event->cancel();
					$lore = $itemClicked->getLore();
					$lore[] = TextFormat::RESET . TextFormat::BOLD . TextFormat::WHITE . "PROTECTED";
					$itemClicked->setLore($lore);
					$itemClicked->getNamedTag()->setString("protected", "true");
					$action->getInventory()->setItem($action->getSlot(), $itemClicked);
					$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
					$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new XpLevelUpSound(100));
					return;
				}
			}
		}
	}

	public function onRedeemCe(PlayerItemUseEvent $event): void
	{
		$player = $event->getPlayer();
		$hand = $player->getInventory()->getItemInHand();
		$nbt = $hand->getNamedTag();
		$success = mt_rand(0, 100);
		$destroy = 100 - $success;
		if ($nbt->getString("elitebook", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			EnchantFactory::giveEnchantBook($player, "elite", true, $success, $destroy);
			Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_LIGHT_AQUA);
		}
		if ($nbt->getString("masterybook", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			EnchantFactory::giveEnchantBook($player, "mastery", true, rand(1, 100), rand(1, 100));
			Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_DARK_PURPLE);
		}
		if ($nbt->getString("ultimatebook", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			EnchantFactory::giveEnchantBook($player, "ultimate", true, rand(1, 100), rand(1, 100));
			Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_YELLOW);
		}
		if ($nbt->getString("simplebook", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			EnchantFactory::giveEnchantBook($player, "simple", true, rand(1, 100), rand(1, 100));
			Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_GRAY);
		}
		if ($nbt->getString("legendarybook", "") !== "") {
			$hand->setCount($hand->getCount() - 1);
			$player->getInventory()->setItemInHand($hand);
			EnchantFactory::giveEnchantBook($player, "legendary", true, rand(1, 100), rand(1, 100));
			Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_GOLD);
		}
        if ($nbt->getString("godlybook", "") !== "") {
            $hand->setCount($hand->getCount() - 1);
            $player->getInventory()->setItemInHand($hand);
            EnchantFactory::giveEnchantBook($player, "godly", true, rand(1, 100), rand(1, 100));
            Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_GOLD);
        }
        if ($nbt->getString("heroicbook", "") !== "") {
            $hand->setCount($hand->getCount() - 1);
            $player->getInventory()->setItemInHand($hand);
            EnchantFactory::giveEnchantBook($player, "heroic", true, rand(1, 100), rand(1, 100));
            Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_GOLD);
        }
        if ($nbt->getString("uniquebook", "") !== "") {
            $hand->setCount($hand->getCount() - 1);
            $player->getInventory()->setItemInHand($hand);
            EnchantFactory::giveEnchantBook($player, "unique", true, rand(1, 100), rand(1, 100));
            Utils::spawnFirework(new Location($player->getLocation()->getX(), $player->getLocation()->getY() + 1.5, $player->getLocation()->getZ(), $player->getWorld(), 0, 0), Fireworks::COLOR_GOLD);
        }
	}


	/**
	 * @priority HIGHEST
	 */
	public function onArmorCrystal(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				$ids = [276, 279];
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() === ItemIds::NETHER_STAR && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR && in_array($itemClicked->getId(), $ids) && $itemClickedWith->getCount() === 1 && $itemClickedWith->getNamedTag()->getString("armorcrystal", "") !== "") {
					if ($itemClicked->getNamedTag()->getString("crystaled", "") !== "") {
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cYou are not skilled enough to add another crystal to this item.");
						$event->getTransaction()->getSource()->sendMessage("§r§7Purchase a Rank or use an Crystal Extractor!");
						$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new AnvilFallSound());
						return;
					}
					$event->cancel();
					$lore = $itemClicked->getLore();
					$lore[] = EnchantFactory::determineCrystalLore($itemClickedWith->getNamedTag()->getInt("crystaltier"));
					$itemClicked->setLore($lore);
					$itemClicked->getNamedTag()->setString("crystaled", "true");
					$itemClicked->getNamedTag()->setInt("crystaltier", $itemClickedWith->getNamedTag()->getInt("crystaltier"));
					$action->getInventory()->setItem($action->getSlot(), $itemClicked);
					$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
					$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new XpLevelUpSound(100));
					return;
				}
			}
		}
	}

	/**
	 * @priority HIGHEST
	 */
	public function onExtractor(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				$ids = [276, 279];
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() === ItemIds::GHAST_TEAR && $itemClickedWith->getCount() === 1 && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR&& in_array($itemClicked->getId(), $ids) && $itemClickedWith->getNamedTag()->getString("crystalextractor", "") !== "") {
					if ($itemClicked->getNamedTag()->getString("crystaled", "") === "") {
						$event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cThis item does not have a crystal applied to it.");
						$event->getTransaction()->getSource()->sendMessage("§r§7You can find crystals from the black market or lootboxes.");
						$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new AnvilFallSound());
						return;
					}
					$event->cancel();
					$lores = $itemClicked->getLore();
					$tag = $itemClicked->getNamedTag()->getInt("crystaltier");
					$string = EnchantFactory::determineCrystalLore($tag);
					unset($lores[array_search($string, $lores)]);
					$itemClicked->setLore($lores);
					$itemClicked->getNamedTag()->removeTag("crystaled");
					$itemClicked->getNamedTag()->removeTag("crystaltier");
					Server::getInstance()->broadcastMessage($itemClicked->getName());
					$transaction->getSource()->getInventory()->addItem(Rewards::getArmorCrystal($tag, 1));
					$action->getInventory()->setItem($action->getSlot(), $itemClicked);
					$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
					$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new AnvilUseSound());
					return;
				}
			}
		}
	}


	/**
	 * @priority HIGHEST
	 */
	public function onCEDUST(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() !== ItemIds::ENCHANTED_BOOK && ($itemClicked = $action->getSourceItem())->getId() === ItemIds::ENCHANTED_BOOK && $itemClickedWith->getCount() === 1 && $itemClickedWith->getNamedTag()->getString("dustadder", "") !== "") {
					if (count($itemClicked->getEnchantments()) < 1) {
						return;
					}
					foreach ($itemClicked->getEnchantments() as $itemClickedEnchants) {
						if ($itemClickedEnchants->getType()->getRarity() !== $itemClickedWith->getNamedTag()->getInt("dustrarity")) {
							$type = EnchantmentsManager::translateId($itemClickedEnchants->getType()->getRarity());
							$transaction->getSource()->sendMessage("§r§c§l(!) §r§cYou need a(n) {$type} rarity dust for this book.");
							$transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asVector3(), new AnvilFallSound());
							return;
						}

						$event->cancel();
						$tag = $itemClickedWith->getNamedTag()->getInt("dust");
						$success = $itemClicked->getNamedTag()->getInt("success");
						if($tag + $success > 100) {
							return;
						}
						$arr = $itemClicked->getLore();
						foreach ($arr as $key => $data) {
							unset($arr[$key]);
							$itemClicked->setLore($arr);
						}
						$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
						$tag = $itemClickedWith->getNamedTag()->getInt("dust");
						$success = $itemClicked->getNamedTag()->getInt("success");
						$itemClicked->getNamedTag()->setInt("success", $success + $tag);
						EnchantFactory::setEnchantmentLore($itemClicked);
						$action->getInventory()->setItem($action->getSlot(), $itemClicked);
						$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new XpLevelUpSound(100));
					}
				}
			}
		}
	}

	/**
	 * @priority HIGHEST
	 */
	public function onTransmong(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				$ids = [276, 279];
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() === ItemIds::PAPER && $itemClickedWith->getCount() === 1 && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR && in_array($itemClicked->getId(), $ids) && $itemClickedWith->getNamedTag()->getString("transmong", "") !== "") {
					if (count($itemClicked->getEnchantments()) < 1) {
						return;
					}
					$event->cancel();
					$enchants = EnchantmentsManager::prioritize($itemClicked->getEnchantments());
					$itemClicked->removeEnchantments();
					foreach ($enchants as $enchant) {
						$itemClicked->addEnchantment($enchant);
					}
					$amount = count($enchants);
					if($itemClicked->hasCustomName()){
					    $itemClicked->setCustomName($itemClicked->getCustomName() . " §r§d§l[§r§b§l{$amount}§r§d§l]");
					}
					if(!$itemClicked->hasCustomName()) {
						$itemClicked->setCustomName($itemClicked->getName() . " §r§d§l[§r§b§l{$amount}§r§d§l]");
					}
					$action->getInventory()->setItem($action->getSlot(), $itemClicked);
					$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
					$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new AnvilUseSound(100));
				}
			}
		}
	}


	/**
	 * @priority HIGHEST
	 */
	public function onBlackScroll(InventoryTransactionEvent $event): void
	{
		$transaction = $event->getTransaction();
		$actions = array_values($transaction->getActions());
		if (count($actions) === 2) {
			foreach ($actions as $i => $action) {
				$ids = [276, 279];
				if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() !== ItemIds::ENCHANTED_BOOK && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR && $itemClicked->getId() !== ItemIds::ENCHANTED_BOOK && in_array($itemClicked->getId(), $ids) && $itemClickedWith->getCount() === 1 && $itemClickedWith->getNamedTag()->getString("blackscroll", "") !== "") {
					$enchantmentSuccessful = false;
					$enchants = $itemClicked->getEnchantments();
					if (empty($enchants)) {
						return;
					}
					$removed = $enchants[array_rand($enchants)];
					if ($removed instanceof EnchantmentInstance) {
						if (!$removed->getType() instanceof CustomEnchant) {
							return;
						}
						$id = $removed->getType()->getName();
						$level = $removed->getLevel();
						$itemClicked->removeEnchantment(EnchantmentsManager::getEnchantmentByName($id));
						$action->getInventory()->setItem($action->getSlot(), $itemClicked);
						$enchantmentSuccessful = true;
					}
					if ($enchantmentSuccessful) {
						$event->cancel();
						$otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
						$inv = $otherAction->getInventory();
						$book = ItemFactory::getInstance()->get(ItemIds::ENCHANTED_BOOK);
						$book->addEnchantment(new EnchantmentInstance(EnchantmentsManager::getEnchantmentByName($id), $removed->getLevel()));
						$tag = $itemClickedWith->getNamedTag()->getInt("percentage");
						$book->getNamedTag()->setInt("success", $tag);
						$book->getNamedTag()->setInt("destroy", mt_rand(0, 40));
						$rarity = EnchantmentsManager::getColor($removed->getType()->getRarity());
                        $level = $removed->getLevel();
						$book->setCustomName("§r§l{$rarity}{$removed->getType()->getName()} " . EnchantmentsManager::roman($level));
						EnchantFactory::setEnchantmentLore($book);
						$inv->addItem($book);
						$transaction->getSource()->getWorld()->addSound($transaction->getSource()->getLocation(), new XpLevelUpSound(100));
					}
				}
			}
		}
	}

    /**
     * @priority HIGHEST
     */
    public function onTransaction(InventoryTransactionEvent $event): void
    {
        $transaction = $event->getTransaction();
        $actions = array_values($transaction->getActions());
        if (count($actions) === 2) {
            foreach ($actions as $i => $action) {
                if ($action instanceof SlotChangeAction && ($otherAction = $actions[($i + 1) % 2]) instanceof SlotChangeAction && ($itemClickedWith = $action->getTargetItem())->getId() === ItemIds::ENCHANTED_BOOK && ($itemClicked = $action->getSourceItem())->getId() !== ItemIds::AIR) {
                    if (count($itemClickedWith->getEnchantments()) < 1) return;
                    $enchantmentSuccessful = false;
                    foreach ($itemClickedWith->getEnchantments() as $enchantment) {
                        $ids = [ItemIds::SUGAR,ItemIds::GLOWSTONE_DUST];
                        if(in_array($itemClicked->getId(),$ids)){
                            return;
                        }
                        if($itemClicked->getId() === ItemIds::ENCHANTED_BOOK){
                            return;
                        }
                        if ($itemClicked->getNamedTag()->getString("randomizer", "") !== "")  {
                            return;
                        }
                        $currentCe = count($itemClicked->getEnchantments());
                        $limit = 8;
                        if ($itemClicked->getNamedTag()->getInt("orb", 0) !== 0) {
                            $limit += $itemClicked->getNamedTag()->getInt("orb");
                        }
                        if ($currentCe >= $limit) {
                            $event->getTransaction()->getSource()->sendMessage("§r§c§l(!) §r§cYou are not skilled enough to add another enchantment to this item.");
                            $event->getTransaction()->getSource()->sendMessage("§r§7Purchase a Rank (or use an Enchantment Orb) to increase the max slots!");
                            $transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
                            Server::getInstance()->broadcastMessage("LIMIT: $limit");
                            return;
                        }

                        $type = $enchantment->getType();

                        if($type instanceof HeroicCustomEnchant) {
                            if(!$itemClicked->hasEnchantment(EnchantmentsManager::getEnchantment($type->getChildId()))) {
                                $transaction->getSource()->sendMessage("§r§c§l(!) §r§cYou require the custom enchant " . EnchantmentsManager::getEnchantment($type->getChildId())->getName() . " at max level in order to apply this enchantment!");
                                return;
                            }

                            if(!$itemClicked->getEnchantment(EnchantmentsManager::getEnchantment($type->getChildId()))->getLevel() < EnchantmentsManager::getEnchantment($type->getChildId())->getMaxLevel()) {
                                $transaction->getSource()->sendMessage("§r§c§l(!) §r§cYou require the custom enchant " . EnchantmentsManager::getEnchantment($type->getChildId())->getName() . " at level " . EnchantmentsManager::getEnchantment($type->getChildId())->getMaxLevel() . " in order to apply this enchantment!");
                                return;
                            }

                            $itemClicked->removeEnchantment(EnchantmentsManager::getEnchantment($type->getChildId()));
                        }

                        $successrate = $itemClickedWith->getNamedTag()->getInt("success",0) !== 0 ? $itemClickedWith->getNamedTag()->getInt("success") : 100;
                        $destroyrate = $itemClickedWith->getNamedTag()->getInt("destroy",0) !== 0 ? $itemClickedWith->getNamedTag()->getInt("destroy") : 0;

                        if ($this->checkPercentages($successrate, $destroyrate) === false && $itemClicked->getNamedTag()->getString("protected", "") === "" && EnchantmentsManager::check($itemClicked, $enchantment->getType()) === true){
                            $event->cancel();
                            $transaction->getSource()->sendMessage("§r§c§l(!) §r§cYour item and the enchantment book has been destroyed.");
                            Loader::playSound($transaction->getSource(), "random.explosion", 2);
                            $transaction->getSource()->getWorld()->addParticle($transaction->getSource()->getLocation(), new HugeExplodeSeedParticle());
                            $action->getInventory()->setItem($action->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
                            $otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
                            return;
                        }

                        if ($this->checkPercentages($successrate, $destroyrate) === false && $itemClicked->getNamedTag()->getString("protected", "") !== "" && EnchantmentsManager::check($itemClicked, $enchantment->getType()) === true) {
                            $event->cancel();
                            $transaction->getSource()->sendMessage("§r§f** §r§f§lWHITE SCROLL §r§f**");
                            $transaction->getSource()->sendMessage("§r§c§l(!) §r§cYour item has been saved from the white scroll, however the book has been destroyed.");
                            Loader::playSound($transaction->getSource(), "random.explode", 2);
                            $transaction->getSource()->getWorld()->addParticle($transaction->getSource()->getLocation(), new HugeExplodeSeedParticle());
                            $lores = $itemClicked->getLore();
                            $string = TextFormat::RESET . TextFormat::BOLD . TextFormat::WHITE . "PROTECTED";
                            unset($lores[array_search($string, $lores)]);
                            $itemClicked->getNamedTag()->removeTag("protected");
                            $itemClicked->setLore($lores);
                            $action->getInventory()->setItem($action->getSlot(), $itemClicked);
                            $otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::getInstance()->get(ItemIds::AIR));
                            return;
                        }

                        $newLevel = $enchantment->getLevel();
                        if (($existingEnchant = $itemClicked->getEnchantment($enchantment->getType())) !== null) {
                            if ($existingEnchant->getLevel() > $newLevel) continue;
                            $newLevel = $existingEnchant->getLevel() === $newLevel ? $newLevel + 1 : $newLevel;
                        }

                        if (EnchantmentsManager::check($itemClicked, $enchantment->getType()) === false) {
                            $transaction->getSource()->sendMessage("§r§c§l(!) §r§cThis Custom Enchantment is not compatible with the selected item.");
                            $transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
                            return;
                        }

                        if ($newLevel > $enchantment->getType()->getMaxLevel()) {
                            $transaction->getSource()->sendMessage("§r§c§l(!) §r§cThis Custom Enchantment has met the MAXLVL: ({$enchantment->getType()->getMaxLevel()})");
                            $transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new AnvilFallSound());
                            return;
                        }

                        if (!EnchantmentsManager::check($itemClicked, $enchantment->getType()) || ($itemClicked->getId() === ItemIds::ENCHANTED_BOOK && count($itemClicked->getEnchantments()) === 0)) continue;
                        $itemClicked->addEnchantment(new EnchantmentInstance($enchantment->getType(), $enchantment->getLevel()));
                        $action->getInventory()->setItem($action->getSlot(), $itemClicked);
                        $enchantmentSuccessful = true;
                    }

                    if ($enchantmentSuccessful) {
                        $event->cancel();
                        #$transaction->getSource()->sendMessage("UR LIMIT IS:$limit");
                        $transaction->getSource()->getLocation()->getWorld()->addSound($transaction->getSource()->getLocation()->asPosition(), new XpLevelUpSound(100));
                        $otherAction->getInventory()->setItem($otherAction->getSlot(), ItemFactory::air());
                    }
                }
            }
        }
    }

    /**
     * @param int $successRate
     * @param int $destroyRate
     * @return bool
     */
    public function checkPercentages(int $successRate, int $destroyRate) : bool {
        if(mt_rand(0, 100) > $successRate) {
            if(mt_rand(0, 100) < $destroyRate) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @return void
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        if ($packet instanceof InventoryTransactionPacket) {
            $transaction = $packet->trData;
            foreach ($transaction->getActions() as $action) {
                $action->oldItem = new ItemStackWrapper($action->oldItem->getStackId(), EnchantmentsManager::filterDisplayedEnchants($action->oldItem->getItemStack()));
                $action->newItem = new ItemStackWrapper($action->newItem->getStackId(), EnchantmentsManager::filterDisplayedEnchants($action->newItem->getItemStack()));
            }
        }
        if ($packet instanceof MobEquipmentPacket) EnchantmentsManager::filterDisplayedEnchants($packet->item->getItemStack());
    }

    public function onDataPacketSend(DataPacketSendEvent $event): void {
        $packets = $event->getPackets();
        foreach ($packets as $packet) {
            if ($packet instanceof InventorySlotPacket) {
                $packet->item = new ItemStackWrapper($packet->item->getStackId(), EnchantmentsManager::displayEnchants($packet->item->getItemStack()));
            }
            if ($packet instanceof InventoryContentPacket) {
                foreach ($packet->items as $i => $item) {
                    $packet->items[$i] = new ItemStackWrapper($item->getStackId(), EnchantmentsManager::displayEnchants($item->getItemStack()));
                }
            }
        }
    }
}