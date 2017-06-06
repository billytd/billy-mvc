<?php
use BillyMVC\Configure;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Configuration;

Configure::write('db', DriverManager::getConnection([
    'dbname'   => Configure::read('db_config.name'),
    'user'     => Configure::read('db_config.user'),
    'password' => Configure::read('db_config.pass'),
    'host'     => Configure::read('db_config.host'),
    'driver'   => 'pdo_mysql',
    'charset'  => 'UTF8'
], new Configuration()));



$uri_path     = preg_replace("/\?.*$/", '', $_SERVER['REQUEST_URI']);
$uri_segments = array_values(array_filter(explode('/', $uri_path)));

if (isset($uri_segments[0])) {
    $controller_name = ucfirst(strtolower(preg_replace("/[^a-z]/i", '', $uri_segments[0]))) . 'Controller';
} else {
    $controller_name = 'IndexController';
}

$controller_name = '\\BillyMVC\\Controller\\' . $controller_name;

$controller = new $controller_name($uri_segments);
