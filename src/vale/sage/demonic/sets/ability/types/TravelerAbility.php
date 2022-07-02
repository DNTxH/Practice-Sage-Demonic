<?php
namespace vale\sage\demonic\sets\ability\types;
use vale\sage\demonic\sets\ability\BaseAbility;
use vale\sage\demonic\entity\ability\CustomFallingBlockEntity;
use vale\sage\demonic\Loader;
use pocketmine\block\VanillaBlocks;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;
use pocketmine\world\Position;


/**
 * Class TravelerAbility
 * @package vale\sage\demonic\ability\types
 * @author Jibix
 * @date 06.01.2022 - 19:01
 * @project Genesis
 */
class TravelerAbility extends BaseAbility{

    public function __construct(){
        parent::__construct(25, 60 * 3, "traveler");
    }

    public function react(Player $player, ...$args): void{
        $pos = $player->getPosition();
        $pos->y += 6;
        $positions = $this->getPositions($pos);
        $blocks = [VanillaBlocks::SOUL_SAND(), VanillaBlocks::END_STONE(), VanillaBlocks::NETHER_BRICKS()];

        $i = 0;
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new ClosureTask(function () use ($player, &$i, $positions, $blocks): void{
            if (++$i >= 4) throw new CancelTaskException();

            /** @var Position $position */
            foreach ($positions as $position) {
                $entity = new CustomFallingBlockEntity($position, $blocks[array_rand($blocks)]);
                $entity->setOwningEntity($player);
                $entity->spawnToAll();
            }
        }), 20 * 2);
    }

    private function getPositions(Position $middle): array{
        $positions = [$middle];
        $x = [];

        for ($i = 1; $i <= 4; $i++) {
            $x[] = $middle->add($i, 0, 0);
            $x[] = $middle->subtract($i, 0, 0);

            $positions[] = $middle->add(0, 0, $i);
            $positions[] = $middle->subtract(0, 0, $i);
        }

        foreach ($x as $position) {
            for ($i = 1; $i <= 4; $i++) {
                $positions[] = $position->subtract(0, 0, $i);
                $positions[] = $position->add(0, 0, $i);
            }

            $positions[] = $position;
        }
        $locations = [];
        foreach ($positions as $position) {
            $locations[] = new Location($position->getX(), $position->getY(), $position->getZ(), $middle->getWorld(), 0, 0);
        }

        return $locations;
    }
}