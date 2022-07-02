<?php

namespace vale\sage\demonic\ChunkBuster\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;

class ChunkBusterCommand extends Command{

    public function __construct() {
        parent::__construct("givechunkbuster", "give chunk buster", "", []);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if($sender instanceof Player){
            $this->giveChunkBusters($sender);
        }
    }

    private function giveChunkBusters(Player $player){
        $item = ItemFactory::getInstance()->get(138, 0, 1);//beacon
        $item->setCustomName("§l§bChunk Buster");
        $item->setLore(["§7Place this in your claimed faction land to destroy the chunk!\n\n§4WARNING: This will destroy the entire chunk!"]);
        $item->getNamedTag()->setString("ChunkBusters", "ChunkBusters");
        $inventory = $player->getInventory();
        if($inventory->canAddItem($item)){
            $inventory->addItem($item);
            $player->sendMessage("§aYou have been given a ChunkBusters!");
        } else {
            $player->sendMessage("§cYou don't have enough space in your inventory!");
        }
    }
}