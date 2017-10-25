<?php
namespace ntentan\wyf;


use ntentan\atiaa\DbContext;
use ntentan\atiaa\Driver;
use ntentan\Context;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

class DatabaseKeyValueStore implements KeyValueStoreInterface
{
    private $db;
    private $table;

    public function __construct($table = 'keyvaluestore')
    {
        $this->db = DbContext::getInstance()->getDriver();
        $this->table = $table;
    }

    public function get($key)
    {
        $result = $this->db->query("SELECT value FROM {$this->table} WHERE key = ?", [$key]);
        return $result['value'] ?? null;
    }

    public function put($key, $value)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE key = ?", [$key]);
        $this->db->query("INSERT INTO {$this->table}(key, value) VALUES(?,?)", [$key, $value]);
    }
}
