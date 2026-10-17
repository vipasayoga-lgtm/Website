<?php

declare(strict_types=1);

// Route static files directly when using built-in PHP dev server
if (php_sapi_name() === 'cli-server') {
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    if (is_file(__DIR__ . $path)) {
        return false;
    }
}

require dirname(__DIR__) . '/app/bootstrap.php';

use App\Core\Request;
use App\Core\Router;

$router = new Router();
(require dirname(__DIR__) . '/app/Config/routes.php')($router);

$router->dispatch(new Request());
