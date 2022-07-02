<?php

namespace vale\sage\demonic\Partner;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class PartnerCommand extends Command
{
    public function __construct() {
        parent::__construct("partner", "Open Partner GUI", "", []);
    }

    public function execute(CommandSender $sender, string $label, array $args): bool{
        if($sender instanceof Player){
            $gui = InvMenu::create(InvMenuTypeIds::TYPE_CHEST);
            $gui->setName("Partner Item");
            $partner = array("Ninja","Bard","SnowBall","HateFoo","Guardian","MeeZoid","ComboAbility","NotRamix","AntiTrap","TimeWarp");
            foreach ($partner as $value){
                $gui->getInventory()->addItem(PartnerAPI::getItem($value));
            }
            $gui->send($sender);
            $gui->setListener(function(InvMenuTransaction $transaction){
               return $transaction->discard();
            });
        }
        return true;
    }
}