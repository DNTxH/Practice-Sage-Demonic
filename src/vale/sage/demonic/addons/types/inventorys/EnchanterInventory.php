<?php
namespace vale\sage\demonic\addons\types\inventorys;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\addons\types\customenchants\factory\EnchantFactory;
use vale\sage\demonic\Loader;

class EnchanterInventory{

	/**
	 * @param Player $player
	 */
	public static function open(Player $player): void{
		Loader::playSound($player, "mob.villager.idle", 1);
		$simple = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE)
			->setCustomName("§r§f§lSimple Enchantment §r§7(Right-Click)")
			->setLore(
				[
					'§r§7Examine to recieve a random',
					'§r§fsimple §r§7enchantment book.',
					'',
					'§r§7Use §r§f/help enchants simple §r§7to view a list',
					'§r§7of possible enchantments you could unlock!',
					'',
					'§r§b§lCOST §r§f400 XP'
				]
			);
		$simple->getNamedTag()->setString("cebook","simple");
		$simple->getNamedTag()->setString("xpcost","400");
		$unique = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE,13,1)
			->setCustomName("§r§a§lUnique Enchantment §r§7(Right-Click)")
			->setLore(
				[
					'§r§7Examine to recieve a random',
					'§r§aunique §r§7enchantment book.',
					'',
					'§r§7Use §r§a/help enchants simple §r§7to view a list',
					'§r§7of possible enchantments you could unlock!',
					'',
					'§r§b§lCOST §r§f800 XP'
				]
			);
		$unique->getNamedTag()->setString("cebook","unique");
		$unique->getNamedTag()->setString("xpcost","800");

		$elite = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE,3,1)
			->setCustomName("§r§b§lElite Enchantment §r§7(Right-Click)")
			->setLore(
				[
					'§r§7Examine to recieve a random',
					'§r§belite §r§7enchantment book.',
					'',
					'§r§7Use §r§b/help enchants simple §r§7to view a list',
					'§r§7of possible enchantments you could unlock!',
					'',
					'§r§b§lCOST §r§f2,500 XP'
				]
			);
		$elite->getNamedTag()->setString("cebook","elite");
		$elite->getNamedTag()->setString("xpcost","2500");
		$ultimate = ItemFactory::getInstance()->get(ItemIds::STAINED_GLASS_PANE,4,1)
			->setCustomName("§r§e§lUltimate Enchantment §r§7(Right-Click)")
			->setLore(
				[
					'§r§7Examine to recieve a random',
					'§r§eultimate §r§7enchantment book.',
					'',
					'§r§7Use §r§r/help enchants simple §r§7to view a list',
					'§r§7of possible enchantments you could unlock!',
					'',
					'§r§b§lCOST §r§f5,000 XP'
				]
			);
		$ultimate->getNamedTag()->setString("cebook","ultimate");
		$ultimate->getNamedTag()->setString("xpcost","5000");
		$legendary = null;
		$godly = null;
		$heroic = null;
		$mastery = null;
		$demonic = null;
		$inventory = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
		$inventory->getInventory()->setContents(
			[
				9 => $simple,
				10 => $unique,
				11 => $elite,
				12 => $ultimate
			]
		);
		$inventory->send($player);
	$inventory
			->setName("§r§8Sage Enchanter")
			->setListener(InvMenu::readonly(function (
				DeterministicInvMenuTransaction $transaction) use ($player, $inventory){
                 if($transaction->getItemClicked()->getNamedTag()->getString("cebook","negro") !== "negro"){
					 $cost = $transaction->getItemClicked()->getNamedTag()->getString("xpcost");
					 if($player->getXpManager()->getCurrentTotalXp() < $cost){
						 $player->sendMessage("§r§cYou do not have enough EXP to purchase that.");
						 $player->sendMessage("§r§7Your XP: {$player->getXpManager()->getCurrentTotalXp()}");
						 $player->getNetworkSession()->getInvManager()->onClientRemoveWindow($player->getNetworkSession()->getInvManager()->getCurrentWindowId());
						 return;
					 }
					 $tier = $transaction->getItemClicked()->getNamedTag()->getString("cebook");
					 $player->getXpManager()->subtractXp($cost);
					 $player->getInventory()->addItem(EnchantFactory::giveRedeemableBook($tier,1));

				 }
			}));
	}
}