<?php

namespace vale\sage\demonic\cchest;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use vale\sage\demonic\Loader as Sage;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\utils\TextFormat as C;

class CollectionChest
{
	private array $prizes = 
	[
		"iron_ingot" => 10,
		"blaze_rod" => 10,
		"gunpowder" => 10,
		"ender_pearl" => 10,
		"spider_eye" => 10,
		"string" => 10,
		"bone" => 10,
		"arrow" => 10,
		"emerald" => 10,
		"diamond" => 10,
		"leather" => 10,
		"rotten_flesh" => 10,
		"emerald_block" => 10,
		"diamond_block" => 10,
		"gold_ingot" => 10,
	];
	private array $logs = [];
	private int $iron_ingot = 0;
	private int $blaze_rod = 0;
	private int $gunpowder = 0;
	private int $ender_pearl = 0;
	private int $spider_eye = 0;
	private int $string = 0;
	private int $bone = 0;
	private int $arrow = 0;
	private int $emerald = 0;
	private int $diamond = 0;
	private int $leather = 0;
	private int $rotten_flesh = 0;
	private int $emerald_block = 0;
	private int $diamond_block = 0;
	private int $gold_ingot = 0;
	
	public function __construct(private Loader $loader, private array $data){
		foreach($data["logs"] as $info){
			if(count($this->logs) >= 53) continue;
			if(86400 - (time() - $info["time"]) <= 0) continue;
			$this->logs[] = $info;
		}
		$this->iron_ingot = $data["items"]["iron_ingot"];
		$this->blaze_rod = $data["items"]["blaze_rod"];
		$this->gunpowder = $data["items"]["gunpowder"];
		$this->ender_pearl = $data["items"]["ender_pearl"];
		$this->spider_eye = $data["items"]["spider_eye"];
		$this->string = $data["items"]["string"];
		$this->bone = $data["items"]["bone"];
		$this->arrow = $data["items"]["arrow"];
		$this->emerald = $data["items"]["emerald"];
		$this->diamond = $data["items"]["diamond"];
		$this->leather = $data["items"]["leather"];
		$this->rotten_flesh = $data["items"]["rotten_flesh"];
		$this->emerald_block = $data["items"]["emerald_block"];
		$this->diamond_block = $data["items"]["diamond_block"];
		$this->gold_ingot = $data["items"]["gold_ingot"];
	}
	
