<?php
namespace ntentan\wyf;


use ntentan\atiaa\DbContext;
use ntentan\wyf\interfaces\KeyValueStoreInterface;

/**
 * A Key-Value store that utilizes a dedicated database table.
 * @package ntentan\wyf
 */
class DatabaseKeyValueStore implements KeyValueStoreInterface, \Serializable
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

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return $this->table;
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        return new self($serialized);
    }
}
