<?php

namespace vale\sage\demonic\Partner\Item;

use Partner\Entity\SnowBallEntity;
use Partner\PartnerAPI;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Throwable;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\ItemIdentifier;
use pocketmine\player\Player;

class SnowBall extends \pocketmine\item\Snowball
{
    protected function createEntity(Location $location, Player $thrower) : Throwable{
        return new SnowBallEntity($location, $thrower);
    }

    public function __construct(ItemIdentifier $identifier, string $name = "§r§l§3Teleportation Ball")
    {
        $this->setCustomName($name);
        $this->setLore(["§r§7Throw to swap position with any\nenemy within 7 block radius.\n\n> §3play.genesispvp.com§7 <"]);
        parent::__construct($identifier, $name);
    }

    public static function onUse(PlayerItemUseEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        if($item instanceof self){
            if (PartnerAPI::getIsInCooldown($player, "SnowBall") === false) {
                if (PartnerAPI::isPartnerCoolDown($player) === false) {
                    $player->sendMessage("§eYou have successfully used §dTeleportation Ball");
                    $player->sendMessage("§eNow cooldown for §d4 minutes");
                    PartnerAPI::setCooldown($player, "SnowBall");
                    PartnerAPI::setPartnerCooldown($player);
                    return true;
                } else {
                    $event->cancel();
                    $player->sendMessage("§cYou can't use Partner Item now, need to wait §d" . PartnerAPI::getPartnerCoolDown($player) . " seconds");
                }
            } else {
                $event->cancel();
                $player->sendMessage("§cYou can't use §dTeleportation Ball §cfor §d" . PartnerAPI::getCoolDown($player,"SnowBall") . " seconds");
            }
        }
    }


}