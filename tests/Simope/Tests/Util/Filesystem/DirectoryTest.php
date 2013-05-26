<?php
namespace Simope\Tests\Util\Filesystem;

use Simope\Config;
use Simope\ContainerFactory;
use Simope\Exception\ContainerFactoryException;
use Simope\EntityManager;
use Simope\Util\Filesystem\Directory;


class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $config = new Config(
            __DIR__.'/../../../storage',
            __DIR__.'/../../../test_config.json'
        );
        $this->container = ContainerFactory::create($config);
        $this->container['config'] = $config;
        $this->container['em'] = new EntityManager(
            $this->container['config']
        );
    }
    public function testClear()
    {
        $directory = new Directory($this->container['config']->dir);
        for ($i=0; $i<5; $i++) {
            mkdir(
                sprintf(
                    '%s/%s',
                    $this->container['config']->dir,
                    uniqid()
                )
            );
        }
        $directory->clear();
        $dir = new \DirectoryIterator($this->container['config']->dir);
        $counter = 0;
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $counter++;
            }
        }
        $this->assertEquals($counter, 0);
        
    }
}
