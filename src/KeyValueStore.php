<?php

namespace ntentan\wyf;


use ntentan\exceptions\NtentanException;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

class KeyValueStore
{
    private static $keyValueStore;

    public static function initialize(KeyValueStoreInterface $keyValueStore)
    {
        self::$keyValueStore = $keyValueStore;
    }

    public static function getInstance() : KeyValueStoreInterface
    {
        if(self::$keyValueStore === null) {
            throw new NtentanException("You have not initialized the key value store");
        }
        return self::$keyValueStore;
    }
}
