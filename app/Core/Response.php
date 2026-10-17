<?php

namespace App\Core;

final class Response
{
    public static function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        if (!headers_sent()) {
            header('X-CSRF-Token: ' . Csrf::token());
        }
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function redirect(string $path, int $status = 302): never
    {
        if (!headers_sent()) {
            header('Location: ' . $path, true, $status);
        }
        if (PHP_SAPI === 'cli') {
            throw new \RuntimeException("REDIRECT: {$path}");
        }
        exit;
    }

    public static function notFound(string $message = 'Not found'): never
    {
        self::json(['success' => false, 'message' => $message], 404);
    }
}
