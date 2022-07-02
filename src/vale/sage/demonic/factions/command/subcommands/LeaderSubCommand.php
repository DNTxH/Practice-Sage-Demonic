<?php
namespace vale\sage\demonic\factions\command\subcommands;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\args\TestArg;
use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;


class LeaderSubCommand extends BaseSubCommand
{

	private $transferTo;
	private array $transfer = [];

	protected function prepare(): void
	{
		$this->registerArgument(0, new RawStringArgument("name",true));
	}

	public function onRun(CommandSender $sender, string $commandLabel, array $args): void {
		if(!$sender instanceof Player) {
			return;
		}
		if(!isset($args["name"])){
			$sender->sendMessage("§r§c§l(!) §r§cInvalid usage, example: \n §r§b/f coleader §r§d<name;confirm:string>");
			return;
		}
		$session = Loader::getInstance()->getSessionManager()->getSession($sender);
		if($session->getFaction() === null) {
			$sender->sendMessage("§r§c§l(!) §r§cYou do not have a Faction!");
			return;
		}
		$f = $session->getFaction();
		if(in_array($sender->getName(), $this->transfer, true)) {
			$p = $this->transferTo[$sender->getName()];
                
			if($args["name"] === "confirm") {
				$f->setLeader($p);
				$f->setRank($sender->getName(), "coleader");
				$f->setRank($p, "owner");

				foreach($f->getAllMembers() as $member) {
					$m = Server::getInstance()->getPlayerExact($member);

					if($m instanceof Player) {
						$m->sendMessage("§r§c§l(!) §r§c" . $sender->getName() . " transfered Faction Ownership to " . $p . "!");
					}
				}
                unset($this->transfer[$sender->getName()]);
                unset($this->transferTo[array_search($sender->getName(), $this->transferTo)]);
			} else {
				$sender->sendMessage(Core::PREFIX . "Cancelled faction owner transfership.");
				unset($this->transfer[$sender->getName()]);
				unset($this->transferTo[array_search($sender->getName(), $this->transferTo)]);
			}
		} else {
			if($f->isMember($args["name"])) {
				$sender->sendMessage(Core::ERROR_PREFIX . "Player {$args["name"]} is not from your faction!");
				return;
			} else {
				$sender->sendMessage("§c > Are you sure you want to transfer ownership of your faction? This cannot be undone in the future.\n§cType §e/f leader confirm§c to confirm!");
				$this->transfer[] = $sender->getName();
				$this->transferTo[$sender->getName()] = $args["name"];
			}
		}
	}
}