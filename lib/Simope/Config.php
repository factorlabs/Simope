<?php
/**
 * File contains Config class
 * @category Persistence
 * @package Simope
 * @subpackage Configuration
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope;
/**
 * Configuration storage class implements Registry pattern
 * @category Persistence
 * @package Simope
 * @subpackage Configuration
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
class Config
{
    /**
     * Array with configuration
     * @var array
     */
    private $config;
    /**
     * Constructor reads json configuration file
     * @param string $dir    storage directory
     * @param Config $config configuration object 
     * @return null
     */
    public function __construct($dir, $config)
    {
        $this->config      = json_decode(file_get_contents($config));
        $this->config->dir = $dir;
        $this->setDebugMode($this->config->env);
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
     * @param mixed $key   container key
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
        return $this->set($key, $value);
    }
    /**
     * @see Config::get($key)
     */
    public function __get($key)
    {
        return $this->get($key);
    }
    /**
     * Sets debug mode depended on env configuration
     * @param string $env env prod|dev
     * @return null
     */
    public function setDebugMode($env)
    {
        switch ($env) {
            case 'prod' :
                ini_set('display_errors', 0);
                error_reporting(E_ALL);
                return;
            case 'dev' :
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
                return;
            default :
                throw new Exception\ConfigException(
                    'Given environment is not allowed'
                );
        }
    }
}