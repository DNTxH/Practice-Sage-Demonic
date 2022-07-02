<?php
namespace vale\sage\demonic\sets\manager;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\sets\ability\types\CupidAbility;
use vale\sage\demonic\sets\ability\types\FantasyAbility;
use vale\sage\demonic\sets\ability\types\ReaperAbility;
use vale\sage\demonic\sets\ability\types\SpookyAbility;
use vale\sage\demonic\sets\ability\types\ThorAbility;
use vale\sage\demonic\sets\ability\types\TravelerAbility;
use vale\sage\demonic\sets\ability\types\XmasAbility;
use vale\sage\demonic\sets\ability\types\YijkiAbility;
use pocketmine\utils\SingletonTrait;


/**
 * Class AbilityManager
 * @package vale\sage\demonic\manager
 * @author Jibix
 * @date 06.01.2022 - 18:55
 * @project Genesis
 */
class AbilityManager{
    use SingletonTrait;

    private array $abilities = [];

    public function init(): void{
        $this->register(new TravelerAbility());
        $this->register(new YijkiAbility());
        $this->register(new FantasyAbility());
        $this->register(new ReaperAbility());
        $this->register(new CupidAbility());
        $this->register(new XmasAbility());
        $this->register(new SpookyAbility());
        $this->register(new ThorAbility());
    }

    public function register(BaseAbility $ability): void{
        $this->abilities[$ability->getName()] = $ability;
    }

    public function getAbility(string $ability): ?BaseAbility{
        return $this->abilities[$ability] ?? null;
    }
}