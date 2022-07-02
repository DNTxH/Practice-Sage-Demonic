<?php

namespace vale\sage\demonic\commands;

use pocketmine\Server;
use vale\sage\demonic\ChunkBuster\commands\ChunkBusterCommand;
use vale\sage\demonic\commands\defaults\admin\CommandSpy;
use vale\sage\demonic\commands\defaults\admin\ForceCommand;
use vale\sage\demonic\commands\defaults\collectionChest\CollectionChestCommand;
use vale\sage\demonic\commands\defaults\ColorCommand;
use vale\sage\demonic\commands\defaults\crates\CratesCommand;
use vale\sage\demonic\commands\defaults\CustomTellCommand;
use vale\sage\demonic\commands\defaults\enchants\EnchantCommand;
use vale\sage\demonic\commands\defaults\homes\DeleteHomeCommand;
use vale\sage\demonic\commands\defaults\homes\HomeCommand;
use vale\sage\demonic\commands\defaults\homes\ListHomeCommand;
use vale\sage\demonic\commands\defaults\inventory\InventorySeeCommand;
use vale\sage\demonic\commands\defaults\LagCommand;
use vale\sage\demonic\commands\defaults\level\LevelUpCommand;
use vale\sage\demonic\commands\defaults\privateVaults\PrivateVaultsCommand;
use vale\sage\demonic\commands\defaults\spawner\SpawnerCommand;
use vale\sage\demonic\commands\defaults\talent\TalentsCommand;
use vale\sage\demonic\commands\defaults\homes\SetHomeCommand;
use vale\sage\demonic\commands\defaults\teleport\SpawnCommand;
use vale\sage\demonic\commands\defaults\teleport\TeleportAcceptCommand;
use vale\sage\demonic\commands\defaults\teleport\TeleportDenyCommand;
use vale\sage\demonic\commands\types\mod\ClearCommand;
use vale\sage\demonic\commands\types\mod\CustomEnchantCommand;
use vale\sage\demonic\commands\types\mod\GamemodeCommand;
use vale\sage\demonic\commands\types\mod\SetBalanceCommand;
use vale\sage\demonic\commands\types\mod\SetSoulsCommand;
use vale\sage\demonic\commands\types\sender\AgeCommand;
use vale\sage\demonic\commands\types\sender\BalanceCommand;
use vale\sage\demonic\commands\types\sender\BalTopCommand;
use vale\sage\demonic\commands\types\sender\BlessCommand;
use vale\sage\demonic\commands\types\sender\CBragCommand;
use vale\sage\demonic\commands\types\sender\DisposalCommand;
use vale\sage\demonic\commands\types\sender\EnchanterCommand;
use vale\sage\demonic\commands\defaults\enderChest\EnderChestCommand;
use vale\sage\demonic\commands\types\sender\EnvoyCommand;
use vale\sage\demonic\commands\types\sender\FeedCommand;
use vale\sage\demonic\commands\types\sender\FlyCommand;
use vale\sage\demonic\commands\types\sender\JackPotCommand;
use vale\sage\demonic\commands\types\sender\KitCommand;
use vale\sage\demonic\commands\types\sender\PayCommand;
use vale\sage\demonic\commands\types\mod\SetRankCommand;
use vale\sage\demonic\commands\types\sender\PotsCommand;
use vale\sage\demonic\commands\types\sender\ReclaimCommand;
use vale\sage\demonic\commands\types\sender\RepairCommand;
use vale\sage\demonic\commands\types\sender\SoulsCommand;
use vale\sage\demonic\commands\types\sender\SplitSoulsCommand;
use vale\sage\demonic\commands\types\sender\StatusCommand;
use vale\sage\demonic\commands\types\sender\TagCommand;
use vale\sage\demonic\commands\types\sender\TinkererCommand;
use vale\sage\demonic\commands\types\sender\WarpCommand;
use vale\sage\demonic\commands\types\sender\WithdrawCommand;
use vale\sage\demonic\commands\types\sender\XpCommand;
use vale\sage\demonic\commands\types\sender\XPWithdrawCommand;
use vale\sage\demonic\commands\types\mod\StopCommand;
use vale\sage\demonic\factions\command\FactionCommand;
use vale\sage\demonic\Loader;
use vale\sage\demonic\commands\defaults\teleport\TeleportCommand;
use vale\sage\demonic\Partner\PartnerCommand;
use vale\sage\demonic\Shop\Command\ShopCommandManager;
use vale\sage\demonic\staff\StaffModeCommand;
use vale\sage\demonic\commands\defaults\sets\CustomArmorCommand;
use vale\sage\demonic\commands\defaults\sets\CustomWeaponCommand;

