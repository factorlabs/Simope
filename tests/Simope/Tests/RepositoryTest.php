<?php
namespace Simope\Tests;

use Simope\Config;
use Simope\ContainerFactory;
use Simope\Exception\ContainerFactoryException;
use Simope\EntityManager;
use Simope\Index;
use Simope\Repository;

class RepositoryTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $this->container = ContainerFactory::create($config);
        $this->container['config'] = $config;
        $this->container['em'] = new EntityManager(
            $this->container['config']
        );
    }
    public function testClear()
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
        $repo->clear();
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
        $this->assertNull($repo->clear());
    }
    public function testFind()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        $repository = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container
        );
        $foundEntityFirst = $repository->find($entity->id);
        $this->assertEquals($entity, $foundEntityFirst);
        $repository->clear();
        $foundEntitySecond = $repository->find($genClass::generate());
        $this->assertNull($foundEntitySecond);
    }
    public function testFindAll()
    {
        $repository = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container
        );
        $repository->clear();
        for ($i=0; $i < 5; $i++) {
            $genClass = $this->container['config']->get('id_gen_strategy_class');
            $entity = new \stdClass();
            $entity->id = $genClass::generate();
            $entity->foo = uniqid();
            $this->container['em']->persist($entity);
        }
        $foundFirst = $repository->findAll();
        $this->assertEquals(5, count($foundFirst));
        $repository->clear();
        $foundSecond = $repository->find($genClass::generate());
        $this->assertEquals(0, count($foundSecond));
    }
    public function testFindBy()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        $repository = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container
        );
        $found = $repository->findBy(array('foo' => 'bar'));
        $this->assertTrue(in_array($entity, $found));
    }
    public function testFindByEmptyCriteria()
    {
        $repository = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container
        );
        $repository->clear();
        for ($i = 0; $i < 5; $i++) {
            $genClass = $this->container['config']->get('id_gen_strategy_class');
            $entity = new \stdClass();
            $entity->id = $genClass::generate();
            $this->container['em']->persist($entity);
        }
        
        $found = $repository->findBy();
        $foundAll = $repository->findAll();
        sort($found);
        sort($foundAll);
		sleep(5);
        for ($i = 0; $i < 5; $i++) {
            $this->assertEquals($found[$i]->id, $foundAll[$i]->id);
        }
        $this->assertEquals(count($found), $repository->count());
    }
    public function testCount()
    {
        $repository = new Repository(
            $this->container['em'],
            'stdClass',
            $this->container
        );
        $repository->clear();
        for ($i=0; $i < 5; $i++) {
            $genClass = $this->container['config']->get('id_gen_strategy_class');
            $entity = new \stdClass();
            $entity->id = $genClass::generate();
            $this->container['em']->persist($entity);
        }
		sleep(5);
        $this->assertEquals(5, $repository->count());
    }
}
