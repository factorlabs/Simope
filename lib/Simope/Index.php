<?php
/**
 * File contains Index class
 * @category Persistence
 * @package Simope
 * @subpackage Index
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope;
/**
 * Class handles indexing of strored objects
 * @category Persistence
 * @package Simope
 * @subpackage Index
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
class Index
{
    /**
     * Constructor sets internal configuration 
     * @param Config $config configuration object 
     * @return null
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
     * @param string $class name of class
     * @param array $criteria search criteria
     * @return array
     */
    public function get($class, array $criteria = array())
    {
        if (count($criteria) === 0) {
            $result = array();
            $resultTmp = array();
            $indexDirectory = sprintf(
                '%s/index/%s',
                $this->config->dir,
                $class
            ); 
            foreach (new \DirectoryIterator($indexDirectory) as $fileInfo) {
                if ($fileInfo->isDot()) {
                    continue;
                }
                $file = sprintf(
                    '%s/%s',
                    $indexDirectory,
                    $fileInfo->getFilename()
                );
                $content = json_decode(file_get_contents($file), true);
                $found = array_keys($content);
                $result = array_merge($resultTmp, $found);
            }
            return array_unique($result);
        } else {
            reset($criteria);
            $file = sprintf(
                '%s/index/%s/%s.json',
                $this->config->dir,
                $class,
                key($criteria)
            );
            if (file_exists($file)) {
                $content = json_decode(file_get_contents($file), true);
                return array_keys($content, $criteria[key($criteria)]);
            }
        }
        return array();
    }
}
