<?php

namespace Services;

class Configuration extends Service
{
    protected array $config;

    /**
     * @param string $configFile Path to file with configuration
     */
    public function __construct(string $configFile)
    {
        // todo Handle wrong file error
        // todo Handle passing array
        // todo Handle passing object
        $this->config = include $configFile;
    }

    /**
     * Magic method to access 1st level properties of configuration
     *
     * @param $name 1st-level key of configuration array
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->config)) {
            return $this->config[$name];
        }
        return null;
    }
}