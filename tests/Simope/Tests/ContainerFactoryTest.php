<?php
namespace Simope\Tests;

use Simope\Config;
use Simope\ContainerFactory;
use Simope\Exception\ContainerFactoryException;

class ContainerFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreate()
    {   
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $container = ContainerFactory::create($config);
        $this->assertTrue(
            is_object($container)
        );
        $containerClass = $config->get('container_class');
        $this->assertTrue(
           $container instanceof $containerClass
        );
    }
    /**
     * @expectedException Simope\Exception\ContainerFactoryException
     * @expectedExceptionMessage Could not create instance of Container class
     */
    public function testCantCreateException()
    {
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $config->set('container_class', 'nonexistingclass');
        try {
            ContainerFactory::create($config);
        } catch(Exception $e) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }
}
