<?php
/**
 * File contains Config class
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 */
namespace Simope;
/**
 * Configuration storage class
 */
class Config
{
    private $config;
    /**
     * Constructor
     * @param string $dir storage directory
     * @param Config $config configuration object 
     * @return null
     */
    public function __construct($dir, $config)
    {
        $this->config      = json_decode(file_get_contents($config));
        $this->config->dir = $dir;
    }
    /**
     * Getter
     * @param integer|string $key container key
     * @return mixed
     */
    public function get($key)
    {
        return $this->config->{$key};
    }
    /**
     * Setter, implements Flexible Interface
     * @param mixed $key container key
     * @param mixed $value container value 
     * @return Config
     */
    public function set($key, $value)
    {
        $this->config->{$key} = $value;
        return $this;
    }
    /**
     * @see Config::set($key, $value)
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
        return $this;
    }
    /**
     * @see Config::get($key)
     */
    public function __get($key)
    {
        return $this->get($key);
    }
}