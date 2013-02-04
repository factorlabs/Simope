<?php
namespace Simope;
use Simope\Util\Filesystem;
class Repository
{
    public function __construct(
        $entityManager,
        $className,
        $container
    )
    {
        $this->entityManager = $entityManager;
        $this->className = $className;
        $this->container = $container;
    }
    public function purge()
    {
        $directory = sprintf('%s/%s', $this->container['config']->dir, $this->className);
        if (!is_dir($directory)) {
            return;
        }
        $directoryClass = $this->container['config']->get('directory_manager_class');
        $directory = new $directoryClass($directory);
        $directory->purge();
    }
}