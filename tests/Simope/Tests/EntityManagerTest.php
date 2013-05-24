<?php
namespace Simope\Tests;

use Simope\Config;
use Simope\ContainerFactory;
use Simope\Exception\ContainerFactoryException;
use Simope\EntityManager;

class EntityManagerTest extends \PHPUnit_Framework_TestCase
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
    public function testPersist()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = 'bar';
        $this->container['em']->persist($entity);
        $this->assertTrue(
            file_exists(
                sprintf(
                    '%s/%s/%s.json',
                    $this->container['config']->get('dir'),
                    get_class($entity),
                    $entity->id
                )
            )
        );
        
        
        $sampleEntityClass = sprintf('SampleEntity_%s', md5(uniqid()));
        eval("class $sampleEntityClass{}; \$sampleEntity = new $sampleEntityClass();");
        $sampleEntity->id = $genClass::generate();
        $sampleEntity->baz = 'quux';
        $this->container['em']->persist($sampleEntity);
        $this->assertTrue(
            file_exists(
                sprintf(
                    '%s/%s/%s.json',
                    $this->container['config']->get('dir'),
                    get_class($entity),
                    $entity->id
                )
            )
        );
        
        
        
        $entity = new \stdClass();
        $directoryManagerClass = $this->container['config']
            ->get('directory_manager_class');
        $directoryManager = new $directoryManagerClass(
            sprintf(
                    '%s/%s/',
                    $this->container['config']->get('dir'),
                    get_class($entity)
                )
        );
        $directoryManager->purge();
        $entity->foo = 'bar';
        $this->container['em']->persist($entity);
        $found = $this->container['em']
            ->findBy('stdClass', 'foo', 'bar');
        $this->assertTrue(
            in_array('id', array_keys(get_object_vars($found[0])))
        );
    }
    /**
     * @expectedException Simope\Exception\EntityManagerException
     * @expectedExceptionMessage Could not create entity direcory
     */
    public function testPersistCantCreateException()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['config']->set('dir', 'nonexistingdir');
        try {
            $this->container['em']->persist($entity);
        } catch(Exception $e) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }
    /**
     * @expectedException Simope\Exception\EntityManagerException
     * @expectedExceptionMessage Entity should be an object
     */
    public function testPersistWrongArgumentException()
    {
        $entity = array();
        try {
            $this->container['em']->persist($entity);
        } catch(Exception $e) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }
    public function testRemove()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        $this->container['em']->remove($entity);
        $this->assertFalse(
            file_exists(
                sprintf(
                    '%s/%s/%s.json',
                    $this->container['config']->get('dir'),
                    get_class($entity),
                    $entity->id
                )
            )
        );
    }
    public function testFindBy()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        $found = $this->container['em']
            ->findBy('stdClass', 'foo', 'bar');
        $this->assertTrue(in_array($entity, $found));
    }
    public function testFind()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        $foundEntity = $this->container['em']->find('stdClass', $entity->id);
        $this->assertEquals($entity, $foundEntity);
        
        
        $foundEntity = $this->container['em']->find(uniqid(), rand(1,1000000));
        $this->assertNull($foundEntity);
    }
    public function testSpy()
    {       
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = "bar";
        $this->container['em']->persist($entity);
        
        
        
        $foundEntity = $this->container['em']->find('stdClass', $entity->id);
        $file = new \SplFileInfo(
            sprintf(
                '%s/%s/%s.json',
                $this->container['config']->get('dir'),
                get_class($entity),
                $entity->id
            )
        );
        $currentCheck = array();
        $currentCheck['last_modified_time'] = $file->getATime();
        $currentCheck['size']               = $file->getSize();
        $this->assertEquals(
            $currentCheck,
            $this->container['em']->spy('stdClass', $entity->id)
        );
        $this->assertNull($this->container['em']->spy('stdClass', uniqid()));
    }
    public function testGetRepository()
    {       
        $repository = $this->container['em']->getRepository('Simope\Repository');
        $this->assertTrue(is_a($repository, 'Simope\Repository'));
    }
}
