<?php

namespace vale\sage\demonic\crate;

use vale\sage\demonic\crate\FloatingTextParticle;
use vale\sage\demonic\Loader;
use vale\sage\demonic\crate\type\SimpleCrate;
use vale\sage\demonic\crate\type\UniqueCrate;
use vale\sage\demonic\crate\type\EliteCrate;
use vale\sage\demonic\crate\type\UltimateCrate;
use vale\sage\demonic\crate\type\LegendaryCrate;
use vale\sage\demonic\crate\type\MasteryCrate;
use pocketmine\world\Position;
use pocketmine\player\Player;

class CrateManager {

    /** @var array */
    public array $floatingTexts = [];
    
    /** @var array */
	public array $crates = [];

    /**
     * @param Loader $loader
     */
	public function __construct(private Loader $loader){
		$this->init();
		$loader->getServer()->getPluginManager()->registerEvents(new CrateListener($loader), $loader);
	}
	
	public function init(): void{
		$this->addCrate(new SimpleCrate(new Position(320, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getSimpleKey()));
		$this->addCrate(new UniqueCrate(new Position(316, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getUniqueKey()));
		$this->addCrate(new EliteCrate(new Position(312, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getEliteKey()));
		$this->addCrate(new UltimateCrate(new Position(308, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getUltimateKey()));
		$this->addCrate(new LegendaryCrate(new Position(304, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getLegendaryKey()));
		$this->addCrate(new MasteryCrate(new Position(300, 101, 300, $this->loader->getServer()->getWorldManager()->getDefaultWorld()), CrateKey::getMasteryKey()));
	}

    /**
     * @return array
     */
	public function getCrates(): array{
		return $this->crates;
	}

    /**
     * @param string $id
     * @return Crate|null
     */
	public function getCrate(string $id): ?Crate{
		return $this->crates[strtolower($id)] ?? null;
	}

    /**
     * @param Crate $crate
     * @return void
     */
	public function addCrate(Crate $crate): void{
		$this->crates[strtolower($crate->getName())] = $crate;
	}

    /**
     * @param string $name
     * @return Crate|null
     */
	public function getCrateByName(string $name): ?Crate{
		switch(strtolower($name)){
			case "simple":
				return $this->getCrate(Crate::SIMPLE);
			break;
			case "unique":
				return $this->getCrate(Crate::UNIQUE);
			break;
			case "elite":
				return $this->getCrate(Crate::ELITE);
			break;
			case "ultimate":
				return $this->getCrate(Crate::ULTIMATE);
			break;
			case "legendary":
				return $this->getCrate(Crate::LEGENDARY);
			break;
			case "mastery":
				return $this->getCrate(Crate::MASTERY);
			break;
		}
		return null;
	}

    /**
     * @param string $name
     * @return void
     */
    public function removeFloatingTexts(string $name): void{
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
            $text = $this->getFloatingText($player, $name);
            if($text === null) {
                continue;
            }
            $this->removeFloatingText($player, $name);
        }
    }

    /**
     * @param Player $player
     * @return array
     */
    public function getFloatingTexts(Player $player): array {
        return $this->floatingTexts[$player->getName()] ?? [];
    }

    /**
     * @param Player $player
     * @param string $identifier
     * @return FloatingTextParticle|null
     */
    public function getFloatingText(Player $player, string $identifier): ?FloatingTextParticle{
        return $this->floatingTexts[$player->getName()][$identifier] ?? null;
    }

    /**
     * @param Player $player
     * @param Position $position
     * @param string $identifier
     * @param string $message
     * @return void
     */
    public function addFloatingText(Player $player, Position $position, string $identifier, string $message): void{
        if($position->getWorld() === null) {
            return;
        }
        $floatingText = new FloatingTextParticle($position, $identifier, $message);
        $this->floatingTexts[$player->getName()][$identifier] = $floatingText;
        $floatingText->sendChangesTo($player);
    }

    /**
     * @param Player $player
     * @param string $identifier
     * @return void
     */
    public function removeFloatingText(Player $player, string $identifier): void{
        $floatingText = $this->getFloatingText($player, $identifier);
        if($floatingText === null) {
            return;
        }
        $floatingText->despawn($player);
        unset($this->floatingTexts[$player->getName()][$identifier]);
    }

}