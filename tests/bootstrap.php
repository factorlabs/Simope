<?php
require_once __DIR__.'/../vendor/pimple/pimple/lib/Pimple.php';


$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Simope\Tests', __DIR__);