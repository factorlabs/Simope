<?php
namespace Simope;
require_once __DIR__.'/../bootstrap.php';
class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $this->assertEquals(
            $config->get('env'),
            'dev'
        );
        $this->assertEquals(
            $config->env,
            'dev'
        );
    }
    public function testSet()
    {
        $config = new Config(
            __DIR__.'/../../storage',
            __DIR__.'/../test_config.json'
        );
        $config->set('foo', 'bar');
        $this->assertEquals(
            $config->get('foo'),
            'bar'
        );
        $config->baz = 'quux';
        $this->assertEquals(
            $config->baz,
            'quux'
        );
    }
    public function testSetDebugMode()
    {
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $this->assertEquals(
            1,
            ini_get('display_errors')
        );
        $this->assertEquals(
            E_ALL,
            error_reporting()
        );
        $config->setDebugMode('prod');
        $this->assertEquals(
            0,
            ini_get('display_errors')
        );
        $this->assertEquals(
            E_ALL,
            error_reporting()
        );
    }
    /**
     * @expectedException Simope\Exception\ConfigException
     * @expectedExceptionMessage Given environment is not allowed
     */
    public function testNotProperEnvException()
    {
        $config = new Config(
            __DIR__.'/../storage',
            __DIR__.'/../test_config.json'
        );
        $config->set('container_class', 'nonexistingclass');
        try {
            $config->setDebugMode('foo');
        } catch(Exception $e) {
            return;
        }
        $this->fail('An expected exception has not been raised.');
    }
}
