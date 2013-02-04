<?php
namespace Simope;
require_once __DIR__.'/../bootstrap.php';
class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->container = new \Pimple();
        $this->container['config'] = new Config(
            __DIR__.'/../../../storage',
            __DIR__.'/../test_config.json'
        );
        $this->container['em'] = new EntityManager(
            $this->container['config']
        );
    }
    public function testPurge()
    {
        $repo = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container  
        );
        if (!is_dir(sprintf('%s/stdClass', $this->container['config']->dir))) {
            mkdir(sprintf('%s/stdClass', $this->container['config']->dir));
        }
        for ($i=0; $i<5; $i++) {
            mkdir(
                sprintf(
                    '%s/stdClass/%s',
                    $this->container['config']->dir,
                    uniqid()
                )
            );
        }
        $repo->purge();
        $dir = new \DirectoryIterator(
            sprintf('%s/stdClass', $this->container['config']->dir)
        );
        $counter = 0;
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $counter++;
            }
        }
        $this->assertEquals($counter, 0);
        
        
        $repo = new Repository(
            $this->container['em'],
            md5(uniqid()),
            $this->container  
        );
        $this->assertNull($repo->purge());
    }
}