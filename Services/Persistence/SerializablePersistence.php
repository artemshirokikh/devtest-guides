<?php

namespace Services\Persistence;

/**
 * @property $connection Here it is a path to file with serialized data
 */
class SerializablePersistence extends Persistence
{
    /**
     * @var array Loaded and decoded instance of database. All data here
     */
    protected array $data;


    /**
     * Load all data from persistence
     */
    public function loadData(): void
    {
        $serialized = file_get_contents($this->connection);
        $this->data = $serialized === ''
            ? []
            : unserialize($serialized);
    }

    /**
     * Save all data to persistence
     */
    public function saveData(): void
    {
        $serialized = serialize($this->data);
        file_put_contents($this->connection, $serialized);
    }

    /**
     * Check is table empty
     *
     * @param string $table
     * @return boolean
     */
    public function isEmptyTable(string $table): bool
    {
        if (!array_key_exists($table, $this->data) || empty($this->data[$table])) {
            return true;
        }
        return false;
    }

    /**
     * Create table if it does exist
     *
     * @param string $table
     */
    protected function initTable(string $table): void
    {
        if (!isset($this->data[$table])) {
            $this->data[$table] = [];
        }
    }

    /**
     * Get all items from table
     *
     * @param string $table
     * @return array
     */
    public function selectAll(string $table): array
    {
        if (!isset($this->data[$table])) {
            return [];
        }
        return $this->data[$table];
    }

    /**
     * Get item from table
     *
     * @param string $table
     * @param mixed $id
     * @return mixed|null
     */
    public function select(string $table, $id)
    {
        if (!isset($this->data[$table]) || !isset($this->data[$table][$id])) {
            return null;
        }
        return $this->data[$table][$id];
    }

    /**
     * Insert item into table
     *
     * @param string $table
     * @param mixed $id
     * @param mixed $data
     */
    public function insert(string $table, $id, $data): void
    {
        $this->initTable($table);
        $this->data[$table][$id] = $data;
    }

    /**
     * Update item in table
     *
     * @param string $table
     * @param mixed $id
     * @param mixed $data
     * @param bool $insertIfNotExists
     */
    public function update(string $table, $id, $data, bool $insertIfNotExists = true): void
    {
        $this->initTable($table);
        if (!isset($this->data[$table][$id])) {
            if ($insertIfNotExists) {
                $this->data[$table][$id] = $data;
            }
            return;
        }
        $this->data[$table][$id] = $data;
    }

    /**
     * Delete item from table
     *
     * @param string $table
     * @param mixed $id
     */
    public function delete(string $table, $id): void
    {
        if (!isset($this->data[$table]) || !isset($this->data[$table][$id])) {
            return;
        }
        unset($this->data[$table][$id]);
    }
}
