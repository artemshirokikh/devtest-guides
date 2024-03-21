<?php

use Services\Configuration;
use Services\Persistence\Persistence;
use Services\Persistence\SerializablePersistence;
use Services\Router;

class Application
{
    public static function run(string $configFile): void
    {
        $config = static::createConfiguration($configFile);
        $router = static::createRouter($config);
        $persist = static::createPersistence($config);

        static::loadData($persist);

        // Run action
        $object = $router->getObject();
        $controllerName = 'Controllers\\' . $router->transformName($object) . 'Controller';
        if (!class_exists($controllerName)) {
            $router->respondNotFound();
        } else {
            $controller = new $controllerName($config, $router, $persist);
            // todo Builder?
            $controller->processRequest();
        }

        static::saveData($persist);
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

    protected static function loadData(Persistence $persistence): void
    {
        if ($persistence instanceof SerializablePersistence) {
            $persistence->loadData();
        }
    }

    protected static function saveData(Persistence $persistence): void
    {
        if ($persistence instanceof SerializablePersistence) {
            $persistence->saveData();
        }
    }
}