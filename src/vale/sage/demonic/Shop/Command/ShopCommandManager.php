<?php

namespace vale\sage\demonic\Shop\Command;

use pocketmine\block\Flower;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use vale\sage\demonic\Shop\GuiManager\GuiManager;

class ShopCommandManager extends Command implements Listener
{
    public function __construct()
    {
        parent::__construct("shop", "Shop Command", "", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player) {
            GuiManager::OpenGui($sender,"Main");
        } else {
            for($id = 1;$id <= 1000;$id++){
                for($meta = 0;$meta <= 300;$meta++){
                    $item = ItemFactory::getInstance()->get($id,$meta);
                    $name = explode(" ",$item->getName());
                    if(in_array("Rose",$name)){
                        $sender->sendMessage("§aID: ".$id."\n§aMeta: ".$meta . $item->getName());
                    }
                }
            }
            $sender->sendMessage("nothing found");
        }
    }
}