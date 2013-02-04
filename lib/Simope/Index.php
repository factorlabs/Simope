<?php
/**
 * File contains Index class
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 */
namespace Simope;
/**
 * Index class responds of indexing functionality
 */
class Index
{
    /**
     * Constructor
     * @param object $config configuration object
     */
    public function __construct($config)
    {
        $this->config = $config;
        $file = sprintf('%s/index', $this->config->dir);
        if (is_dir($file) === false) {
            mkdir($file);
        }
    }
    /**
     * Sets index according to Entity properties
     * @param object $entity
     * @return null
     */
    public function set($entity)
    {
        $properties = array();
        $reflection = new \ReflectionClass($entity);
        $properties = $reflection->getProperties(
            \ReflectionProperty::IS_PUBLIC
            | \ReflectionProperty::IS_PROTECTED
            | \ReflectionProperty::IS_PRIVATE
            | \ReflectionProperty::IS_STATIC
        );
        if (count($properties) === 0) {
            foreach (get_object_vars($entity) as $key => $property) {
                $properties[$key] = $property;
            }
        }
        $dir = sprintf(
            '%s/index/%s',
            $this->config->dir,
            get_class($entity)
        );
        if (count($properties) > 0 && !is_dir($dir)) {
            mkdir($dir);
        }
        foreach ($properties as $key => $value) {
            $file = sprintf(
                '%s/%s.json',
                $dir,
                $key
            );
            if (file_exists($file)) {
                $content = json_decode(file_get_contents($file), true);
                $content[$entity->id] = $value;
                file_put_contents($file, json_encode($content));
            } else {
                file_put_contents(
                    $file,
                    json_encode(array($entity->id => $value))
                );
            }
        }
    }
    /**
     * Gets array of Entities ids 
     * @param string $class
     * @param mixed $key
     * @param mixed $value
     * @return array
     */
    public function get($class, $key, $value)
    {
        $file = sprintf(
            '%s/index/%s/%s.json',
            $this->config->dir,
            $class,
            $key
        );
        if (file_exists($file)) {
            $content = json_decode(file_get_contents($file), true);
            return array_keys($content, $value);
        }
        return array();
    }
}