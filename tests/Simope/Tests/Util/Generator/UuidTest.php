<?php
namespace Simope\Tests\Util;

use Simope\Util\Generator\Uuid;

class UuidTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }
    public function testGenerate()
    {
        $this->assertEquals(
            preg_match(
                '/^[0-9a-z]{8}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{4}-[0-9a-z]{12}$/',
                Uuid::generate()
            ),
            1
        );
    }
}