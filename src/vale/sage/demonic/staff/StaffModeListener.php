<?php

namespace vale\sage\demonic\staff;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\type\InvMenuTypeIds;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\item\Dye;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\permission\BanEntry;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\Loader;

class StaffModeListener implements Listener {

    private array $itemUseCooldown = [];

    public function onUse(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $staffClass = new StaffManager();
        $item = $event->getItem();
        if ($staffClass->isInStaffMode($player) === true) {
            if (isset($this->itemUseCooldown[$player->getName()])) {
                if (time() - $this->itemUseCooldown[$player->getName()] < 2) return true;
            }
            $this->itemUseCooldown[$player->getName()] = time();
            $item = $event->getItem();
            if ($item->getNamedTag()->getTag("isvalid") !== null) {
                $tag = $item->getNamedTag()->getString("isvalid");
                switch ($tag) {
                    case "emerald":
                        $players = Loader::getInstance()->getServer()->getOnlinePlayers();
                        $chosen = $players[array_rand($players)];
                        $player->teleport($chosen->getPosition());
                        $player->sendMessage("§aYou have been teleported to " . $chosen->getName());
                        break;
                    case "compass":
                        $onlinePlayer = Loader::getInstance()->getServer()->getOnlinePlayers();
                        if (count($onlinePlayer) === 1) {
                            $player->sendMessage("§cThere is no one to teleport to!");
                            return true;
                        }
                        $form = new SimpleForm(function (Player $player, $data) use ($onlinePlayer) {
                            if ($data === null) {
                                return true;
                            }
                            $name = array_keys($onlinePlayer)[$data];
                            $select = $onlinePlayer[$name];
                            $player->teleport($select->getPosition());
                            $player->sendMessage("You teleported to " . $select->getName());
                        });
                        foreach ($onlinePlayer as $p) {
                            if ($p !== $player) {
                                $form->addButton($p->getName());
                            }
                        }
                        $form->setTitle("§aStaffMode Compass");
                        $form->setContent("§aSelect a player to teleport to");
                        $player->sendForm($form);
                        break;
                    case "dye":
                        if (in_array($player->getName(), Loader::$vanish)) {
                            unset(Loader::$vanish[array_search($player->getName(), Loader::$vanish)]);
                            $player->sendMessage("§aYou are unvanished to everyone!");
                            $player->getInventory()->getItemInHand()->setCustomName("§aVanish");
                            $this->unVanish($player);
                        } else {
                            Loader::$vanish[] = $player->getName();
                            $player->sendMessage("§aYou are vanished to everyone!");
                            $player->getInventory()->getItemInHand()->setCustomName("§aunVanish");
                            $this->reVanish($player);
                        }
                        break;
                }
                $event->cancel();
            }
        }
    }

    public function onDamage(EntityDamageByEntityEvent $event) : void {
        $player = $event->getEntity();
        $damager = $event->getDamager();
        $staffManager = new StaffManager();
        if ($player instanceof Player and $damager instanceof Player) {
            if ($staffManager->isInStaffMode($damager)) {
                $item = $damager->getInventory()->getItemInHand();
                if ($item->getNamedTag()->getTag("isvalid") !== null) {
                    switch ($item->getNamedTag()->getString("isvalid")) {
                        case "book":
                            $player_head = $player->getArmorInventory()->getHelmet();
                            $player_chest = $player->getArmorInventory()->getChestplate();
                            $player_legs = $player->getArmorInventory()->getLeggings();
                            $player_boots = $player->getArmorInventory()->getBoots();
                            $glass = ItemFactory::getInstance()->get(ItemIds::GLASS, 0, 1);
                            $inv_menu = InvMenu::create(InvMenuTypeIds::TYPE_DOUBLE_CHEST);
                            $inv_menu->getInventory()->setContents($player->getInventory()->getContents(true));
                            $inv_menu->setName("§a". $player->getName() . "'s Inventory");
                            for ($i = 36; $i <= 44; $i++) {
                                $inv_menu->getInventory()->setItem($i, $glass);
                            }
                            $inv_menu->getInventory()->setItem(45, $glass);
                            $inv_menu->getInventory()->setItem(46, $player_head);
                            $inv_menu->getInventory()->setItem(47, $glass);
                            $inv_menu->getInventory()->setItem(48, $player_chest);
                            $inv_menu->getInventory()->setItem(49, $glass);
                            $inv_menu->getInventory()->setItem(50, $player_legs);
                            $inv_menu->getInventory()->setItem(51, $glass);
                            $inv_menu->getInventory()->setItem(52, $player_boots);
                            $inv_menu->getInventory()->setItem(53, $glass);
                            $inv_menu->send($damager);
                            break;
                        case "ice":
                            if($player->isImmobile()){
                                $player->sendMessage(TextFormat::RED . "You're been unfreeze!");
                                $damager->sendMessage(TextFormat::GREEN . "You unfreeze " . $player->getName());
                                $player->setImmobile(false);
                            }
                            else{
                                $player->sendMessage(TextFormat::GREEN . "You have been frozen!");
                                $damager->sendMessage(TextFormat::RED . "You freeze " . $player->getName());
                                $player->setImmobile(true);
                            }
                            break;
                        case "skull":
                            $player_name = $player->getName();
                            $first_join = $player->getFirstPlayed();
                            $device = $player->getPlayerInfo()->getExtraData()["DeviceModel"];
                            $ping = $player->getNetworkSession()->getPing();
                            $ip = $player->getNetworkSession()->getIp();
                            $form = new CustomForm(function(Player $player,$data){

                            });
                            $form->setTitle("§aPlayer Info");
                            $form->addLabel("§aName: §f" . $player_name);
                            $form->addLabel("§aFirst Join: §f" . date("d/m/Y H:i:s", $first_join / 1000));
                            $form->addLabel("§aDevice: §f" . $device);
                            $form->addLabel("§aPing: §f" . $ping);
                            $form->addLabel("§aIP address: §f" . $ip);
                            $damager->sendForm($form);
                            break;
                    }
                    $event->cancel();
                }
            }
    	}
    }

    public function unVanish(Player $player): void{
        $player->getInventory()->getItemInHand()->setCustomName("§aVanish");
       Loader::getInstance()->getServer()->addOnlinePlayer($player);
    }

    public function reVanish(Player $player): void{
       $player->getInventory()->getItemInHand()->setCustomName("§aunVanish");
       Loader::getInstance()->getServer()->removeOnlinePlayer($player);
    }

    public function onQuit(PlayerQuitEvent $event) : void {
        $player = $event->getPlayer();
        $staffManager = new StaffManager();
        if (in_array($player->getName(), $staffManager->frozen)) {
            Loader::getInstance()->getServer()->getNameBans()->add(new BanEntry($player->getName()));
            Loader::getInstance()->getServer()->broadcastMessage("§l§c   \n§r§7[§dGenesis§7] §r§c". $player->getName() . " was banned for logging out whilst frozen.");
        }
        if ($staffManager->isInStaffMode($player)) {
            $staffManager->unsetFromStaffMode($player);
        }
    }

    public static function update() {
        $staff = Loader::$staffMode;
        $online = Loader::getInstance()->getServer()->getOnlinePlayers();
        foreach ($online as $p){
            if(in_array($p->getName(), $staff) === false){
                foreach ($staff as $s){
                    $p->hidePlayer(Loader::getInstance()->getServer()->getPlayerExact($s));
                }
            } else {
                foreach ($staff as $s){
                    $p->showPlayer(Loader::getInstance()->getServer()->getPlayerExact($s));
                }
            }
        }
    }

}