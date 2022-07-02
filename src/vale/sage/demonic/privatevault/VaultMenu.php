<?php

namespace vale\sage\demonic\privatevault;

use vale\sage\demonic\Loader;
use muqsit\invmenu\InvMenu;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

class VaultMenu
{
    /** @var InvMenu */
    private InvMenu $invMenu;

    /**
     * @param Vault $vault
     */
	public function __construct(private Vault $vault){
		$this->invMenu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
		$this->invMenu->setName($this->vault->getusername() . "'s PV #" . $this->vault->getNumber());
		$this->invMenu->getInventory()->setContents($this->vault->getItems());
		$this->invMenu->setInventoryCloseListener(\Closure::fromCallable([$this, "onClose"]));
	}

    /**
     * @param Player $player
     * @return void
     */
	public function send(Player $player): void{
		$this->invMenu->send($player);
	}

    /**
     * @return InvMenu
     */
	public function getInvMenu(): InvMenu{
		return $this->invMenu;
	}

    /**
     * @param Player $player
     * @param Inventory $inventory
     * @return void
     */
	public function onClose(Player $player, Inventory $inventory): void{
		$viewers = $inventory->getViewers();
		foreach($viewers as $key => $viewer){
			if($viewer->getId() === $player->getId()){
				unset($viewers[$key]);
			}
		}
		if(empty($viewers)){
			$this->vault->setItems($inventory->getContents());
			Loader::getInstance()->getPrivateVaultDB()->unloadVault($this->vault);
		}
	}
}