<?php
namespace ntentan\wyf;


use ntentan\atiaa\DbContext;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

/**
 * A Key-Value store that utilizes a dedicated database table.
 * @package ntentan\wyf
 */
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
        return $result[0]['value'] ?? null;
    }

    public function put($key, $value)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE key = ?", [$key]);
        $this->db->query("INSERT INTO {$this->table}(key, value) VALUES(?,?)", [$key, $value]);
    }
}
