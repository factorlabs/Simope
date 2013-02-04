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
$res = 0;
for ($i=0; $i<100; $i++) {
    $start = xdebug_time_index();
    $found = $container['em']->findBy('stdClass', 'foo', 'bar');
    $end = xdebug_time_index();
    $res = $res+$end-$start;
    echo $i."\r\n";
}
echo ($res/100)."\r\n";
