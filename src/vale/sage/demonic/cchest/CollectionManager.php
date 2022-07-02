<?php

namespace vale\sage\demonic\cchest;

use vale\sage\demonic\Loader;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\world\Position;
use vale\sage\demonic\cchest\CollectionChest;
use vale\sage\demonic\cchest\CollectionListener;

class CollectionManager
{
	private array $chests = [];
	
	public function __construct(private Loader $loader){
		$db = new Config($loader->getDataFolder()."cchest.json", Config::JSON);
		foreach($db->get("chests", []) as $position => $data){
			$this->chests[$position] = new CollectionChest($loader, $data);
		}
		$loader->getServer()->getPluginManager()->registerEvents(new CollectionListener($loader), $loader);
	}
	
	public function addChest(Position $position): void{
		$data = 
		[
			// this is the default|new|empty data of the collection chest
			"logs" => [],
			"items" => [
				"iron_ingot" => 0,
				"blaze_rod" => 0,
				"gunpowder" => 0,
				"ender_pearl" => 0,
				"spider_eye" => 0,
				"string" => 0,
				"bone" => 0,
				"arrow" => 0,
				"emerald" => 0,
				"diamond" => 0,
				"leather" => 0,
				"rotten_flesh" => 0,
				"emerald_block" => 0,
				"diamond_block" => 0,
				"gold_ingot" => 0
			],
		];
		$this->chests[$this->loader->positionToString($position)] = new CollectionChest($this->loader, $data);
	}
	
	public function removeChest(Position $position): void{
		unset($this->chests[$this->loader->positionToString($position)]);
	}
	
	public function getChest(Position $position): ?CollectionChest{
		if(!array_key_exists($this->loader->positionToString($position), $this->chests)) return null;
		return $this->chests[$this->loader->positionToString($position)];
	}
	
	public function getItem(): Item{
		$item = ItemFactory::getInstance()->get(ItemIds::CHEST, 0);
		$item->setCustomName(C::RESET . C::WHITE . "Collection Chest");
		$item->setLore([
			C::RESET . C::GRAY . " * This will collect all mob spawner drops within the ranged"
		]);
		$enchantment = new EnchantmentInstance(StringToEnchantmentParser::getInstance()->parse("unbreaking"), 1);
		$item->addEnchantment($enchantment);
		$nbt = $item->getNamedTag();
		$nbt->setString("cchest", "cchest");
		$item->setNamedTag($nbt);
		return $item;
	}
	
	public function getNearestChest(Position $position): ?CollectionChest{
		$nearest = null;
		foreach($this->chests as $cpos => $class){
			$cpos = $this->loader->stringToPosition($cpos);
			if($position->distance($cpos) >= 128) continue;
			if($position->getWorld() !== $cpos->getWorld()) continue;
			if($nearest !== null){
				if($nearest->distance($cpos) >= $position->distance($cpos)){
					$nearest = $class;
					continue;
				}
			}
			$nearest = $class;
		}
		return $nearest;
	}
	
	public function save(): void{
		$db = new Config($this->loader->getDataFolder()."cchest.json", Config::JSON);
		$array = [];
		foreach($this->chests as $position => $class){
			$array[$position] = $class->getData();
		}
		$db->setNested("chests", $array);
		$db->save();
	}
}