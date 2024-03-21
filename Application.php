<?php

use Services\Configuration;
use Services\Router;

class Application
{
    public static function run(string $configFile): void
    {
        $config = static::createConfiguration($configFile);
        $router = static::createRouter($config);
        $persist = static::createPersistence($config);

        // todo
        echo 'Running again...';
    }


    protected static function createConfiguration($configFile): Configuration
    {
        return new Configuration($configFile);
    }

    protected static function createRouter(Configuration $config): Router
    {
        return new Router();
    }

    protected static function createPersistence(Configuration $config)
    {
        $className = $config->persistence['class'];
        $connection = $config->persistence['connection'];
        return new $className($connection);
    }
}