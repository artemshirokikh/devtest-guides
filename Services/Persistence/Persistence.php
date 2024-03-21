<?php

namespace Services\Persistence;

use Services\Service;

abstract class Persistence extends Service
{
    /**
     * @var string Connection string to connect to persistence service
     */
    protected string $connection;


    /**
     * @param string $connection Connection string to connect to persistence service
     */
    public function __construct(string $connection)
    {
        // todo Handle array
        $this->connection = $connection;
    }


    public function getConnection(): string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return Persistence
     */
    public function setConnection(string $connection): self
    {
        $this->connection = $connection;
        return $this;
    }
}
