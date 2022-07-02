<?php

namespace vale\sage\demonic;

use pocketmine\permission\Permissible;
use pocketmine\utils\TextFormat;
use vale\sage\demonic\items\armor\BaseArmorItem;
use vale\sage\demonic\ranks\RankManager;
use vale\sage\demonic\sessions\SessionManager;
use vale\sage\demonic\utils\Utils;
use pocketmine\inventory\CallbackInventoryListener;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use vale\sage\demonic\database\Database;


/**
 * Class GenesisPlayer
 * @package core
 * @author Jibix
 * @date 08.01.2022 - 18:45
 * @project Genesis
 */
class GenesisPlayer extends Player{

    /** @var bool */
    private $loaded = false;

    public ?string $armor = null;

    /** @var int */
    private int $level = 1;

    /** @var int  */
    private int $levelExperience = 0;

    /** @var int */
    private int $talentPoints = 1;

    /** @var int  */
    private int $dodgeTalentLevel = 0;

    /** @var int */
    private int $sellTalentLevel = 0;

    /** @var int */
    private int $xpTalentLevel = 0;

    /** @var int */
    private int $pvpOutgoingTalentLevel = 0;

    /** @var int */
    private int $pveTalentLevel = 0;

    /** @var int */
    private int $pvpIncomingTalentLevel = 0;

    /** @var int */
    private int $minersFortuneTalentLevel = 0;

    /** @var int */
    private int $luckyTalentLevel = 0;

    /** @var int */
    private int $onlineTime = 0;

    /**
     * Function initEntity
     * @param CompoundTag $nbt
     */
    public function initEntity(CompoundTag $nbt): void{
        parent::initEntity($nbt);

        $this->armorInventory->getListeners()->add(new CallbackInventoryListener(
            function (Inventory $inventory, int $slot, Item $oldItem): void{
                if (($armor = Utils::wearFullArmorSet($this)) instanceof BaseArmorItem && (empty($this->armor) || $this->armor !== $armor->getColoredName())) {
                    $this->armor = $armor->getColoredName();
                    $this->sendMessage("§l{$this->armor}§a has been activated!");
                    $armor->applyFullArmor($this);
                } elseif (!Utils::wearFullArmorSet($this) instanceof BaseArmorItem && !empty($this->armor)) {
                    $this->sendMessage("§l{$this->armor}§c has been deactivated!");
                    $this->armor = null;
                }
            },
            function (Inventory $inventory, array $oldContents): void{}
        ));
    }
    
    public function load() : void {
        Database::queryAsync("SELECT * FROM core_players WHERE xuid = ?", "s", [$this->getXuid()], function (array $rows) {
            if(!$this->isOnline()) return;

            if($row = $rows[0] ?? null) {
                unset($row);

                foreach($rows as $row) {
                    $this->level = $row["level"];
                    $this->levelExperience = $row["experience"];
                    $this->talentPoints = $row["talentPoints"];
                    $this->dodgeTalentLevel = $row["dodgeTalentLevel"];
                    $this->sellTalentLevel = $row["sellTalentLevel"];
                    $this->xpTalentLevel = $row["xpTalentLevel"];
                    $this->pvpOutgoingTalentLevel = $row["pvpOutgoingTalentLevel"];
                    $this->pveTalentLevel = $row["pveTalentLevel"];
                    $this->pvpIncomingTalentLevel = $row["pvpIncomingTalentLevel"];
                    $this->minersFortuneTalentLevel = $row["minersFortuneTalentLevel"];
                    $this->luckyTalentLevel = $row["luckyTalentLevel"];
                    $this->onlineTime = $row["onlineTime"];
                }

                $this->loaded = true;
                return;
            }
        });

        Database::queryAsync("INSERT INTO core_players(xuid, username) VALUES(?, ?)", "ss", [$this->getXuid(), $this->getName()]);
    }

    /**
     * @return bool
     */
    public function isLoaded() : bool {
        return $this->loaded;
    }

