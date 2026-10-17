<?php

namespace App\Core;

final class Env
{
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded || !is_file($path)) {
            self::$loaded = true;
            return;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            if (strlen($value) >= 2 && $value[0] === '"' && str_ends_with($value, '"')) {
                $value = substr($value, 1, -1);
            }

            if (getenv($key) === false) {
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }

        self::$loaded = true;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        return match (strtolower((string) $value)) {
            'true' => true,
            'false' => false,
            'null' => null,
            default => $value,
        };
    }

    public static function update(array $pairs): void
    {
        $path = dirname(__DIR__, 2) . '/.env';
        if (!is_file($path)) {
            return;
        }

        $rawLines = file($path, FILE_IGNORE_NEW_LINES);
        if ($rawLines === false) {
            return;
        }

        $lines = $rawLines;

        foreach ($pairs as $key => $val) {
            $key = trim((string) $key);
            $val = (string) $val;

            if (preg_match('/[\s"#\'\\\\]/', $val) || $val === '') {
                $formattedVal = '"' . addcslashes($val, '"\\') . '"';
            } else {
                $formattedVal = $val;
            }

            $found = false;
            foreach ($lines as $i => $line) {
                $trimmed = trim($line);
                if ($trimmed === '' || str_starts_with($trimmed, '#')) {
                    continue;
                }
                if (!str_contains($trimmed, '=')) {
                    continue;
                }
                [$currentKey] = explode('=', $trimmed, 2);
                if (trim($currentKey) === $key) {
                    $lines[$i] = "{$key}={$formattedVal}";
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $lines[] = "{$key}={$formattedVal}";
            }

            $_ENV[$key] = $val;
            putenv("{$key}={$val}");
        }

        file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL, LOCK_EX);
    }
}
