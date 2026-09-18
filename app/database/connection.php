<?php

declare(strict_types=1);

$databaseConfig = require APP_PATH . '/config/database.php';

if ($databaseConfig['dsn'] === '') {
    throw new RuntimeException('Configure DB_DSN no ambiente do servidor antes de acessar o banco de dados.');
}

return new PDO(
    $databaseConfig['dsn'],
    $databaseConfig['username'],
    $databaseConfig['password'],
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);
