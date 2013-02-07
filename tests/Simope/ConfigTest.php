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
}
