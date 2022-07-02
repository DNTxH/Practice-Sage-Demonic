<?php

namespace vale\sage\demonic\privatevault;

use pocketmine\item\Item;

class Vault implements \JsonSerializable
{
    /** @var VaultMenu */
	private VaultMenu $menu;

    /** @var bool */
	private bool $loading = false;

    /** @var bool */
	private bool $unloading = false;

    /**
     * @param string $username
     * @param int $number
     * @param array $items
     */
	public function __construct(
		private string $username,
		private int $number,
		private array $items,
	){
		$this->menu = new VaultMenu($this);
	}

    /**
     * @return VaultMenu
     */
	public function getMenu(): VaultMenu{
		return $this->menu;
	}

    /**
     * @return string
     */
	public function getusername(): string{
		return $this->username;
	}

    /**
     * @return int
     */
	public function getNumber(): int{
		return $this->number;
	}

    /**
     * @param array $items
     * @return void
     */
	public function setItems(array $items): void{
		$this->menu->getInvMenu()->getInventory()->setContents($items);
		$this->items = $items;
	}

    /**
     * @return array
     */
	public function getItems(): array{
		return $this->items;
	}

    /**
     * @return string
     */
	public function getIdentifier(): string{
		return $this->username . "." . $this->number;
	}

    /**
     * @return array|mixed
     */
	public function jsonSerialize(){
		return $this->items;
	}

    /**
     * @param bool $loading
     * @return void
     */
	public function setLoading(bool $loading): void{
		$this->loading = $loading;
	}

    /**
     * @param bool $unloading
     * @return void
     */
	public function setUnloading(bool $unloading): void{
		$this->unloading = $unloading;
	}

    /**
     * @return bool
     */
	public function isLoading(): bool{
		return $this->loading;
	}

    /**
     * @return bool
     */
	public function isUnloading(): bool{
		return $this->unloading;
	}
}