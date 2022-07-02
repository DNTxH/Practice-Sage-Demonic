<?php

declare(strict_types = 1);

namespace vale\sage\demonic\levels\gui;

use vale\sage\demonic\GenesisPlayer;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class TalentViewGui {

    /** @var GenesisPlayer */
    private GenesisPlayer $player;

    /** @var InvMenu  */
    private InvMenu $menu;

    public function __construct(GenesisPlayer $player) {
        $this->player = $player;

        $this->menu = InvMenu::create(InvMenu::TYPE_CHEST)->setName(TextFormat::GRAY . "Talent Points: " . $player->getTalentPoints())->setListener(InvMenu::readonly(\Closure::fromCallable([$this, "onTransaction"])))->setInventoryCloseListener(function (Player $player) {
            // do nothing
        });

        $this->menu->getInventory()->setItem(15, ItemFactory::getInstance()->get(446, 0, 1)->setCustomName(TextFormat::GOLD . "Dodge " . TextFormat::GRAY . "(Shield)\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getDodgeTalentLevel() . " / " . TextFormat::RED . "25\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getDodgeTalentLevel() . "‰ Dodge Passive"));
        $this->menu->getInventory()->setItem(3, VanillaItems::EXPERIENCE_BOTTLE()->setCustomName(TextFormat::GOLD . "Increased XP Gain" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getXpTalentLevel() . " / " . TextFormat::RED . "75\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getXpTalentLevel() . "‰ XP Gain"));
        $this->menu->getInventory()->setItem(4, VanillaItems::DIAMOND_AXE()->setCustomName(TextFormat::GOLD . "Increased Outgoing PvE Damage" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getPveTalentLevel() . " / " . TextFormat::RED . "30\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getPveTalentLevel() . "‰ Outgoing PvE Damage"));
        $this->menu->getInventory()->setItem(5, VanillaItems::DIAMOND_SWORD()->setCustomName(TextFormat::GOLD . "Increased Outgoing PvP Damage" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getPvpOutgoingTalentLevel() . " / " . TextFormat::RED . "30\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getPvpOutgoingTalentLevel() . "‰ Outgoing PvP Damage"));
        $this->menu->getInventory()->setItem(11, VanillaItems::IRON_SWORD()->setCustomName(TextFormat::GOLD . "Decreased Incoming PvP Damage" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getPvpIncomingTalentLevel() . " / " . TextFormat::RED . "15\n\n" . TextFormat::GRAY . "-" . TextFormat::GREEN . $player->getPvpIncomingTalentLevel() . "‰ Incoming PvP Damage"));
        $this->menu->getInventory()->setItem(12, VanillaItems::PAPER()->setCustomName(TextFormat::GOLD . "Increased /sell Prices" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getSellTalentLevel() . " / " . TextFormat::RED . "45\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getSellTalentLevel() . "‰ /sell prices"));
        $this->menu->getInventory()->setItem(22, VanillaItems::GOLD_INGOT()->setCustomName(TextFormat::GOLD . "Improved Ore Drops" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getMinersFortuneTalentLevel() . " / " . TextFormat::RED . "25\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getMinersFortuneTalentLevel() . "‰ Chance Of Improved Drops"));
        $this->menu->getInventory()->setItem(14, VanillaBlocks::COAL()->asItem()->setCustomName(TextFormat::GOLD . "Larger Ore Drop Quantities" . "\n" . TextFormat::YELLOW . "Talent Level\n" . TextFormat::WHITE . $player->getLuckyTalentLevel() . " / " . TextFormat::RED . "25\n\n" . TextFormat::GRAY . "+" . TextFormat::GREEN . $player->getLuckyTalentLevel() . "‰ Chance Of Larger Quantity Drops"));
        $this->menu->getInventory()->setItem(13, VanillaItems::WRITTEN_BOOK()->setCustomName("§l§dGuide Menu")->setLore([
            "§8:: §dTalents Guide §8::",
            "§7Welcome to the Talent Menu as you can see",
            "§7There are 8 talents you can choose to put your talent points into",
            "§7Talents is what will make your in-game Experience different from others .",
            " ",
            "§71. §cClick/Tap §7the talent you would like to put a point into",
            "§7Try not to miss click as talents cannot be refunded",
            "§7You'll be able to §aspend §7talents you get from player leveling up",
            " ",
            "§7(§4!§7) If there are any bugs within this system please report it",
            "§4§lFailure §r§7to do so will result in a account §4§lTermination§r"]));

        $this->menu->send($player);
    }

    /**
     * @param DeterministicInvMenuTransaction $transaction
     */
    public function onTransaction(DeterministicInvMenuTransaction $transaction) {
        $item = $transaction->getItemClicked()->getId();

        switch($item) {
            case 446:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getDodgeTalentLevel() >= 25) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Dodge talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increaseDodgeTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Dodge talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::EXPERIENCE_BOTTLE:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getXpTalentLevel() >= 75) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum XP talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increaseXpTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your XP talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::DIAMOND_AXE:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getPveTalentLevel() >= 30) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum PvE talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increasePveTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your PvE talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::DIAMOND_SWORD:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getPvpOutgoingTalentLevel() >= 30) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Outgoing PvP talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increasePvpOutgoingTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Outgoing PvP talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::IRON_SWORD:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getPvpIncomingTalentLevel() >= 15) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Incoming PvP talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increasePvpIncomingTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Incoming PvP talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::PAPER:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getSellTalentLevel() >= 45) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Sell talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increaseSellTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Sell talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::GOLD_INGOT:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getMinersFortuneTalentLevel() >= 25) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Miners Fortune talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increaseMinersFortuneTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Miners Fortune talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;

            case ItemIds::COAL_BLOCK:
                if($this->player->getTalentPoints() >= 1) {
                    if($this->player->getLuckyTalentLevel() >= 25) {
                        $this->player->sendMessage(TextFormat::RED . "You have reached the maximum Lucky talent level!");
                        $this->player->removeCurrentWindow();
                        return;
                    }

                    $this->player->decreaseTalentPoints();
                    $this->player->increaseLuckyTalentLevel();
                    $this->player->sendMessage(TextFormat::GREEN . "Successfully upgraded your Lucky talent!");
                    $this->player->removeCurrentWindow();
                } else {
                    $this->player->removeCurrentWindow();
                    $this->player->sendMessage(TextFormat::RED . "Insufficient number of talent points!");
                }
            break;
        }
    }
}