<?php


namespace vale\sage\demonic\utils;


use pocketmine\world\Position;
use vale\sage\demonic\factions\Faction;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\sets\manager\ArmorManager;
use vale\sage\demonic\Loader;
use pocketmine\entity\Human;
use pocketmine\inventory\ArmorInventory;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class Utils
{

    public static function getRandomFloat(int $min, int $max): float{
        return mt_rand($min, $max - 1) + (mt_rand(0, PHP_INT_MAX - 1) / PHP_INT_MAX);
    }

    public static function wearFullArmorSet(Human $player): ?BaseArmorItem{
        $armor = [];
        foreach ($player->getArmorInventory()->getContents() as $slot => $item) {
            $item = ArmorManager::getInstance()->getArmor($item);
            if ($item instanceof BaseArmorItem) {
                if (!isset($armor[$item->getArmorName()])) $armor[$item->getArmorName()] = 0;
                $armor[$item->getArmorName()] += 1;
            }
        }
        foreach ($armor as $name => $count) {
            if ($count >= 4) return ArmorManager::getInstance()->getArmor([$name, "helmet"]);
        }
        return null;
    }

    public static function armorSlotToType(int $slot): string{
        return match ($slot) {
            ArmorInventory::SLOT_HEAD => "helmet",
            ArmorInventory::SLOT_CHEST => "chestplate",
            ArmorInventory::SLOT_LEGS => "leggings",
            ArmorInventory::SLOT_FEET => "boots",
            default => "undefined"
        };
    }

    private static array $requests;

    public static function shortenNumber(int $number, int $precision = 3): string {
        $divisors = [
            1000 ** 0 => "", // One
            1000 ** 1 => "K", // Thousand
            1000 ** 2 => "M", // Million
            1000 ** 3 => "B", // Billion
            1000 ** 4 => "T", // Trillion
        ];

        foreach($divisors as $divisor => $shorthand){
            if(abs($number) < ($divisor * 1000)) {
                break;
            }
        }

        return (float)number_format($number / $divisor, $precision) . $shorthand;
    }


    public static function translateTime(int $seconds): string
	{
		if ($seconds < 60) {
			if ($seconds == 1) return $seconds . " second";
			return $seconds . " seconds";
		} else {
			if (round($seconds / 60) == 1) return round($seconds / 60) . " minute";
			return round($seconds / 60) . " minutes";
		}
	}

	/**
	 * @param Faction $faction
	 * @param Player $player
	 * @return bool
	 */
    public static function hasRequested(Faction $faction, Player $player): bool{
		return isset($faction->requests[$player->getName()]);
    }

	public static function addRequests(Faction $faction, Player $player): void{
		$faction->requests[$player->getName()] = $player;
	}
}