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
class Repository
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
     * Purges repository
     * @return null
     */
    public function purge()
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
        $directory->purge();
    }
}