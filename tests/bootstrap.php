<?php
require_once __DIR__.'/../lib/autoload.php';
//spl_autoload_register('autoload');
require_once __DIR__.'/../vendor/Pimple.php';
require_once __DIR__.'/../lib/Simope/Config.php';
require_once __DIR__.'/../lib/Simope/ContainerFactory.php';
require_once __DIR__.'/../lib/Simope/EntityManager.php';
require_once __DIR__.'/../lib/Simope/Util/Generator/Generable.php';
require_once __DIR__.'/../lib/Simope/Util/Generator/Uuid.php';
require_once __DIR__.'/../lib/Simope/Exception/EntityManagerException.php';
require_once __DIR__.'/../lib/Simope/Exception/ContainerFactoryException.php';
require_once __DIR__.'/../lib/Simope/Exception/ConfigException.php';
require_once __DIR__.'/../lib/Simope/Index.php';
require_once __DIR__.'/../lib/Simope/Repository.php';
require_once __DIR__.'/../lib/Simope/Util/Filesystem/Directory.php';