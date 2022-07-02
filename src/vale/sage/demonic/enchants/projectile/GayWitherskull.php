<?php

namespace vale\sage\demonic\enchants\projectile;

use vale\sage\demonic\Loader;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\math\RayTraceResult;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\player\Player;
use pocketmine\item\Durable;
use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Random;
use pocketmine\color\Color;
use pocketmine\world\World;
use pocketmine\world\particle\InstantEnchantParticle;

class GayWitherskull extends NiggaProjectile
{
    protected $drag = 0.01;
    protected $gravity = 0.05;

    protected $damage = 0;

    public function onHitEntity(Entity $entityHit, RayTraceResult $hitResult): void
    {
        if ($entityHit instanceof Player) {
			$owner = $this->getOwningEntity();
			if ($entityHit == $owner) return;
			
            $effect = new EffectInstance(VanillaEffects::WITHER(), 20*5, 2, false);
            $entityHit->getEffects()->add($effect);
			
			$world = $entityHit->getWorld();
			$random = new Random((int) (microtime(true) * 1000) + mt_rand());
			$playerPos = $entityHit->getPosition();
			$pos = new Vector3(
				$this->getRelativeDouble($playerPos->getX(), $entityHit, "~"),
				$this->getRelativeDouble($playerPos->getY(), $entityHit, "~", World::Y_MIN, World::Y_MAX),
				$this->getRelativeDouble($playerPos->getZ(), $entityHit, "~")
			);
			$particle = new InstantEnchantParticle(new Color(255, 0, 127));
			for($i = 0; $i < 10; ++$i) {
				$world->addParticle($pos->add(
					$random->nextSignedFloat(),
					$random->nextSignedFloat() + 0.5,
					$random->nextSignedFloat()
				), $particle);
			}
			
			$armors = $entityHit->getArmorInventory()->getContents();
			if (empty($armors)) return;
			$armor = $armors[array_rand($armors)];
			if (rand(1, 100) <= rand(1, 2)) {
				if ($armor instanceof Durable) {
					$armor->applyDamage($armor->getMaxDurability());
					$entityHit->getArmorInventory()->setItem($armor->getArmorSlot(), $armor);
					$entityHit->sendMessage(C::RED . "Your " . $armor->getVanillaName() . " has been destroyed by the soul from the demonic realm!");
					$owner->sendMessage(Loader::REG_CMD_PREFIX . "Your demonic soul has destroyed enemy's " . $armor->getVanillaName() . "!");
				}
			}
        }
        parent::onHitEntity($entityHit, $hitResult);
    }

    public static function getNetworkTypeId(): string
    {
        return EntityIds::WITHER_SKULL;
    }

    protected function getInitialSizeInfo(): EntitySizeInfo
    {
        return new EntitySizeInfo(0.5, 0.5);
    }
	
	public function getRelativeDouble(float $original, Player $sender, string $input, float $min = -30000000, float $max = 30000000) : float{
		if($input[0] === "~"){
			$value = $this->getDouble($sender, substr($input, 1));

			return $original + $value;
		}

		return $this->getDouble($sender, $input, $min, $max);
	}

	public function getDouble(Player $sender, string $value, float $min = -30000000, float $max = 30000000) : float{
		$i = (double) $value;

		if($i < $min){
			$i = $min;
		}elseif($i > $max){
			$i = $max;
		}

		return $i;
	}
}