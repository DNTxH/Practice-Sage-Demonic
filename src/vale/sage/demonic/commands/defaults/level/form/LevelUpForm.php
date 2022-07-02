<?php

namespace vale\sage\demonic\commands\defaults\level\form;

use vale\sage\demonic\GenesisPlayer;
use vale\sage\demonic\levels\PlayerLevelUtils;
use form\CustomForm;
use form\element\Label;
use pocketmine\utils\TextFormat;

class LevelUpForm extends CustomForm {

    /**
     * @param GenesisPlayer $player
     */
    public function __construct(GenesisPlayer $player) {
        $title = TextFormat::DARK_AQUA . "How To Level Up";
        $elements = [];
        $elements[] = new Label("info", TextFormat::GRAY . "Level: " . TextFormat::WHITE . $player->getLevel() . " / " . TextFormat::RED . "100\n" . TextFormat::GRAY . "Level Experience: " . TextFormat::WHITE . $player->getLevelExperience() . " / " . TextFormat::RED . PlayerLevelUtils::calculateLevelXpRequirement($player) . "\n" . TextFormat::GREEN . "You can receive XP by doing\nany of the things listed below:\n" . TextFormat::GRAY . "• Killing Bosses" . TextFormat::DARK_AQUA . " (+100XP)\n" . TextFormat::GRAY . "• Find a meteor " . TextFormat::DARK_AQUA . "(+10XP)\n" . TextFormat::GRAY . "• Kill a mob " . TextFormat::DARK_AQUA . "(+1XP)\n" . TextFormat::GRAY . "• Kill a player " . TextFormat::DARK_AQUA . "(+5XP)\n" . TextFormat::GRAY . "• Break an ore block " . TextFormat::DARK_AQUA . "(+2XP)\n" . TextFormat::GRAY . "• Find a treasure chest " . TextFormat::DARK_AQUA . "(+50XP)");
        parent::__construct($title, $elements);
    }

}