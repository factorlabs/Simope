Simope
======
# What is Simope?

Simope (Simple Object Persistence) is library that allows to persist PHP objects without any DBMS installed.

# Requirements
* PHP 5.3 or higher
 
 
# Configuration

## First require library files:

    require_once __DIR__.'/lib/autoload.php';
    spl_autoload_register('autoload');
    require_once __DIR__.'/vendor/Pimpl.php';


## Than configure your environment:

    //Configure Dependency Injection Container (here we use Pimple: https://github.com/fabpot/Pimple)
    //If you dont want to use Pimple or any other DIC, just pass instance of Config explicite
    $container = new Pimple();
    $container['config'] = new Simope\Config(
        __DIR__.'/storage',
        __DIR__.'/lib/tests/test_config.json'
    );

    $container['em'] = new Simope\EntityManager(
        $container['config']
    );


## The last thing, is to change permissions of **storage** directory 


Example for Ubuntu users: *sudo chmod -R 777 storage*



# Usage


So, here you can find examples of CRUD

## Persist your first object
    $order = new stdClass();
    $order->name = 'New order';
    $order->items = array('Notebook', 'Keyboard');
    var_dump($container['em']->persist($order));


## Finding an object

    $order = $container['em']->findBy('stdClass', 'name', 'New order');
    var_dump($order);


### Or if you know id of object you can use: 
    $order = $container['em']->find('stdClass', $order[0]->id);
    var_dump($order);
## Now, lets remove an object:
    $container['em']->remove($order);
### Or, purge an repository:
    $repo = new Simope\Repository(
        $container['em'],
        'stdClass',
        $container  
    );
    $repo->purge();
