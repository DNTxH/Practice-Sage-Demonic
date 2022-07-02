<?php namespace vale\sage\demonic\slotbot;

use vale\sage\demonic\Loader;
use vale\sage\demonic\slotbot\commands\SlotBotCommand;
use vale\sage\demonic\slotbot\commands\TicketCommand;
use vale\sage\demonic\slotbot\sessions\SessionManager;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\NoSuchTagException;
use pocketmine\player\Player;
//slotbot by taco uwu
class SlotBotManager {

    public const SLOTBOT_MENU_TITLE = "§d§lGenesis§8PvP §7: §5SlotBot";

    public SessionManager $slotBotSessionManager;

    public function __construct() {
        $this->slotBotSessionManager = new SessionManager();
        Loader::getInstance()->getServer()->getPluginManager()->registerEvents(new SlotBotSessionEvent(), Loader::getInstance());
        Loader::getInstance()->getServer()->getCommandMap()->registerAll("core", [
            new SlotBotCommand(),
            new TicketCommand()
        ]);
    }

    public function strToItem(string $item) : Item {
        $explode = explode(":", $item);
        $name = false;
        if (count($explode) > 3) $name = true;
        $item = ItemFactory::getInstance()->get(
            (int)$explode[0],
            (int)$explode[1],
            (int)$explode[2]
        );
        if ($name) $item->setCustomName($explode[3]);
        $lore = false;
        if (count($explode) > 3) $lore = true;
        if ($lore) $item->setLore(explode("\n", $explode[4]));
        return $item;
    }

    public function openMenuForPlayer(Player $player) : void {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $inv = $menu->getInventory();
        $menu->setName(self::SLOTBOT_MENU_TITLE);
        // just had to be the format that sumxprove wanted to copy from another server lol
        $map = [
            ItemIds::BEACON.":0:1:§r§7|| §fLoot Table §r§l§7||:§r§7See the prizes that you can win by clicking this!",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::PAPER.":0:1:§r§l§7|| §bAdd Tickets §7||:§r§7Add tickets into the tickets slot by clicking this!",
            //2
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            //3
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":5:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::MAGMA_CREAM.":1:1:§r§7§l|| §aStart Rolling §7||:§r§7Make the bot start rolling after adding at least 1 ticket!",
            //4
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            //5
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":0:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            //6
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::STAINED_GLASS.":14:1:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)",
            ItemIds::STAINED_GLASS.":14:2:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)",
            ItemIds::STAINED_GLASS.":14:3:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)",
            ItemIds::STAINED_GLASS.":14:4:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)",
            ItemIds::STAINED_GLASS.":14:5:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)",
            ItemIds::STAINED_GLASS.":15:1",
            ItemIds::WRITABLE_BOOK.":0:1:§r§l§7|| §dHow to play §l§7||:§r§7Hello and welcome to the §l§aSlot Bot Machine!\n§r§7You can win cool rewards here! We wish you good luck!!\n§r§7Here are the steps to make life easier for you:\n\n§r§1. Click the §l§bPaper §r§7or the §r§l§bAdd Tickets §r§7item\nin order to make the machine start adding tickets.\n\n§r§72. Once you've at least added 1 ticket then click the §l§aGreen Dye\n§r§7or the §l§aStart Rolling§r§7 item in order to start the machine\n§r§7and claim your prizes!\n\n§r§8(§l§c!§r§8) §r§7You can check out the cool rewards you can win\n§r§7by clicking the §l§fBeacon §r§7or the §l§fLoot Table §r§7item. §8(§l§c§r§8)",
        ];
        $slots = (count($inv->getContents(true)) - 1);
        foreach (array_reverse($map) as $slot) {
            $inv->setItem($slots, $this->strToItem($slot));
            $slots--;
        }
        $menu->send($player);
        $menu->setListener(function(InvMenuTransaction $transaction) : InvMenuTransactionResult {
            $item = $transaction->getItemClicked();
            $inv = $transaction->getAction()->getInventory();
            $player = $transaction->getPlayer();
            if (Loader::getSlotBotManager()->slotBotSessionManager->getSlotBotSession($player)->running) return $transaction->discard();
            $slot = $transaction->getAction()->getSlot();
            if ($item->getId() == ItemIds::STAINED_GLASS) {
                return $transaction->discard();
            }
            if ($item->getId() == ItemIds::PAPER and !$this->isRealTicket($item)) {
                foreach ($player->getInventory()->getContents() as $pSlot => $invI) {
                    if ($this->isRealTicket($invI)) {
                        $slot = $this->getOpenTicketSlot($inv);
                        if ($slot == null) {
                            return $transaction->discard();
                        }
                        $new = clone $invI;
                        $inv->setItem($slot, $new->setCount(1));
                        $player->getInventory()->setItem($pSlot, $invI->setCount($invI->getCount() - 1));
                    }
                }
            }
            if ($item->getId() == ItemIds::PAPER and $this->isRealTicket($item)) {
                $player->getInventory()->addItem($item);
                $inv->setItem($slot, $this->strToItem(ItemIds::STAINED_GLASS.":14:1:§r§7[§c§lEMPTY TICKET SLOT§7]:§r§7Please click the §l§bPaper §r§7or the\n§r§l§bAdd Tickets §r§7item in order to replace\n§r§7this with a ticket.\n\n§r§8(§l§c!§r§8)§7 If you don't know how to play\nclick the §l§dBook and Quil §r§7or the\n§r§l§dHow To Play §r§7item §r§8(§l§c!§r§8)"));
            }
            if ($item->getId() == ItemIds::MAGMA_CREAM) {
                $red = [47, 48, 49, 50, 51];
                $list = [];
                foreach ($red as $num) {
                    if ($inv->getItem($num)->getId() !== ItemIds::STAINED_GLASS) $list[] = $num;
                }
                if (count($list) < 1) return $transaction->discard();
                Loader::getSlotBotManager()->slotBotSessionManager->getSlotBotSession($player)->roll($player, $list, $inv);
            }
            return $transaction->discard();
        });
    }

    public function getOpenTicketSlot(Inventory $inv) : ?int {
        $slots = [
            47,
            48,
            49,
            50,
            51
        ];
        foreach ($slots as $slot) {
            if ($inv->getItem($slot)->getId() == ItemIds::STAINED_GLASS) return $slot;
        }
        return null;
    }

    public function getTicket() : Item {
        $item = ItemFactory::getInstance()->get(ItemIds::PAPER);
        $item->setCustomName("§l§aSlotbot Ticket");
        $item->setLore(["§r§7Congrats! You've found a §l§aSlotbot Ticket!\n§r§7Go to spawn and open the slotbot menu to get cool rewards!!"]);
        $item->getNamedTag()->setString("slotBotTicket", "teehee");
        return $item;
    }

    public function giveTicket(Player $player) : void {
        $item = $this->getTicket();
        $player->getInventory()->canAddItem($item) ? $player->getInventory()->addItem($item) : $player->getWorld()->dropItem($player->getPosition()->asVector3(), $item);
    }

    public function isRealTicket(Item $item) : bool {
        try {
            $e = $item->getNamedTag()->getTag("slotBotTicket");
            return $e !== null;
        } catch (NoSuchTagException $e) {
            return false;
        }
    }

}
