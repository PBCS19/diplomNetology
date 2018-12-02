<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/constant.php';

use Engine\Core\Router\Router;

try {
    
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
    
    $cms = new Router();
    $cms->run();
    
} catch (\ErrorException $e) {
    echo 'Ошибка' . $e->getMessage();
}

