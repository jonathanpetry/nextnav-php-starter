<?php

$brandColors = $appConfig['brand']['colors'];
?>
<script>
    (() => {
        const root = document.documentElement;
        root.dataset.theme = root.dataset.defaultTheme;
        if (root.dataset.allowThemeToggle === 'true') {
            try {
                const saved = localStorage.getItem(root.dataset.appKey + '_theme');
                if (saved === 'light' || saved === 'dark') root.dataset.theme = saved;
            } catch { /* O tema continua funcional sem armazenamento. */ }
        }
    })();
</script>
<link rel="stylesheet" href="<?= APP_URL ?>/assets/app.css?v=<?= filemtime(PUBLIC_PATH . '/assets/app.css') ?>">
<style>
    :root {
        --primary: <?= $brandColors['primary'] ?>;
        --primary-dark: color-mix(in srgb, <?= $brandColors['primary'] ?> 78%, #000000);
        --primary-soft: color-mix(in srgb, <?= $brandColors['primary'] ?> 9%, #ffffff);
        --primary-border: color-mix(in srgb, <?= $brandColors['primary'] ?> 32%, #ffffff);
        --on-primary: <?= $brandColors['on_primary'] ?>;
        --secondary: <?= $brandColors['secondary'] ?>;
        --on-secondary: <?= $brandColors['on_secondary'] ?>;
    }
    html[data-theme="dark"] {
        --primary: <?= $brandColors['dark_primary'] ?>;
        --primary-dark: color-mix(in srgb, <?= $brandColors['dark_primary'] ?> 84%, #000000);
        --primary-soft: color-mix(in srgb, <?= $brandColors['dark_primary'] ?> 13%, transparent);
        --primary-border: color-mix(in srgb, <?= $brandColors['dark_primary'] ?> 34%, transparent);
        --on-primary: <?= $brandColors['dark_on_primary'] ?>;
        --secondary: <?= $brandColors['dark_secondary'] ?>;
        --on-secondary: <?= $brandColors['dark_on_secondary'] ?>;
    }
</style>
<?php if ($appConfig['brand']['favicon'] !== ''): ?>
    <link rel="icon" href="<?= APP_URL ?>/<?= htmlspecialchars($appConfig['brand']['favicon'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
<?php endif; ?>
