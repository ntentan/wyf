<?php

namespace ntentan\wyf\interfaces;

/**
 * Key value stores are used within WYF for keeping certain system states.
 * @package ntentan\wyf\interfaces
 */
interface KeyValueStoreInterface
{
    public function get($key);
    public function put($key, $value);
}