<?php

namespace vale\sage\demonic\enchants;

use pocketmine\player\Player;
use pocketmine\world\particle\FlameParticle;

class Particles
{
	public function coneFlame(Player $player) {
        $origin = $player->getLocation()->add(-0.05, 0, -0.05);
		for($i = 5; $i > 0; $i -= 0.1){
			$radio = $i / 3;
			$x = $radio * cos(3 * $i);
			$y = 5 - $i;
			$z = $radio * sin(3 * $i);
			$player->getLevel()->addParticle(new FlameParticle($origin->add($x, $y, $z)));
		}
		for($i = 5; $i > 0; $i -= 0.1){
			$radio = $i / 3;
			$x = -$radio * cos(3 * $i);
			$y = 5 - $i;
			$z = -$radio * sin(3 * $i);
			$player->getLevel()->addParticle(new FlameParticle($origin->add($x, $y, $z)));
		}
    }
	
}