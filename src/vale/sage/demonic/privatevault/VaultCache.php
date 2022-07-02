<?php

namespace vale\sage\demonic\privatevault;

class VaultCache
{
    /** @var array */
	private static array $cache = [];

    /**
     * @param Vault $vault
     * @return void
     */
	public static function addToCache(Vault $vault): void{
		self::$cache[$vault->getIdentifier()] = $vault;
	}

    /**
     * @param Vault $vault
     * @return void
     */
	public static function removeFromCache(Vault $vault): void{
		unset(self::$cache[$vault->getIdentifier()]);
	}

    /**
     * @param string $identifier
     * @return Vault|null
     */
	public static function getFromCache(string $identifier): ?Vault{
		return self::$cache[$identifier] ?? null;
	}
}