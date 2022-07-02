<?php

namespace vale\sage\demonic\commands\defaults\level\form;

use vale\sage\demonic\commands\defaults\level\form\LevelUpForm;
use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\levels\PlayerLevelUtils;
use form\MenuForm;
use form\MenuOption;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class LevelUpCommandForm extends MenuForm {

    /**
     * @param GenesisPlayer $player
     */
    public function __construct(GenesisPlayer $player) {
        $title = TextFormat::DARK_AQUA . "Level Up Form";
        $elements = [];
        $text = TextFormat::GREEN . "Player Level: " . TextFormat::YELLOW . $player->getLevel() . "\n" . TextFormat::GRAY . "- " . self::calculateBar($player) . TextFormat::WHITE . " : " . TextFormat::YELLOW . $player->getLevelExperience() . TextFormat::GRAY . " / " . TextFormat::YELLOW . PlayerLevelUtils::calculateLevelXpRequirement($player);
        $elements[] = new MenuOption("How To Level Up");
        parent::__construct($title, $text, $elements);
    }

    /**
     * @param GenesisPlayer $player
     * @return string
     */
    private static function calculateBar(GenesisPlayer $player) : string {
        if(PlayerLevelUtils::calculateLevelXpRequirement($player) === -1) return TextFormat::GREEN . "||||||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 5) return TextFormat::RED . "||||||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 10) return TextFormat::GREEN . "|" . TextFormat::RED . "|||||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 15) return TextFormat::GREEN . "||" . TextFormat::RED . "||||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 20) return TextFormat::GREEN . "|||" . TextFormat::RED . "|||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 25) return TextFormat::GREEN . "||||" . TextFormat::RED . "||||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 30) return TextFormat::GREEN . "|||||" . TextFormat::RED . "|||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 35) return TextFormat::GREEN . "||||||" . TextFormat::RED . "||||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 40) return TextFormat::GREEN . "|||||||" . TextFormat::RED . "|||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 45) return TextFormat::GREEN . "||||||||" . TextFormat::RED . "||||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 50) return TextFormat::GREEN . "|||||||||" . TextFormat::RED . "|||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 55) return TextFormat::GREEN . "||||||||||" . TextFormat::RED . "||||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 60) return TextFormat::GREEN . "|||||||||||" . TextFormat::RED . "|||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 65) return TextFormat::GREEN . "||||||||||||" . TextFormat::RED . "||||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 70) return TextFormat::GREEN . "|||||||||||||" . TextFormat::RED . "|||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 75) return TextFormat::GREEN . "||||||||||||||" . TextFormat::RED . "||||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 80) return TextFormat::GREEN . "|||||||||||||||" . TextFormat::RED . "|||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 85) return TextFormat::GREEN . "||||||||||||||||" . TextFormat::RED . "||||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 90) return TextFormat::GREEN . "|||||||||||||||||" . TextFormat::RED . "|||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 95) return TextFormat::GREEN . "||||||||||||||||||" . TextFormat::RED . "||";
        if(($player->getLevelExperience() / PlayerLevelUtils::calculateLevelXpRequirement($player)) * 100 < 100) return TextFormat::GREEN . "|||||||||||||||||||" . TextFormat::RED . "|";
        return TextFormat::GREEN . "||||||||||||||||||||";
    }

    /**
     * @param Player $player
     * @param int $selectedOption
     */
    public function onSubmit(Player $player, int $selectedOption): void {
        if(!$player instanceof GenesisPlayer) return;
        $player->sendForm(new LevelUpForm($player));
    }

}