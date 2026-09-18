<?php

require_once dirname(__DIR__) . '/bootstrap.php';

$pageTitle = $pageTitle ?? 'Visão geral';
$activePage = $activePage ?? '';

$menuLevels = require APP_PATH . '/config/navigation.php';

// Renderize apenas níveis alcançáveis. "demo" oculta amostras, não autoriza acesso.
$visibleLevels = [];
$pendingLevels = ['root'];
while ($pendingLevels !== []) {
    $levelId = array_shift($pendingLevels);
    if (isset($visibleLevels[$levelId]) || !isset($menuLevels[$levelId])) {
        continue;
    }
    $level = $menuLevels[$levelId];
    $level['items'] = array_values(array_filter($level['items'], static fn (array $item): bool =>
        $appConfig['interface']['show_demo_modules'] || empty($item['demo'])
    ));
    $visibleLevels[$levelId] = $level;
    foreach ($level['items'] as $item) {
        if (isset($item['target'])) {
            $pendingLevels[] = $item['target'];
        }
    }
}
$menuLevels = $visibleLevels;

$loaderImage = $appConfig['loader']['custom_image'] ?: $appConfig['brand']['logo'];
?>
<!DOCTYPE html>
<html lang="pt-BR" data-allow-theme-toggle="<?= $appConfig['interface']['allow_theme_toggle'] ? 'true' : 'false' ?>" data-app-key="<?= htmlspecialchars(APP_KEY, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-default-theme="<?= htmlspecialchars($appConfig['interface']['default_theme'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-loader-minimum-duration="<?= $appConfig['loader']['minimum_duration'] ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= htmlspecialchars($appConfig['app']['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> · <?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></title>
    <?php require APP_PATH . '/layout/theme.php'; ?>
</head>
<body>
    <?php if ($appConfig['loader']['enabled']): ?>
        <dialog class="app-loader" aria-live="polite" aria-label="<?= htmlspecialchars($appConfig['loader']['text'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-app-loader>
            <div class="app-loader-content" role="status">
                <?php if ($appConfig['loader']['show_brand'] && $loaderImage !== ''): ?>
                    <img src="<?= APP_URL ?>/<?= htmlspecialchars($loaderImage, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="">
                <?php elseif ($appConfig['loader']['show_brand']): ?>
                    <span class="app-loader-mark" aria-hidden="true"><?= htmlspecialchars($appConfig['brand']['mark'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <?php else: ?>
                    <span class="app-loader-spinner" aria-hidden="true"></span>
                <?php endif; ?>
                <small><?= htmlspecialchars($appConfig['loader']['text'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
            </div>
        </dialog>
        <script>document.querySelector('[data-app-loader]').showModal(); window.setTimeout(() => { if (!window.NextNavLoader) document.querySelector('[data-app-loader]')?.close(); }, 5000);</script>
    <?php endif; ?>
    <noscript><p class="alert alert-warning">Ative o JavaScript para usar menus e demonstrações interativas. <a href="<?= APP_URL ?>/index.php">Ir ao início</a>.</p></noscript>

    <div class="menu-overlay" data-menu-overlay></div>

    <aside class="menu-drawer" id="app-menu" aria-label="Navegação principal" aria-hidden="true" inert data-menu>
        <div class="menu-brand">
            <a href="<?= APP_URL ?>/index.php" class="menu-brand-link" data-menu-home>
                <?php if ($appConfig['brand']['logo'] !== ''): ?>
                    <img class="brand-logo" src="<?= APP_URL ?>/<?= htmlspecialchars($appConfig['brand']['logo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="">
                <?php else: ?>
                    <span class="brand-mark"><?= htmlspecialchars($appConfig['brand']['mark'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                <?php endif; ?>
                <span><strong><?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong><small><?= htmlspecialchars($appConfig['brand']['subtitle'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></span>
            </a>
            <button type="button" class="icon-button" aria-label="Fechar menu" data-menu-close>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg>
            </button>
        </div>

        <div class="menu-level-header" hidden data-menu-level-header>
            <button type="button" class="menu-back" data-menu-back>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 18-6-6 6-6"/></svg>
                <span>Voltar</span>
            </button>
            <div>
                <small>VOCÊ ESTÁ EM</small>
                <strong data-menu-title></strong>
                <span data-menu-description></span>
            </div>
        </div>

        <nav class="menu-navigation">
            <?php foreach ($menuLevels as $levelId => $level): ?>
                <section class="menu-level" data-menu-level="<?= htmlspecialchars($levelId, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-level-title="<?= htmlspecialchars($level['title'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-level-description="<?= htmlspecialchars($level['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"<?= $levelId === 'root' ? '' : ' hidden' ?>>
                    <span class="menu-label"><?= $levelId === 'root' ? 'ESPAÇO DE TRABALHO' : 'OPÇÕES' ?></span>
                    <?php foreach ($level['items'] as $item): ?>
                        <?php if (isset($item['target'])): ?>
                            <button type="button" class="menu-entry" data-menu-target="<?= htmlspecialchars($item['target'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                        <?php else: ?>
                            <a class="menu-entry<?= ($item['active'] ?? '') === $activePage ? ' is-active' : '' ?>" href="<?= APP_URL ?>/<?= htmlspecialchars($item['href'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" data-menu-leaf<?= ($item['active'] ?? '') === $activePage ? ' aria-current="page"' : '' ?>>
                        <?php endif; ?>
                            <span class="menu-entry-icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="3"/><path d="M8 9h8M8 13h5"/></svg>
                            </span>
                            <span class="menu-entry-copy">
                                <strong><?= htmlspecialchars($item['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
                                <small><?= htmlspecialchars($item['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small>
                            </span>
                            <svg class="menu-entry-arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
                        <?= isset($item['target']) ? '</button>' : '</a>' ?>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        </nav>

        <a class="menu-user" href="<?= APP_URL ?>/perfil.php" data-menu-leaf>
            <span class="user-avatar" aria-hidden="true">UD</span>
            <span class="menu-user-copy"><strong>Usuário Demonstração</strong><small>Administrador</small></span>
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    </aside>

    <div class="app-area" data-app-area>
        <header class="topbar">
            <div class="topbar-left">
                <button type="button" class="icon-button menu-button" aria-label="Abrir menu" aria-controls="app-menu" aria-expanded="false" data-menu-open>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
                <a class="topbar-brand" href="<?= APP_URL ?>/index.php" aria-label="Ir para o início de <?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                    <?php if ($appConfig['brand']['logo'] !== ''): ?>
                        <img src="<?= APP_URL ?>/<?= htmlspecialchars($appConfig['brand']['logo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>" alt="">
                    <?php else: ?>
                        <span aria-hidden="true"><?= htmlspecialchars($appConfig['brand']['mark'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span>
                    <?php endif; ?>
                    <strong><?= htmlspecialchars(APP_NAME, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
                </a>
            </div>
            <div class="topbar-actions">
                <button type="button" class="account-button" aria-label="Abrir menu da conta" aria-haspopup="menu" aria-controls="account-menu" aria-expanded="false" data-account-open>UD</button>
            </div>
        </header>

        <div class="account-menu" id="account-menu" role="menu" hidden data-account-menu>
            <div class="account-name"><strong>Usuário Demonstração</strong><small>Administrador</small></div>
            <a href="<?= APP_URL ?>/perfil.php" role="menuitem">Meu perfil</a>
            <a href="<?= APP_URL ?>/perfil.php#senha" role="menuitem">Trocar senha</a>
            <a href="<?= APP_URL ?>/login.php" role="menuitem">Sair da demonstração</a>
            <?php if ($appConfig['interface']['allow_theme_toggle']): ?>
                <button type="button" role="menuitemcheckbox" aria-checked="false" data-theme-toggle>
                    <span>Tema escuro</span>
                    <span class="theme-switch" aria-hidden="true"><i></i></span>
                </button>
            <?php endif; ?>
        </div>

        <div class="page-scroll">
            <main class="page-content">
