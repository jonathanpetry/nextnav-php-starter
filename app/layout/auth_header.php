<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$pageTitle = $pageTitle ?? 'Acesso';
?>
<!DOCTYPE html>
<html lang="pt-BR" data-allow-theme-toggle="<?= $appConfig['interface']['allow_theme_toggle'] ? 'true' : 'false' ?>" data-app-key="<?= htmlspecialchars(APP_KEY, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-default-theme="<?= htmlspecialchars($appConfig['interface']['default_theme'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars($appConfig['app']['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <?php require APP_PATH . '/layout/theme.php'; ?>
</head>
<body class="auth-body">
    <div class="auth-shell">
        <aside class="auth-showcase" aria-label="Apresentação do sistema">
            <a class="auth-brand" href="<?= APP_URL ?>/login.php" aria-label="<?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> — acesso">
                <?php if ($appConfig['brand']['logo'] !== ''): ?>
                    <img src="<?= APP_URL ?>/<?= htmlspecialchars($appConfig['brand']['logo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="">
                <?php else: ?>
                    <span aria-hidden="true"><?= htmlspecialchars($appConfig['brand']['mark'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <?php endif; ?>
                <strong><?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
            </a>
            <div class="auth-showcase-copy">
                <span class="eyebrow">ACESSO SEGURO</span>
                <h1><?= htmlspecialchars($appConfig['app']['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h1>
                <p><?= htmlspecialchars($appConfig['brand']['subtitle'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            </div>
            <small>Interface demonstrativa. A autenticação deve ser conectada ao produto final.</small>
        </aside>

        <main class="auth-main">
            <header class="auth-topbar">
                <a class="auth-brand auth-brand-mobile" href="<?= APP_URL ?>/login.php" aria-label="<?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> — acesso">
                    <?php if ($appConfig['brand']['logo'] !== ''): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($appConfig['brand']['logo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="">
                    <?php else: ?>
                        <span aria-hidden="true"><?= htmlspecialchars($appConfig['brand']['mark'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <strong><?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
                </a>
                <?php if ($appConfig['interface']['allow_theme_toggle']): ?>
                    <button class="auth-theme-button" type="button" aria-label="Alternar tema" aria-pressed="false" data-auth-theme>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v2M12 19v2M3 12h2M19 12h2M5.6 5.6 7 7M17 17l1.4 1.4M18.4 5.6 17 7M7 17l-1.4 1.4"/><circle cx="12" cy="12" r="4"/></svg>
                    </button>
                <?php endif; ?>
            </header>
            <div class="auth-content">
                <section class="auth-card">
