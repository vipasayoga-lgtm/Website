<?php

declare(strict_types=1);

use App\Core\Env;
use App\Core\Session;

require dirname(__DIR__) . '/vendor/autoload.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $path = __DIR__ . '/' . str_replace('\\', '/', $relative) . '.php';

    if (is_file($path)) {
        require $path;
    }
});

Env::load(dirname(__DIR__) . '/.env');

$config = require __DIR__ . '/Config/app.php';

date_default_timezone_set($config['timezone']);

error_reporting(E_ALL);
ini_set('display_errors', $config['debug'] ? '1' : '0');
ini_set('log_errors', '1');
ini_set('error_log', dirname(__DIR__) . '/storage/logs/php-error.log');

\App\Core\SecurityHeaders::apply();

/**
 * Any exception that escapes a controller lands here instead of a raw PHP
 * fatal-error page. Always logged; the branded errors/500 view is shown to
 * the visitor, with the message/trace only included when APP_DEBUG=true.
 */
set_exception_handler(static function (\Throwable $e) use ($config): void {
    error_log(sprintf(
        '[Uncaught %s] %s in %s:%d%s%s',
        get_class($e),
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
        PHP_EOL,
        $e->getTraceAsString()
    ));

    if (!headers_sent()) {
        http_response_code(500);
    }

    if (PHP_SAPI === 'cli') {
        return;
    }

    echo \App\Core\View::render('errors/500', [
        'debug' => $config['debug'],
        'exceptionMessage' => $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine(),
        'exceptionTrace' => $e->getTraceAsString(),
    ], 'main');
});

Session::start();
