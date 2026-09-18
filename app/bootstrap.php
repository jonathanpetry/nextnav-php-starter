<?php

declare(strict_types=1);

// Erros ficam no log do PHP, nunca na resposta HTTP, inclusive em desenvolvimento.
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
set_exception_handler(static function (Throwable $exception): void {
    $environment = defined('APP_ENV') ? APP_ENV : 'bootstrap';
    error_log(sprintf('[NextNav:%s] %s em %s:%d', $environment, get_class($exception), $exception->getFile(), $exception->getLine()));
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
    }
    echo 'Não foi possível concluir a solicitação. Tente novamente ou contate o responsável pelo sistema.';
    exit(1);
});

define('PROJECT_ROOT', dirname(__DIR__));
define('APP_PATH', __DIR__);
define('PUBLIC_PATH', PROJECT_ROOT . '/public');

$appConfig = require APP_PATH . '/config/app.php';

if (!is_array($appConfig)) {
    throw new RuntimeException('A configuração principal do NextNav é inválida.');
}

foreach (['app', 'brand', 'interface', 'loader'] as $section) {
    if (!isset($appConfig[$section]) || !is_array($appConfig[$section])) {
        throw new RuntimeException("A seção {$section} da configuração é inválida.");
    }
}

foreach (['primary', 'secondary', 'dark_primary', 'dark_secondary'] as $colorKey) {
    $color = strtoupper((string) ($appConfig['brand']['colors'][$colorKey] ?? ''));
    if (preg_match('/^#[0-9A-F]{6}$/', $color) !== 1) {
        throw new RuntimeException("A cor {$colorKey} deve usar o formato hexadecimal completo.");
    }
    $appConfig['brand']['colors'][$colorKey] = $color;
}

foreach ([['brand', 'logo'], ['brand', 'favicon'], ['loader', 'custom_image']] as [$section, $assetKey]) {
    $asset = str_replace('\\', '/', trim((string) ($appConfig[$section][$assetKey] ?? '')));
    if (str_contains($asset, '..') || str_starts_with($asset, '/') || preg_match('/[\x00-\x1F\x7F?#%]|^[a-z][a-z0-9+.-]*:/i', $asset) === 1) {
        throw new RuntimeException("O caminho de {$assetKey} deve ser relativo à pasta public.");
    }
    $appConfig[$section][$assetKey] = $asset;
}

foreach (['primary' => 'on_primary', 'secondary' => 'on_secondary', 'dark_primary' => 'dark_on_primary', 'dark_secondary' => 'dark_on_secondary'] as $colorKey => $contrastKey) {
    $hex = ltrim($appConfig['brand']['colors'][$colorKey], '#');
    $luminance = 0.0;
    foreach ([0.2126, 0.7152, 0.0722] as $channel => $weight) {
        $value = hexdec(substr($hex, $channel * 2, 2)) / 255;
        $luminance += ($value <= 0.04045 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4) * $weight;
    }
    // Escolhe o maior contraste entre preto e branco para a cor sólida configurada.
    $appConfig['brand']['colors'][$contrastKey] = ($luminance + 0.05) / 0.05 >= 1.05 / ($luminance + 0.05) ? '#000000' : '#FFFFFF';
}

$appConfig['interface']['default_theme'] = $appConfig['interface']['default_theme'] === 'dark' ? 'dark' : 'light';
$appConfig['loader']['minimum_duration'] = min(1000, max(0, (int) ($appConfig['loader']['minimum_duration'] ?? 0)));

$environment = getenv('APP_ENV');
if ($environment !== false && $environment !== '') {
    $appConfig['app']['environment'] = $environment;
}
if (preg_match('/^[a-z0-9_-]{2,30}$/', (string) ($appConfig['app']['environment'] ?? '')) !== 1) {
    throw new RuntimeException('O ambiente deve usar somente letras minúsculas, números, hífen e sublinhado.');
}

define('APP_NAME', (string) $appConfig['app']['name']);
define('APP_KEY', (string) $appConfig['app']['key']);
define('APP_ENV', (string) $appConfig['app']['environment']);

$scriptPath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$modulesPosition = strpos($scriptPath, '/modules/');
$baseUrl = $modulesPosition === false ? dirname($scriptPath) : substr($scriptPath, 0, $modulesPosition);
$baseUrl = $baseUrl === '/' || $baseUrl === '.' ? '' : rtrim($baseUrl, '/');

define('APP_URL', $baseUrl);

if (!in_array($appConfig['app']['timezone'] ?? '', DateTimeZone::listIdentifiers(), true)) {
    throw new RuntimeException('O fuso horário configurado é inválido.');
}
date_default_timezone_set($appConfig['app']['timezone']);
header('Content-Type: text/html; charset=UTF-8');
