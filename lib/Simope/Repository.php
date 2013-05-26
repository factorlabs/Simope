<?php
/**
 * File contains Repository class
 * @category Persistence
 * @package Simope
 * @subpackage Repository
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @version GIT: <git_id>
 * @link https://github.com/factorlabs/Simope
 */
namespace Simope;
use Simope\Util\Filesystem;
/**
 * Class implements Repository pattern
 * @category Persistence
 * @package Simope
 * @subpackage Repository
 * @author Leszek Albrzykowski <l.albrzykowski@factorlabs.pl>
 * @license The MIT License (MIT)
 * @link https://github.com/factorlabs/Simope
 * @see http://martinfowler.com/eaaCatalog/repository.html
 */
class Repository implements \Countable
{
    /**
     * Constructor sets initial properties
     * @param string $class name of class
     * @param mixed $key name of property
     * @param mixed $value value of property
     * @return null
     */
    public function __construct(
        $entityManager,
        $className,
        $container
    ) 
    {
        $this->entityManager = $entityManager;
        $this->className     = $className;
        $this->container     = $container;
    }
    /**
     * Clears repository
     * @return null
     */
    public function clear()
    {
        $directoryName = sprintf(
            '%s/%s',
            $this->container['config']->dir,
            $this->className
        );
        if (!is_dir($directoryName)) {
            return;
        }
        $directoryClass = $this->container['config']
            ->get('directory_manager_class');
        $directory = new $directoryClass($directoryName);
        $directory->clear();
    }
    /**
     * Finds entity by given id
     * @param mixed $entityId id of entity
     * @return null|object result of searching
     */
    public function find($entityId)
    {
        return $this->entityManager->find($this->className, $entityId);
    }
    /**
     * Finds entity by given paramaters
     * @param array $criteria search criteria
     * @return array searching result
     */
    public function findBy(array $criteria = array())
    {
        return $this->entityManager->findBy(
            $this->className,
            $criteria
        );
    }
    /**
     * Finds all entities
     * @return array searching result
     */
    public function findAll()
    {
        return $this->entityManager->findAll($this->className);
    }
    /**
     * Counts all entities in repository
     * @return integer number of entities in repository
     */
    public function count()
    {
        return count($this->entityManager->findAll($this->className));
    }
}