class CommandManager
{

	public static function init(): void
	{
		foreach (Server::getInstance()->getCommandMap()->getCommands() as $command) {
			$command->setPermission(null);
		}
			Loader::getInstance()->getServer()->getCommandMap()->registerAll("Sage", [
				new StopCommand(Loader::getInstance()),
				new BalanceCommand(Loader::getInstance(), "balance","view your balance",["bal","money"]),
				new PayCommand(Loader::getInstance(), "pay"),
				new WithdrawCommand(Loader::getInstance(), "withdraw"),
				new BalTopCommand(Loader::getInstance(), "baltop","view the top richest players",["topmoney"]),
				new BlessCommand(Loader::getInstance(),"bless"),
				new XPWithdrawCommand(Loader::getInstance(),"xpbottle"),
				new XpCommand(),
				new KitCommand(Loader::getInstance(),"kit"),
				new ReclaimCommand(Loader::getInstance(),"reclaim"),
				new TagCommand(Loader::getInstance(), "tag"),
				new SetRankCommand(Loader::getInstance(),"setrank"),
				new RepairCommand(Loader::getInstance(), "fix","fix your items",["repair"]),
				new SoulsCommand(Loader::getInstance(), "souls"),
				new EnvoyCommand(Loader::getInstance(),"envoy"),
				new WarpCommand(Loader::getInstance(), "warp"),
				new CBragCommand(Loader::getInstance(), "seebrag","see bragging",["cbrag"]),
				new FactionCommand(Loader::getInstance(), "f", "factions lol", ["factions"]),
				new AgeCommand("age","check planets age","lol"),
				new JackPotCommand(Loader::getInstance(), "jackpot"),
				new StatusCommand("status","check your status","ok",["ping","tps","lag"]),
				new PotsCommand("pots"),
				new SetBalanceCommand(Loader::getInstance(),"setbalance"),
				new SetSoulsCommand(Loader::getInstance(), "setsouls"),
				new ClearCommand(Loader::getInstance(),"clear"),
				new GamemodeCommand(Loader::getInstance(),"gamemode"),
				new CustomEnchantCommand(Loader::getInstance(),"ce"),
				new SplitSoulsCommand(Loader::getInstance(),"splitsouls"),
				new TinkererCommand("tinkerer"),
				new DisposalCommand("dispose","","lol",["disposal","trash","bin"]),
				new EnchanterCommand("enchanter"),
				new FeedCommand("feed"),
				new FlyCommand("fly"),
                new LevelUpCommand(),
                new TalentsCommand(),
                new LagCommand(),
                new CustomTellCommand(),
                // new StaffModeCommand(),
                new CommandSpy(),
                new ColorCommand(),
                new CratesCommand(),
                new EnderChestCommand(),
                new PrivateVaultsCommand(),
                new InventorySeeCommand(),
                new EnchantCommand(),
                new SpawnerCommand(),
                new SetHomeCommand(),
                new DeleteHomeCommand(),
                new ListHomeCommand(),
                new HomeCommand(),
                new ForceCommand(),
                new SpawnCommand(),
                new TeleportCommand(),
                new TeleportAcceptCommand(),
                new TeleportDenyCommand(),
                new CollectionChestCommand(),
                new StaffModeCommand(),
                new PartnerCommand(),
                new ChunkBusterCommand(),
                new ShopCommandManager(),
                new CustomArmorCommand(),
                new CustomWeaponCommand()
			]);
		}
	}