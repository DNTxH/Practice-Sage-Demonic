<?php

namespace vale\sage\demonic\commands;

use pocketmine\command\CommandSender;

abstract class SubCommand{

    /** @var array $aliases */
    private $aliases = [];
    /** @var string $description */
    private $description;
    /** @var string $name */
    private $name;
    /** @var string $usage */
    private $usage;

    public function __construct(array $aliases, string $description, string $usage){
        $this->aliases = array_map("strtolower", $aliases);
        $this->description = $description;
        $this->name = array_shift($aliases);
        $this->usage = $usage;
    }

    public function getAlises(): array{
        return $this->aliases;
    }

    public function getDescription(): string{
        return $this->description;
    }

    public function getName(): string{
        return $this->name;
    }

    public function getUsage(): string{
        return $this->usage;
    }

    public function onCommand(CommandSender $sender, array $args): void{}
}