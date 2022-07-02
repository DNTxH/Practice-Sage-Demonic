<?php

namespace vale\sage\demonic\Shop\BillingManager;

use FormAPI\CustomForm;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use vale\sage\demonic\Loader;

class BillingManager
{

    private Player $player;
    private float $price;
    private bool $is_sellable;
    private ?bool $is_Custom_Item;
    private int $id;
    private int $meta;
    private ?Item $item;

    public function __construct(Player $player,int $id,int $meta, float $price, ?bool $is_sellable = false, ?bool $is_Custom_Item = false,?Item $item = null)
    {
        $this->player = $player;
        $this->item = $item;
        $this->id = $id;
        $this->meta = $meta;
        $this->price = $price;
        $this->is_sellable = $is_sellable;
        $this->is_Custom_Item = $is_Custom_Item;
    }

    public function run(){
        if($this->is_sellable) {
            $form = new CustomForm(function (Player $player, ?array $data) {
                if ($data === null) {
                    return;
                }
                if ($data[0] === 0) {
                    $this->buy();
                }
                if ($data[0] === 1) {
                    $this->sell();
                }
            });
            $form->setTitle("Shop");
            $form->addDropdown("Select an option", ["buy", "sell"]);
            $this->player->sendForm($form);
        } else {
            $this->buy();
        }
    }

    public function sell(){
        $price = round(($this->price / 2),2);
        $player = $this->player;
        $item_inv = $player->getInventory()->getContents(false);
        $count = 0;
        if($this->is_Custom_Item){
            $item = $this->item;
            $item->setCount(1);
            $item_name = $item->getCustomName();
            foreach ($item_inv as $item_in){
                if($item_in === $item){
                    $count = $count + $item_in->getCount();
                }
            }
        } else {
            $item = ItemFactory::getInstance()->get($this->id,$this->meta);
            $item_name = $item->getCustomName();
            $item->setCount(1);
            $id = $item->getId();
            $meta = $item->getMeta();
            foreach ($item_inv as $item_in){
                if($item_in->getId() === $id and $item_in->getMeta() === $meta){
                    $count = $count + $item_in->getCount();
                }
            }
        }
        if($count < 1){
            $player->sendMessage("§4You don't have any of this item");
            return;
        }
        $form = new CustomForm(function (Player $player, $data) use ($price, $item,$count,$item_name){
            if($data === null){
                return true;
            }
            $quantity = $data[2];
            if($quantity > $count){
                $player->sendMessage("§4You don't have that many §e$item_name");
                return true;
            }
            $total_price = $quantity * $price;
            $player->getInventory()->removeItem($item->setCount($quantity));
            Loader::getInstance()->getSessionManager()->getSession($player)->addBalance($total_price);
            $player->sendMessage("§2You sold §4$quantity"."§f of §e$item_name"."§f for §2$$total_price");
            return true;
        });
        $form->setTitle("Sell Item");
        $form->addLabel("You're about to sell §e$item_name"."§f"."for §2$$price");
        $form->addLabel("Quantity item that you have:§4$count"."§f\nTotal Price you'll get(if sell all):§2$". ($count * $price));
        $form->addInput("Type the quantity that you want to sell");
        $player->sendForm($form);
    }

    private function buy(): void
    {
        if($this->is_Custom_Item){
            $item = $this->item;
            $item->setCount(1);
            $item_name = $item->getCustomName();
        } else {
            $item = ItemFactory::getInstance()->get($this->id,$this->meta,1);
            $item_name = $item->getName();
        }
        $price = $this->price;
        $player = $this->player;
        $inventory = $player->getInventory();
        $max_item = $this->MaxCanAdd($inventory,$item);
        if($max_item === 0){
            $player->sendMessage("§4You inventory is full!");
            return;
        }
        $money = Loader::getInstance()->getSessionManager()->getSession($player)->getBalance();
        $afford = (int)($money / $price);
        if($afford === 0){
            $player->sendMessage("§4You don't have enough money to buy §c[§e$item_name"."§c]");
            return;
        }
        $afford = min($afford,$max_item);
        $form = new CustomForm(function (Player $player, $data) use ($item_name, $price, $item,$max_item,$money,$afford){
            if($data === null){
                return true;
            }
            $quantity = $data[2];
            if($quantity > $afford){
                $player->sendMessage("§4You can't afford buy that many!");
                return true;
            }
            $total_price = $quantity * $price;
            $player->getInventory()->addItem($item->setCount($quantity));
            Loader::getInstance()->getSessionManager()->getSession($player)->setBalance(Loader::getInstance()->getSessionManager()->getSession($player)->getBalance() - $total_price);
            $player->sendMessage("§2You bought $quantity of $item_name for $$total_price");
            return true;
        });
        $form->setTitle("Buy Item");
        $form->addLabel("You're about to buy §e$item_name §f"."for $$price/per item");
        $form->addLabel("You can buy §4$afford"."§f items\nYour money: §2$$money");
        $form->addInput("Type the quantity that you want to buy");
        $player->sendForm($form);
    }

    private function MaxCanAdd(Inventory $inventory, Item $item): int
    {
        $item->setCount(0);
        $count = 0;
        for($i = 0; $i < $inventory->getMaxStackSize(); $i++){
            if($inventory->canAddItem($item->setCount($count))) {
                $count++;
            }else{
                return $count;
            }
        }
        return $count;
    }


}