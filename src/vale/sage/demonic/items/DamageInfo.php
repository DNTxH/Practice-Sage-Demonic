<?php
namespace vale\sage\demonic\items;


/**
 * Class DamageInfo
 * @package vale\sage\demonic\items
 * @author Jibix
 * @date 06.01.2022 - 17:38
 * @project Genesis
 */
class DamageInfo{

    const CUSTOM_MODIFIER = 11;

    public function __construct(private array $entities = []){}

    public function getIncrease(string $entity): float{
        if (!empty($this->entities["increase"])) {
            return $this->entities["increase"][$entity] ?? $this->entities["increase"]["default"] ?? 0;
        } else {
            return 0;
        }
    }

    public function getDecrease(string $entity): float{
        if (!empty($this->entities["decrease"])) {
            return $this->entities["decrease"][$entity] ?? $this->entities["decrease"]["default"] ?? 0;
        } else {
            return 0;
        }
    }
}