    /**
     * @return int
     */
    public function getLevel() : int {
        return $this->level;
    }

    /**
     * @param int $amount
     */
    public function increaseLevel(int $amount = 1) : void {
        $this->level += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.level = core_players.level + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getLevelExperience() : int {
        return $this->levelExperience;
    }

    public function increaseLevelExperience(int $amount = 1) : void {
        $this->levelExperience += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.experience = core_players.experience + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    public function resetLevelExperience() : void {
        $this->levelExperience = 0;
        Database::queryAsync("UPDATE core_players SET core_players.experience = 0 WHERE xuid = ?", "s", [$this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getTalentPoints() : int {
        return $this->talentPoints;
    }

    /**
     * @param int $amount
     */
    public function increaseTalentPoints(int $amount = 1) : void {
        $this->talentPoints += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.talentPoints = core_players.talentPoints + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @param int $amount
     */
    public function decreaseTalentPoints(int $amount = 1) : void {
        $this->talentPoints -= $amount;
        Database::queryAsync("UPDATE core_players SET core_players.talentPoints = core_players.talentPoints - ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getDodgeTalentLevel() : int {
        return $this->dodgeTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increaseDodgeTalentLevel(int $amount = 1) : void {
        $this->dodgeTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.dodgeTalentLevel = core_players.dodgeTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getSellTalentLevel() : int {
        return $this->sellTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increaseSellTalentLevel(int $amount = 1) : void {
        $this->sellTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.sellTalentLevel = core_players.sellTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getXpTalentLevel() : int {
        return $this->xpTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increaseXpTalentLevel(int $amount = 1) : void {
        $this->xpTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.xpTalentLevel = core_players.xpTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getPvpOutgoingTalentLevel() : int {
        return $this->pvpOutgoingTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increasePvpOutgoingTalentLevel(int $amount = 1) : void {
        $this->pvpOutgoingTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.pvpOutgoingTalentLevel = core_players.pvpOutgoingTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getPveTalentLevel() : int {
        return $this->pveTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increasePveTalentLevel(int $amount = 1) : void {
        $this->pveTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.pveTalentLevel = core_players.pveTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getPvpIncomingTalentLevel() : int {
        return $this->pvpIncomingTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increasePvpIncomingTalentLevel(int $amount = 1) : void {
        $this->pvpIncomingTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.pvpIncomingTalentLevel = core_players.pvpIncomingTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getMinersFortuneTalentLevel() : int {
        return $this->minersFortuneTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increaseMinersFortuneTalentLevel(int $amount = 1) : void {
        $this->minersFortuneTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.minersFortuneTalentLevel = core_players.minersFortuneTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getLuckyTalentLevel() : int {
        return $this->luckyTalentLevel;
    }

    /**
     * @param int $amount
     */
    public function increaseLuckyTalentLevel(int $amount = 1) : void {
        $this->luckyTalentLevel += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.luckyTalentLevel = core_players.luckyTalentLevel + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    /**
     * @return int
     */
    public function getPlayTime() : int {
        return $this->onlineTime;
    }

    /**
     * @param int $amount
     * @return void
     */
    public function increasePlayTime(int $amount = 1) : void {
        $this->onlineTime += $amount;
        Database::queryAsync("UPDATE core_players SET core_players.onlineTime = core_players.onlineTime + ? WHERE xuid = ?", "is", [$amount, $this->getXuid()]);
    }

    public function resetPlayTime() : void {
        $this->onlineTime = 0;
        Database::queryAsync("UPDATE core_players SET core_players.onlineTime = ? WHERE xuid = ?", "is", [0, $this->getXuid()]);
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasPermission($name) : bool {
        if(in_array($name, (array)Loader::getInstance()->getConfig()->getNested(Loader::getInstance()->getRankManager()->getName(Loader::getInstance()->getSessionManager()->getSession($this)->getRank()) . ".permissions"))) {
            return true;
        } else {
            return false;
        }
    }
}