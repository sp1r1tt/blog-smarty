<?php

declare(strict_types=1);

// Конфиг подключения к MySQL (используется Database::connect()).

$envFile = dirname(__DIR__) . '/.env';
if (is_file($envFile)) {
    $values = parse_ini_file($envFile, false, INI_SCANNER_RAW);
    if (is_array($values)) {
        foreach ($values as $key => $value) {
            $key = (string) $key;
            if ($key === '' || getenv($key) !== false) {
                continue;
            }
            putenv($key . '=' . (string) $value);
        }
    }
}

return [
    'host' => getenv('DB_HOST') ?: '127.0.0.1',
    'port' => getenv('DB_PORT') ?: '3306',
    'dbname' => getenv('DB_NAME') ?: 'blog',
    'username' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: '',
];
