<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function envOrDefault(string $key, string $default): string
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        return $default;
    }

    return $value;
}

function resolveDatabaseConfig(): array
{
    $jawsDbUrl = getenv('JAWSDB_URL');
    if ($jawsDbUrl !== false && $jawsDbUrl !== '') {
        $parts = parse_url($jawsDbUrl);
        if (is_array($parts)) {
            return [
                'host' => (string) ($parts['host'] ?? '127.0.0.1'),
                'port' => (string) (($parts['port'] ?? 3306)),
                'name' => ltrim((string) ($parts['path'] ?? '/php_crud_db'), '/'),
                'user' => (string) ($parts['user'] ?? 'root'),
                'pass' => (string) ($parts['pass'] ?? ''),
            ];
        }
    }

    return [
        'host' => envOrDefault('DB_HOST', '127.0.0.1'),
        'port' => envOrDefault('DB_PORT', '3306'),
        'name' => envOrDefault('DB_NAME', 'php_crud_db'),
        'user' => envOrDefault('DB_USER', 'root'),
        'pass' => envOrDefault('DB_PASS', ''),
    ];
}

function getPdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $db = resolveDatabaseConfig();
    $dsn = 'mysql:host=' . $db['host'] . ';port=' . $db['port'] . ';dbname=' . $db['name'] . ';charset=utf8mb4';

    $pdo = new PDO($dsn, $db['user'], $db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function initializeDatabase(PDO $pdo): void
{
    $sql = "
        CREATE TABLE IF NOT EXISTS applicants (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(120) NOT NULL,
            email VARCHAR(150) NOT NULL,
            phone VARCHAR(30) NOT NULL,
            address VARCHAR(255) NOT NULL,
            position_applied VARCHAR(120) NOT NULL,
            years_experience INT UNSIGNED NOT NULL,
            image_path VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    $pdo->exec($sql);
}
