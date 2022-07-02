<?php
namespace vale\sage\demonic\ChunkBuster\Listener;

use ChunkBuster\Task\ChunkBusterEndTask;
use ChunkBuster\Task\ChunkBusterTask;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\world\World;

class ChunkBusterEventListener implements Listener{

    private PluginBase $plugin;
    private array $player_limit = [];
    private array $cool_down = [];

    public function __construct(PluginBase $plugin){
        $this->plugin = $plugin;
    }


    public function onPlace(BlockPlaceEvent $event){
        $item = $event->getItem();
        $player = $event->getPlayer();
        $chunkX = $event->getBlock()->getPosition()->getX() >> 4;
        $chunkZ = $event->getBlock()->getPosition()->getZ() >> 4;
        $world = $player->getWorld();
        if($item->getNamedTag()->getTag("ChunkBusters") !== null){
//            $faction = $this->plugin->getServer()->getPluginManager()->getPlugin("FactionsPro");
//            $ClaimApi = new Claim($chunkX,$chunkZ,$faction);
//            $select_faction = $ClaimApi->getFaction();
//            if(!(in_array($player,$select_faction->getMembers))){
//                $player->sendMessage("§4You can't place blocks in other player's faction!");
//            } else {
                if($this->isLimited($player) === true) {
                    $player->sendMessage("§4You need to wait until the previous ChunkBuster is finished!");
                    $event->cancel();
                } else {
                    if($this->isCoolDown($player)){
                        $time_left = $this->getCoolDownTime($player);
                        $minutes = $time_left[0];
                        $seconds = $time_left[1];
                        $player->sendMessage("§4You need to wait §e{$minutes}§4:§e{$seconds}§4 before you can use another ChunkBuster!");
                        $event->cancel();
                    } else {
                        $this->clearChunk($world, $chunkX, $chunkZ, $player);
                        $player->getInventory()->removeItem($item->setCount(1));
                    }
                }
//            }
        }
    }

    private function clearChunk(World $world,int $chunkX,int $chunkZ,Player $player){
        $count = 0;
        for ($y = 256; $y >= 0; $y--) {
            for ($x = (($chunkX * 16) + 16); $x >= ($chunkX * 16); $x--) {
                for ($z = (($chunkZ * 16) + 16); $z >= ($chunkZ * 16); $z--) {
                    $block = $world->getBlockAt($x, $y, $z);
                    if($block->getId() !== 0){
                        if($block->getId() !== 7) {
                            $count = $count + 0.1;
                            $this->plugin->getScheduler()->scheduleDelayedTask(new ChunkBusterTask($block), 20 + $count);
                        }
                    }
                }
            }
        }
        $this->player_limit[$player->getName()] = $player->getName();
        $this->plugin->getScheduler()->scheduleDelayedTask(new ChunkBusterEndTask($this,$player), 20 + $count);
    }

    public function removeLimit(Player $player){
        $playerName = $player->getName();
        if(in_array($playerName,$this->player_limit)){
            unset($this->player_limit[$playerName]);
            $this->addCoolDown($player);
        }
    }

    public function isLimited(Player $player): bool
    {
        $playerName = $player->getName();
        if(in_array($playerName,$this->player_limit)){
            return true;
        }
        return false;
    }


    public function getCooldownTime(Player $player): bool|array
    {
        $playerName = $player->getName();
        if(in_array($playerName,array_keys($this->cool_down))){
            $time = $this->cool_down[$playerName];
            $time_now = time();
            $time_left = $time - $time_now;
            $minutes = floor($time_left / 60);
            $seconds = $time_left - ($minutes * 60);
            return array($minutes,$seconds);
        }
        return false;
    }

    public function isCoolDown(Player $player){
        $playerName = $player->getName();
        if(in_array($playerName,array_keys($this->cool_down))){
            $time = $this->cool_down[$playerName];
            if($time < time()){
                unset($this->cool_down[$playerName]);
                return false;
            } else {
                return true;
            }
        }
        return false;
    }

    public function addCoolDown(Player $player){
        $playerName = $player->getName();
        $this->cool_down[$playerName] = time() + (20 * 60);//20 minutes
    }

    public static function giveChunkBuster(Player $player){
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