<?php
namespace vale\sage\demonic\commands\types\subcommands;

use CortexPE\Commando\BaseSubCommand;
use pocketmine\player\Player;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use vale\sage\demonic\Loader;
use vale\sage\demonic\rewards\Rewards;

class TopMoneyOnlineSubCommand extends BaseSubCommand
{

	protected function prepare(): void
	{
		// TODO: Implement prepare() method.
	}

	/** @var array $topmoney */
	public array $topmoney = [];

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
	{
		if (!$sender instanceof Player) {
			return;
		}
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {

			$name = $player->getName();
			$session = Loader::getInstance()->getSessionManager()->getSession($player);
			if ($session !== null) {
				$balances = $session->getBalance();
				$this->topmoney[$name] = $balances;
			}
			$array_unique = array_unique($this->topmoney);
			asort($array_unique);
			$i = 0;
			#$player->sendMessage("§6~ §lTop Player Balances §r§6~");
			$player = $sender;
			foreach ($this->topmoney as $p => $balances) {
				if ($i < 10 && $balances) {
					$i++;
					switch ($i) {
						case 1:
							$player->sendMessage("§r§a§l1. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 2:
							$player->sendMessage("§r§a§l2. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 3:
							$player->sendMessage("§r§a§l3. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 4:
							$player->sendMessage("§r§a§l4. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 5:
							$player->sendMessage("§r§a§l5. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 6:
							$player->sendMessage("§r§a§l6. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 7:
							$player->sendMessage("§r§a§l7. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 8:
							$player->sendMessage("§r§a§l8. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 9:
							$player->sendMessage("§r§a§l9. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
						case 10:
							$player->sendMessage("§r§a§l10. §r§e§l" . $p . ": §r§a§l" . number_format($balances, 2));
							break;
					}
					unset($this->topmoney[$p]);
				}
			}
		}
	}
}