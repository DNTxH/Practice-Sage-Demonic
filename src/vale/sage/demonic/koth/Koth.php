<?php 

namespace vale\sage\demonic\koth;

use vale\sage\demonic\koth\command\CurrentPositionCommand;
use vale\sage\demonic\koth\command\KothManagerCommand;
use vale\sage\demonic\koth\task\KothRunTask;
use vale\sage\demonic\koth\task\KothScoreboardTask;
use vale\sage\demonic\koth\utils\KothScoreboardUtils;
use vale\sage\demonic\koth\utils\KothUtils;
use vale\sage\demonic\Loader;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;

class Koth {

    public const POS_1 = "295:50:358";

    public const POS_2 = "295:80:358";

    public const WORLD = "world";

    public const KOTH_TIME = 200;

    public const WINNER_COMMAND = "say {name} has won koth! congratz to them or something idk";

    public bool $running = false;

    public int $timeLeft = 0;

    public string $capturing = "";

    public int $capture = 0;

    private KothUtils $kothUtils;

    private KothScoreboardUtils $kothScoreboardUtils;

    public function __construct() {
        $cmdMap = Loader::getInstance()->getServer()->getCommandMap();
        $cmdMap->registerAll("core", [
            new KothManagerCommand(),
            new CurrentPositionCommand()
        ]);
        $this->kothUtils = new KothUtils();
        $this->kothScoreboardUtils = new KothScoreboardUtils();
    }

    //TODO: replace this with a function that actually gets the players faction
    public function getFaction(Player $player) : string {
        return "oogabooga";
    }

    public function startKoth() : void {
        $this->running = true;
        Server::getInstance()->broadcastMessage("§l§aKOTH §r§7>> §fA game of §eKOTH §fhas been started!");
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new KothRunTask(), 20);
        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new KothScoreboardTask(), 20);
    }

    /**
     * @param string $name
     * @return array<Player>
     *
     * TODO: make sure all players on online in the array
     * TODO: Make  this actually interact with the database and not return every online player
     */
    public function getAllPlayersFromAFaction(string $name) : array {
        return Server::getInstance()->getOnlinePlayers();
    }

    public function getCurrentKothWinner() : ?string {
        if ($this->capturing !== "") return $this->capturing;
        return null;
    }

    public function strPos(string $pos) : Vector3 {
        $exp = explode(":", $pos);
        return new Vector3(
            (int)$exp[0],
            (int)$exp[1],
            (int)$exp[2]
        );
    }

    public function isInKothPosition(Position $pos) : bool {
        $world = $pos->getWorld();
        if ($world->getDisplayName() == self::WORLD) {
            $pos1 = $this->strPos(self::POS_1);
            $pos2 = $this->strPos(self::POS_2);
            $aabb = new AxisAlignedBB(
                min($pos1->getX(), $pos2->getX()),
                min($pos1->getY(), $pos2->getY()),
                min($pos1->getZ(), $pos2->getZ()),
                max($pos1->getX(), $pos2->getX()),
                max($pos1->getY(), $pos2->getY()),
                max($pos1->getZ(), $pos2->getZ())
            );
            return $aabb->isVectorInside($pos->asVector3());
       } return false;
    }

    public function sendKothScoreboard(Player $player) : void {
        $utils = $this->kothScoreboardUtils;
        $utils->removeScoreboard($player);
        $utils->clearLines($player);
        $utils->showScoreboard($player);
        $utils->addLine("§l§a§6    §c§r", $player);
        $utils->addLine("§l§aFlag", $player);
        if ($this->capture > 5) {
            $flag = "§l§eCAPTURED";
        } else if ($this->capturing !== "") {
            $flag = "§l§eCAPTURING";
        } else {
            $flag = "§l§eN / A";
        }
        $utils->addLine($flag, $player);
        $utils->addLine("§l§a     §r§c§r ", $player);
        $utils->addLine("§l§aCapping", $player);
        if ($this->capturing !== "") {
            $capper = $this->capturing;
        } else {
            $capper = "None";
        }
        $utils->addLine("§l§f".$capper, $player);
        $utils->addLine("§l§f§c     §a§r            §c  ", $player);
        $utils->addLine("§l§aTime Left", $player);
        $utils->addLine("§r§l§f".$this->kothUtils->secondsToCD($this->timeLeft), $player);
        $utils->addLine("§l§c§a§6    §r§c§r ", $player);
        $utils->addLine("§l§aDistance", $player);
        $utils->addLine("§l§f".floor($player->getPosition()->distance($this->strPos(self::POS_1)))."m", $player);
    }

}