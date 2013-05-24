<?php
/**
 * File contains EntityManager class
 * @category Persistence
 * @package Simope
 * @subpackage EntityManager
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope;
/**
 * Class allows persist an PHP objects
 * @category Persistence
 * @package Simope
 * @subpackage EntityManager
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 */
class EntityManager
{
    /**
     * Constructor sets internal configuration 
     * @param Config $config configuration object 
     * @return null
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }
    /**
     * Persists object
     * @param object $entity any PHP object
     * @throws EntityManagerException
     * @return null
     */
    public function persist($entity)
    {
        if (is_object($entity) === false) {
            throw new Exception\EntityManagerException(
                'Entity should be an object'
            );
        }
        $generatorClass = $this->config->id_gen_strategy_class;
        if (!isset($entity->id)) {
            $entity->id = $generatorClass::generate();
        }
        if (
            is_dir($this->config->dir) === false
            || is_writable($this->config->dir) === false
        ) {
            throw new Exception\EntityManagerException(
                'Could not create entity direcory'
            );
        }
        $directory = sprintf(
            "%s/%s",
            $this->config->dir,
            get_class($entity)
        );
        if (!is_dir($directory)) {
            mkdir($directory);
        }
        file_put_contents(
            sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                get_class($entity),
                $entity->id
            ),
            json_encode($entity)
        );
        $indexClass = $this->config->index_class;
        $index      = new $indexClass($this->config);
        $index->set($entity);
    }
    /**
     * Removes object from storage
     * @param object $entity any PHP object
     * @return boolean result of unlink() function
     */
    public function remove($entity)
    {
        return unlink(
            sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                get_class($entity),
                $entity->id
            )
        );
    }
    /**
     * Removes object from storage
     * @param string $entityName name of class
     * @param mixed $entityId id of entity
     * @return null|object result of searching
     */
    public function find($entityName, $entityId)
    {
        $file = sprintf(
            '%s/%s/%s.json',
            $this->config->dir,
            $entityName,
            $entityId
        );
        if (file_exists($file)
        ) {
            return json_decode(file_get_contents($file));
        }
    }
    /**
     * Finds entity by given paramaters
     * @param string $entityName name of class
     * @param mixed $key name of property
     * @param mixed $value value of property
     * @return array searching result
     */
    public function findBy($entityName, $key, $value)
    {
        $indexClass = $this->config->index_class;
        $index = new $indexClass($this->config);
        $ids = $index->get($entityName, $key, $value);
        $result = array();
        foreach ($ids as $id) {
            $file = sprintf(
                '%s/%s/%s.json',
                $this->config->dir,
                $entityName,
                $id
            );
            if (file_exists($file)
            ) {
                $result[] = json_decode(file_get_contents($file));
            }
        }
        return $result;
    }
    /**
     * Returns basic information about created file/object
     * @param string $entityName name of class
     * @param mixed $entityId id of entity
     * @return null|array basic information
     */
    public function spy($entityName, $entityId)
    {
        $file = sprintf(
            '%s/%s/%s.json',
            $this->config->dir,
            $entityName,
            $entityId
        );
        if (file_exists($file)
        ) {
            $file = new \SplFileInfo($file);
            $result = array();
            $result['last_modified_time'] = $file->getATime();
            $result['size']               = $file->getSize();
            return $result;
        } else {
            return null;
        }
    }
    public function getRepository($entityName)
    {
        $parts = explode("\\", $entityName);
        $entityClassName = array_pop($parts);
        return new $entityName($this, $entityClassName, $this->config);
    }
}
