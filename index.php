<?php
require_once __DIR__.'/lib/autoload.php';
spl_autoload_register('autoload');
require_once __DIR__.'/vendor/Pimpl.php';


$container = new \Pimple();
$container['config'] = new Simope\Config(
    __DIR__.'/storage',
    __DIR__.'/lib/tests/test_config.json'
);
$container['em'] = new Simope\EntityManager(
    $container['config']
);
$start = xdebug_time_index();
for ($i=0; $i<1000; $i++) {
    //$key = md5(uniqid());
    $object = new stdClass();
    $object->foo = 'Some value';
    $container['em']->persist($object);
    echo $i."\r\n";
}
$end = xdebug_time_index();
echo $end-$start;
