<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

require(__DIR__ . '/../../vendor/autoload.php');

use BillyMVC\Configure;
Configure::write('db_config', [
        'host' => 'hostname',
        'name' => 'dbname',
        'user' => 'dbuser',
        'pass' => 'dbpass'
    ]);

require(__DIR__ . '/../_init.php');
