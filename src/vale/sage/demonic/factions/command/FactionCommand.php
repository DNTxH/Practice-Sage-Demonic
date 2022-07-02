<?php

declare(strict_types=1);

namespace vale\sage\demonic\factions\command;

use CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

use vale\sage\demonic\factions\command\subcommands\{CoordsSubCommand,
	DeinviteSubCommand,
	HelpSubCommand,
	CreateSubCommand,
	FactionWhoSubCommand,
	InviteSubCommand,
	JoinSubCommand,
	ListSubCommand,
	TopSubCommand,
	MapSubCommand,
	SetHomeSubCommand,
	SetWarpSubCommand,
	WarpSubCommand,
	HomeSubCommand,
	BanSubCommand,
	UnbanSubCommand,
	BanlistSubCommand,
	ShowClaimsSubCommand,
	LeaveSubCommand,
	ClaimSubCommand,
	UnclaimSubCommand,
	KickSubCommand,
	ModSubCommand,
	ColeaderSubCommand,
	LeaderSubCommand,
	ChatSubCommand,
	ChestSubCommand,
	WeeWooSubCommand,
	NotificationsSubCommand,
	InvseeSubCommand};


class FactionCommand extends BaseCommand
{

	public function prepare(): void
	{
		$this->registerSubCommand(new HelpSubCommand("help","",["h","?"]));
		$this->registerSubCommand(new CreateSubCommand("create"));
		$this->registerSubCommand(new FactionWhoSubCommand("who"));
		$this->registerSubCommand(new JoinSubCommand("join"));
		$this->registerSubCommand(new ListSubCommand("list"));
		$this->registerSubCommand(new TopSubCommand("top"));
		$this->registerSubCommand(new MapSubCommand("map"));
		$this->registerSubCommand(new CoordsSubCommand("coords"));
		$this->registerSubCommand(new SetHomeSubCommand("sethome"));
		$this->registerSubCommand(new HomeSubCommand("home"));
		$this->registerSubCommand(new FactionWhoSubCommand("title"));
		$this->registerSubCommand(new BanSubCommand("ban"));
		$this->registerSubCommand(new UnbanSubCommand("unban"));
		$this->registerSubCommand(new BanlistSubCommand("banlist"));
		$this->registerSubCommand(new FactionWhoSubCommand("lowpower"));
		$this->registerSubCommand(new ShowClaimsSubCommand("showclaims"));
		$this->registerSubCommand(new LeaveSubCommand("leave"));
		$this->registerSubCommand(new InviteSubCommand("invite"));
		$this->registerSubCommand(new DeinviteSubCommand("deinvite"));
		$this->registerSubCommand(new ClaimSubCommand("claim"));
		$this->registerSubCommand(new UnclaimSubCommand("unclaim"));
		$this->registerSubCommand(new KickSubCommand("kick"));
		$this->registerSubCommand(new ModSubCommand("mod"));
		$this->registerSubCommand(new ColeaderSubCommand("coleader"));
		$this->registerSubCommand(new LeaderSubCommand("leader"));
		$this->registerSubCommand(new ChatSubCommand("chat"));
		$this->registerSubCommand(new WarpSubCommand("warp"));
		$this->registerSubCommand(new SetWarpSubCommand("setwarp"));
		$this->registerSubCommand(new FactionWhoSubCommand("perms"));
		$this->registerSubCommand(new FactionWhoSubCommand("upgrades"));
		$this->registerSubCommand(new ChestSubCommand("chest"));
		$this->registerSubCommand(new WeeWooSubCommand("weewoo"));
		$this->registerSubCommand(new InvseeSubCommand("invsee"));
		$this->registerSubCommand(new NotificationsSubCommand("notifications"));
		$this->registerSubCommand(new FactionWhoSubCommand("wild"));

	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		$message = [
				"§r§d_____________.[ §r§bFactions Help (1/3) ].§r§d_____________",
				"§r§b/f help,h,? §r§d[page = 1] §r§7display a help message",
				"§r§b/f create <name> §r§7create a new faction",
				"§r§b/f who <name> §r§7show faction information",
				"§r§b/f tag <name> §r§7change faction name",
				"§r§b/f join <name> §r§7join a faction",
				"§r§b/f list §r§7list all factions",
				"§r§b/f top §r§7view richest factions",
				"§r§b/f map §r§7map of the surrounding area",
				"§r§b/f sethome §r§7set faction home",
				"§r§b/f home §r§7go to faction home",
				"§r§b/f title <player> <title> §r§7set a player's title",
				"§r§b/f ban §r§7ban a player from faction",
				"§r§b/f unban §r§7unban a player from faction",
				"§r§b/f banlist §r§7see all banned players",
				"§r§b/f lowpower §r§7see player's with power under max in faction",
				"§r§b/f coords §r§7broadcast location to faction",
				"§r§b/f showclaims §r§7list all claims from faction"
			];
			foreach ($message as $line) {
				$sender->sendMessage($line);
			}
	}
}