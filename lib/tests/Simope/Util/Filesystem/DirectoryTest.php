<?php
namespace Simope\Util\Filesystem;
require_once __DIR__.'/../../../bootstrap.php';
class DirectoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->container = new \Pimple();
        $this->container['config'] = new \Simope\Config(
            __DIR__.'/../../../../../storage',
            __DIR__.'/../../../test_config.json'
        );
        $this->container['em'] = new \Simope\EntityManager(
            $this->container['config']
        );
    }
    public function testPurge()
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
        $directory->purge();
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