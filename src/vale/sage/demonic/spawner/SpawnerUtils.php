<?php

namespace vale\sage\demonic\spawner;

class SpawnerUtils
{
	public static function getEntityName(string $name): string{
		$names = [
			"irongolem" => "Iron Golem",
			"blaze" => "Blaze",
			"creeper" => "Creeper",
			"enderman" => "Enderman",
			"zombiepigman" => "Zombie Pigman",
			"cavespider" => "Cave Spider",
			"spider" => "Spider",
			"skeleton" => "Skeleton",
			"zombie" => "Zombie",
			"wolf" => "Wolf",
			"pig" => "Pig",
			"chicken" => "Chicken",
			"sheep" => "Sheep",
			"cow" => "Cow",
		];
		return $names[$name] ?? "Unknown";
	}
	
	public static function getEntityDrop(string $name): string{
		$names = [
			"irongolem" => "Iron Ingot and Poppy",
			"blaze" => "Blaze Rod",
			"creeper" => "Gunpowder",
			"enderman" => "Enderpearl",
			"zombiepigman" => "Gold Nugget and Rotten Flesh",
			"cavespider" => "String, Rotten Flesh and Spider Eye",
			"spider" => "String, Iron Ingot, Carrot and Potato",
			"skeleton" => "Arrow and Bone",
			"zombie" => "Rotten Flesh, Iron Ingot, Carrot and Potato",
			"wolf" => "Bone",
			"pig" => "Raw Porkchop",
			"chicken" => "Raw Chicken and Feather",
			"sheep" => "Wool and Raw Mutton",
			"cow" => "Raw Beef and Leather",
		];
		return $names[$name] ?? "Nothing";
	}
	
	public static function getEntityArrayList(): array{
        $names = [
            "irongolem",
			"blaze",
			"creeper",
			"enderman",
			"zombiepigman",
			"cavespider",
			"spider",
			"skeleton",
			"zombie",
			"wolf",
			"pig",
			"chicken",
			"sheep",
			"cow",
		];
        return $names;
    }
}