<?php
require_once __DIR__.'/lib/autoload.php';
spl_autoload_register('autoload');
require_once __DIR__.'/vendor/Pimpl.php';

//Configure Dependency Injection Container (here we use Pimple)
$container = new \Pimple();
$container['config'] = new Simope\Config(
    __DIR__.'/storage',
    __DIR__.'/lib/tests/test_config.json'
);
$container['em'] = new Simope\EntityManager(
    $container['config']
);

//Now create first entity
$order = new stdClass();
$order->name = 'New order';
$order->items = array('Notebook', 'Keyboard');

var_dump($container['em']->persist($order));


//Finding an Entity
$order = $container['em']->findBy('stdClass', 'name', 'New order');
var_dump($order);
//Or we we know id of Entity we can use:
$order = $container['em']->find('stdClass', $order[0]->id);
var_dump($order);
//Ok, now lets purge an repository:
$repo = new Simope\Repository(
    $container['em'],
    'stdClass',
    $container  
);
$repo->purge();