	public function sendMenu(Player $player): void{
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName(C::RESET . "Collection Chest Contents");
		$menu->setListener(InvMenu::readonly(function(DeterministicInvMenuTransaction $transaction): void{
			$player = $transaction->getPlayer();
			$clicked = $transaction->getItemClicked();
			if($clicked->getId() === ItemIds::PAPER){
				$this->sendLogMenu($player);
			}
			if($clicked->getId() === ItemIds::GOLD_NUGGET){
				$player->removeCurrentWindow();
				$this->sellAll($player);
			}
			if($clicked->getId() === ItemIds::IRON_INGOT){
				$player->removeCurrentWindow();
				$count = $this->iron_ingot;
				$value = $this->prizes["iron_ingot"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->iron_ingot = 0;
			}
			if($clicked->getId() === ItemIds::BLAZE_ROD){
				$player->removeCurrentWindow();
				$count = $this->blaze_rod;
				$value = $this->prizes["blaze_rod"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->blaze_rod = 0;
			}
			if($clicked->getId() === ItemIds::GUNPOWDER){
				$player->removeCurrentWindow();
				$count = $this->gunpowder;
				$value = $this->prizes["gunpowder"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->gunpowder = 0;
			}
			if($clicked->getId() === ItemIds::ENDER_PEARL){
				$player->removeCurrentWindow();
				$count = $this->ender_pearl;
				$value = $this->prizes["ender_pearl"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->ender_pearl = 0;
			}
			if($clicked->getId() === ItemIds::SPIDER_EYE){
				$player->removeCurrentWindow();
				$count = $this->spider_eye;
				$value = $this->prizes["spider_eye"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->spider_eye = 0;
			}
			if($clicked->getId() === ItemIds::STRING){
				$player->removeCurrentWindow();
				$count = $this->string;
				$value = $this->prizes["string"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->string = 0;
			}
			if($clicked->getId() === ItemIds::BONE){
				$player->removeCurrentWindow();
				$count = $this->bone;
				$value = $this->prizes["bone"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->bone = 0;
			}
			if($clicked->getId() === ItemIds::ARROW){
				$player->removeCurrentWindow();
				$count = $this->arrow;
				$value = $this->prizes["arrow"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->arrow = 0;
			}
			if($clicked->getId() === ItemIds::EMERALD){
				$player->removeCurrentWindow();
				$count = $this->emerald;
				$value = $this->prizes["emerald"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->emerald = 0;
			}
			if($clicked->getId() === ItemIds::DIAMOND){
				$player->removeCurrentWindow();
				$count = $this->diamond;
				$value = $this->prizes["diamond"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->diamond = 0;
			}
			if($clicked->getId() === ItemIds::LEATHER){
				$player->removeCurrentWindow();
				$count = $this->leather;
				$value = $this->prizes["leather"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->leather = 0;
			}
			if($clicked->getId() === ItemIds::ROTTEN_FLESH){
				$player->removeCurrentWindow();
				$count = $this->rotten_flesh;
				$value = $this->prizes["rotten_flesh"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->rotten_flesh = 0;
			}
			if($clicked->getId() === ItemIds::EMERALD_BLOCK){
				$player->removeCurrentWindow();
				$count = $this->emerald_block;
				$value = $this->prizes["emerald_block"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->emerald_block = 0;
			}
			if($clicked->getId() === ItemIds::DIAMOND_BLOCK){
				$player->removeCurrentWindow();
				$count = $this->diamond_block;
				$value = $this->prizes["diamond_block"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->diamond_block = 0;
			}
			if($clicked->getId() === ItemIds::GOLD_INGOT){
				$player->removeCurrentWindow();
				$count = $this->gold_ingot;
				$value = $this->prizes["gold_ingot"] * $count;
				$data = [];
				if($value <= 0){
					$player->sendMessage(C::RED . C::BOLD . "(!) " . C::RESET . C::RED . "This is empty!");
					return;
				}
				Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
				$data["items"][] = ["name" => $clicked->getVanillaName(), "count" => $count, "value" => $value];
				$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold " . $count . "x " . $clicked->getVanillaName() . " for $" . number_format($value));
				$data["player"] = $player->getName();
				$data["amount"] = $value;
				$data["time"] = time();
				$this->logs[] = $data;
				$this->gold_ingot = 0;
			}
		}));
		for($i = 0; $i < 54; $i++){
			if(in_array($i, [10,11,12,14,15,16,28,29,30,32,33,34,37,38,40,42,53])) continue;
			$menu->getInventory()->setItem($i, ItemFactory::getInstance()->get(160, 7, 1));
		}
		$menu->getInventory()->setItem(10, VanillaItems::IRON_INGOT()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->iron_ingot . "x"]));
		$menu->getInventory()->setItem(11, VanillaItems::BLAZE_ROD()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->blaze_rod . "x"]));
		$menu->getInventory()->setItem(12, VanillaItems::GUNPOWDER()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->gunpowder . "x"]));
		
		$menu->getInventory()->setItem(14, VanillaItems::ENDER_PEARL()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->ender_pearl . "x"]));
		$menu->getInventory()->setItem(15, VanillaItems::SPIDER_EYE()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->spider_eye . "x"]));
		$menu->getInventory()->setItem(16, VanillaItems::STRING()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->string . "x"]));
		
		$menu->getInventory()->setItem(28, VanillaItems::BONE()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->bone . "x"]));
		$menu->getInventory()->setItem(29, VanillaItems::ARROW()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->arrow . "x"]));
		$menu->getInventory()->setItem(30, VanillaItems::EMERALD()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->emerald . "x"]));
		
		
		$menu->getInventory()->setItem(32, VanillaItems::DIAMOND()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->diamond . "x"]));
		$menu->getInventory()->setItem(33, VanillaItems::LEATHER()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->leather . "x"]));
		$menu->getInventory()->setItem(34, VanillaItems::ROTTEN_FLESH()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->rotten_flesh . "x"]));
		
		$menu->getInventory()->setItem(37, ItemFactory::getInstance()->get(ItemIds::EMERALD_BLOCK)->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->emerald_block . "x"]));
		$menu->getInventory()->setItem(38, ItemFactory::getInstance()->get(ItemIds::DIAMOND_BLOCK)->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->diamond_block . "x"]));
		
		$menu->getInventory()->setItem(40, VanillaItems::GOLD_NUGGET()->setCustomName(C::RESET . C::BOLD . C::RED . "Sell all items")->setLore([C::RESET . C::GRAY . "Current value: " . C::WHITE . "$" . number_format($this->getAllValue())]));
		
		$menu->getInventory()->setItem(42, VanillaItems::GOLD_INGOT()->setLore([C::RESET . C::YELLOW . "  * Count: " . $this->gold_ingot . "x"]));
		
		$menu->getInventory()->setItem(53, VanillaItems::PAPER()->setCustomName(C::RESET . C::RED . C::BOLD . "Collection Chest Logs")->setLore([C::RESET . C::GRAY . "Left-Click to open the logs", C::RESET . C::GRAY . "for this collection chest"]));
		
		$menu->send($player);
	}
	
	public function sellAll(Player $player): void{
		$value = 0;
		$data = [];
		foreach([
		"iron_ingot" => $this->iron_ingot,
		"blaze_rod" => $this->blaze_rod,
		"gunpowder" => $this->gunpowder,
		"ender_pearl" => $this->ender_pearl,
		"spider_eye" => $this->spider_eye,
		"string" => $this->string,
		"bone" => $this->bone,
		"arrow" => $this->arrow,
		"emerald" => $this->emerald,
		"diamond" => $this->diamond,
		"leather" => $this->leather,
		"rotten_flesh" => $this->rotten_flesh,
		"emerald_block" => $this->emerald_block,
		"diamond_block" => $this->diamond_block,
		"gold_ingot" => $this->gold_ingot,
		] as $name => $count){
			$amount = $this->prizes[$name] * $count;
			if($count > 0) $data["items"][] = ["name" => ucwords(str_replace("_", " ", $name)), "count" => $count, "value" => $amount];
			$value += $amount;
		}
		$this->iron_ingot = 0;
		$this->blaze_rod = 0;
		$this->gunpowder = 0;
		$this->ender_pearl = 0;
		$this->spider_eye = 0;
		$this->string = 0;
		$this->bone = 0;
		$this->arrow = 0;
		$this->emerald = 0;
		$this->diamond = 0;
		$this->leather = 0;
		$this->rotten_flesh = 0;
		$this->emerald_block = 0;
		$this->diamond_block = 0;
		$this->gold_ingot = 0;
		$player->sendMessage(C::GREEN . C::BOLD . "(!) " . C::RESET . C::GREEN . "You've sold all items for $" . number_format($value));
		if($value <= 0) return;
		Sage::getInstance()->getSessionManager()->getSession($player)->addBalance($value);
		$data["player"] = $player->getName();
		$data["amount"] = $value;
		$data["time"] = time();
		$this->logs[] = $data;
		/*
			0 => [
				"player" => "Steve",
				"amount" => 100000,
				"items" => [
					0 => [
						"name" => "Iron Ingot",
						"count" => 1,
						"value" => 10
					],
				],
				"time" => 254368987544567
			],
		*/
	}
	
	public function sendLogMenu(Player $player): void{
		$this->refreshLog();
		$menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$menu->setName(C::RESET . "Collection Chest Logs");
		$menu->setListener(InvMenu::readonly());
		foreach($this->logs as $slot => $info){
			$item = VanillaItems::PAPER();
			$item->setCustomName(C::RESET . C::GREEN . C::BOLD . $info["player"]);
			$lores = [
				C::RESET . C::GRAY . " * Sold: $" . number_format($info["amount"]),
				C::RESET . C::GRAY . "Items: ",
			];
			foreach($info["items"] as $item_info){
				$lores[] = C::RESET . C::GRAY . " * " . $item_info["name"] . " x" . $item_info["count"] . ": $" . number_format($item_info["value"]);
			}
			$duration = time() - $info["time"];
			$hours = (int)($duration / 60/ 60);
			$minutes = (int)($duration /60) - $hours * 60;
			$seconds = (int) $duration - $hours * 60 * 60 - $minutes * 60;
			$lores[] = C::RESET . C::GRAY . "Time: " . $hours . " hours, " . $minutes . " minutes and " . $seconds . " seconds Ago";
			$item->setLore($lores);
			/*
				Steve
					  * Sold: $1,000
					Items:
					  * Iron Ingot: $1,000
					Time: 3 hours and 1 second
			*/
			$menu->getInventory()->setItem($slot, $item);
		}
		$menu->send($player);
	}
	
	public function refreshLog(): void{
		$logs = [];
		foreach($this->logs as $int => $info){
			if(count($logs) >= 53){
				array_shift($logs);
			}
			if(86400 - (time() - $info["time"]) <= 0) continue;
			$logs[] = $info;
		}
		$this->logs = $logs;
	}
	
	public function getAllValue(): int{
		$value = $this->prizes["iron_ingot"] * $this->iron_ingot;
		$value += $this->prizes["blaze_rod"] * $this->blaze_rod;
		$value += $this->prizes["gunpowder"] * $this->gunpowder;
		$value += $this->prizes["ender_pearl"] * $this->ender_pearl;
		$value += $this->prizes["spider_eye"] * $this->spider_eye;
		$value += $this->prizes["string"] * $this->string;
		$value += $this->prizes["bone"] * $this->bone;
		$value += $this->prizes["arrow"] * $this->arrow;
		$value += $this->prizes["emerald"] * $this->emerald;
		$value += $this->prizes["diamond"] * $this->diamond;
		$value += $this->prizes["leather"] * $this->leather;
		$value += $this->prizes["rotten_flesh"] * $this->rotten_flesh;
		$value += $this->prizes["emerald_block"] * $this->emerald_block;
		$value += $this->prizes["diamond_block"] * $this->diamond_block;
		$value += $this->prizes["gold_ingot"] * $this->gold_ingot;
		return $value;
	}
	
	public function getData(): array{
		$array = [];
		$array["logs"] = $this->logs;
		$array["items"]["iron_ingot"] = $this->iron_ingot;
		$array["items"]["blaze_rod"] = $this->blaze_rod;
		$array["items"]["gunpowder"] = $this->gunpowder;
		$array["items"]["ender_pearl"] = $this->ender_pearl;
		$array["items"]["spider_eye"] = $this->spider_eye;
		$array["items"]["string"] = $this->string;
		$array["items"]["bone"] = $this->bone;
		$array["items"]["arrow"] = $this->arrow;
		$array["items"]["emerald"] = $this->emerald;
		$array["items"]["diamond"] = $this->diamond;
		$array["items"]["leather"] = $this->leather;
		$array["items"]["rotten_flesh"] = $this->rotten_flesh;
		$array["items"]["emerald_block"] = $this->emerald_block;
		$array["items"]["diamond_block"] = $this->diamond_block;
		$array["items"]["gold_ingot"] = $this->gold_ingot;
		return $array;
	}
	
	public function addItems(array $items): array{
		$drop = [];
		foreach($items as $item){
			$name = strtolower(str_replace(" ", "_", $item->getName()));
			if(!isset($this->prizes[$name])){
				$drop[] = $item;
				continue;
			}
			if($name === "iron_ingot") $this->iron_ingot += $item->getCount();
			if($name === "blaze_rod") $this->blaze_rod += $item->getCount();
			if($name === "gunpowder") $this->gunpowder += $item->getCount();
			if($name === "ender_pearl") $this->ender_pearl += $item->getCount();
			if($name === "spider_eye") $this->spider_eye += $item->getCount();
			if($name === "string") $this->string += $item->getCount();
			if($name === "bone") $this->bone += $item->getCount();
			if($name === "arrow") $this->arrow += $item->getCount();
			if($name === "emerald") $this->emerald += $item->getCount();
			if($name === "diamond") $this->diamond += $item->getCount();
			if($name === "leather") $this->leather += $item->getCount();
			if($name === "rotten_flesh") $this->rotten_flesh += $item->getCount();
			if($name === "emerald_block") $this->emerald_block += $item->getCount();
			if($name === "diamond_block") $this->diamond_block += $item->getCount();
			if($name === "gold_ingot") $this->gold_ingot += $item->getCount();
		}
		return $drop;
	}
	
	public function isEmpty(): bool{
		if($this->iron_ingot > 0) return false;
		if($this->blaze_rod > 0) return false;
		if($this->gunpowder > 0) return false;
		if($this->ender_pearl > 0) return false;
		if($this->spider_eye > 0) return false;
		if($this->string > 0) return false;
		if($this->bone > 0) return false;
		if($this->arrow > 0) return false;
		if($this->emerald > 0) return false;
		if($this->diamond > 0) return false;
		if($this->leather > 0) return false;
		if($this->rotten_flesh > 0) return false;
		if($this->emerald_block > 0) return false;
		if($this->diamond_block > 0) return false;
		if($this->gold_ingot > 0) return false;
		return true;
	}
}