<?php

declare(strict_types=1);

/*
 * A conexão é configurada pelo ambiente do servidor.
 * Nunca escreva credenciais reais neste arquivo.
 */
return [
    'dsn' => (string) getenv('DB_DSN'),
    'username' => (string) getenv('DB_USERNAME'),
    'password' => (string) getenv('DB_PASSWORD'),
];
