<?php

namespace vale\sage\demonic\Partner;

use vale\sage\demonic\Partner\cooldown\EnderPearl\EnderPearl;
use vale\sage\demonic\Partner\Item\AntiTrap;
use vale\sage\demonic\Partner\Item\Bard;
use vale\sage\demonic\Partner\Item\ComboAbility;
use vale\sage\demonic\Partner\Item\Guardian;
use vale\sage\demonic\Partner\Item\HateFoo;
use vale\sage\demonic\Partner\Item\MeeZoid;
use vale\sage\demonic\Partner\Item\Ninja;
use vale\sage\demonic\Partner\Item\NotRamix;
use vale\sage\demonic\Partner\Item\SnowBall;
use vale\sage\demonic\Partner\Item\TimeWarp;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\entity\ProjectileHitEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use Ramsey\Uuid\Type\Time;

class EventHandle implements Listener
{
    public function onDamage(EntityDamageByEntityEvent $event){
        $ninja = new Ninja();
        $ninja->onDamage($event);
        $Meezoid = new Meezoid();
        $Meezoid->onDamage($event);

        $combo = new ComboAbility();
        $combo->onDamage($event);

    }

    public function onProject(ProjectileHitEntityEvent $event){
        $anti = new AntiTrap();
        $anti->damage($event);
    }


    public function onInterac(PlayerItemUseEvent $event){
        $ninja = new Ninja();
        $ninja->onUse($event);

        Bard::onUse($event);
        $hatefoo = new HateFoo();
        $hatefoo->onUse($event);

        $Guardian = new Guardian();
        $Guardian->onUse($event);

        $Combo = new ComboAbility();
        $Combo->onUse($event);

        $notRamix = new NotRamix();
        $notRamix->onUse($event);

        $anti = new AntiTrap();
        $anti->onUse($event);

        SnowBall::onUse($event);
        EnderPearl::onUse($event);
        TimeWarp::onUse($event);
    }

    public function onPlace(BlockPlaceEvent $event){
        $meezoid = new MeeZoid();
        $meezoid->onPlace($event);
    }

    public function onBreak(BlockBreakEvent $event){
        $meezoid = new MeeZoid();
        $meezoid->onBreak($event);
    }


}