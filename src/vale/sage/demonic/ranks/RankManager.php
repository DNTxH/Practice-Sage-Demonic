<?php
namespace vale\sage\demonic\ranks;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;
use vale\sage\demonic\ranks\rank\Rank;
use vale\sage\demonic\sessions\player\SessionPlayer;
use pocketmine\utils\TextFormat;


class RankManager
{
	public array $ranks = [
        0 => ["Peasant", TextFormat::GRAY],
        1 => ["Knight", TextFormat::AQUA],
        2 => ["Mage", TextFormat::LIGHT_PURPLE],
        3 => ["Noble", TextFormat::GOLD],
        4 => ["Lord", TextFormat::BLUE],
        5 => ["Emperor", TextFormat::RED],
        6 => ["Youtuber", TextFormat::RED],
        7 => ["Lucifer", TextFormat::DARK_PURPLE],
        8 => ["Helper", TextFormat::LIGHT_PURPLE],
        9 => ["Mod", TextFormat::DARK_PURPLE],
        10 => ["Admin", TextFormat::RED],
        11 => ["Management", TextFormat::AQUA],
        12 => ["Supervisor", TextFormat::BLUE],
        13 => ["CoOwner", TextFormat::GOLD],
        14 => ["Owner", TextFormat::DARK_RED],
        15 => ["Executive", TextFormat::LIGHT_PURPLE]
    ];
	
	public function __construct(
		private Loader $plugin
	)
	{
		$this->plugin->getServer()->getPluginManager()->registerEvents(new RankListener($this->plugin), $plugin);
	}

    public function getName(int $id) {
        return $this->ranks[$id][0];
    }

    public function get(string $name): ?int{
        foreach ($this->ranks as $rank => $data) {
            if (strtolower($data[0]) == strtolower($name)) {
                return $rank;
            }
        }
        return null;
    }


    public function getAll(): array{
        $ranks = [];
        foreach ($this->ranks as $rank) {
            $ranks[] = strtolower($rank[0]);
        }
        return $ranks;
    }

    public function updateNametag(SessionPlayer $session) {
		$player = $session->getPlayer();
		$rank = $session->getRank();
        #TODO Factions system
        $faction = $session->getFaction() !== null ? $session->getFaction()->getName() : "";
        $player->setNameTag("§r§f§l<§r" . $this->ranks[$rank][1] . $this->ranks[$rank][0] . "§r§l§f>\n§r" . $this->ranks[$rank][1] . "§r§f$faction " . $player->getName());
        $player->setScoreTag("§r§7" . $player->getHealth() . TextFormat::RED . " ❤");
    }

    public function getChatFormat(SessionPlayer $session) :string{
		$player = $session->getPlayer();
        $rank = $session->getRank();
        #TODO Factions system
        $faction = $session->getFaction() !== null ? $session->getFaction()->getName() : "";
        return $this->ranks[$rank][1] . "§r§f$faction §r§f§l<§r" . $this->ranks[$rank][1] . $this->ranks[$rank][0] . "§r§l§f>§r " . $this->ranks[$rank][1] . $player->getName() . "§r§8:§r " . $this->ranks[$rank][1];
    }

	public function getPlugin(): Loader{
		return $this->plugin;
	}

}