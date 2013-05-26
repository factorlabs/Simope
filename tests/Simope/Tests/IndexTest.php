<?php
namespace Simope\Tests;

use Simope\Config;
use Simope\ContainerFactory;
use Simope\Exception\ContainerFactoryException;
use Simope\EntityManager;
use Simope\Index;

class IndexTest extends \PHPUnit_Framework_TestCase
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
        $this->container['index'] = new Index(
            $this->container['config']
        );
    }
    public function testSet()
    {
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->foo = 'bar';
        $this->container['index']->set($entity);
        
        
        $file = sprintf(
            '%s/index/%s/%s.json',
            $this->container['config']->get('dir'),
            get_class($entity),
            'foo'
        );
        $this->assertTrue(file_exists($file));
        $content = json_decode(file_get_contents($file), true);
        $keys = array_keys($content, 'bar');
        $this->assertTrue(in_array($entity->id, $keys));
        
    }
    public function testGet()
    {
        $key   = md5(uniqid());
        $value = md5(uniqid());
        $genClass = $this->container['config']->get('id_gen_strategy_class');
        $entity = new \stdClass();
        $entity->id = $genClass::generate();
        $entity->$key = $value;
        $this->container['index']->set($entity);
        $keys = $this->container['index']
            ->get(get_class($entity), array($key => $value));
        $this->assertTrue(in_array($entity->id, $keys));
        
        
        $keys = $this->container['index']
            ->get(md5(uniqid()), array(md5(uniqid()) => md5(uniqid())));
        $this->assertEquals(array(), $keys);
    }
}
