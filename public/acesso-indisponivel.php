<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$reason = is_string($_GET['motivo'] ?? null) ? $_GET['motivo'] : '';
$reason = in_array($reason, ['bloqueado', 'inativo'], true) ? $reason : 'bloqueado';
$pageTitle = $reason === 'inativo' ? 'Conta inativa' : 'Acesso bloqueado';

require APP_PATH . '/layout/auth_header.php';
?>
<div class="auth-state">
    <span class="auth-state-icon" aria-hidden="true">×</span>
    <span class="eyebrow"><?= $reason === 'inativo' ? 'CONTA INATIVA' : 'ACESSO BLOQUEADO' ?></span>
    <h2><?= $reason === 'inativo' ? 'Esta conta não está ativa' : 'Não foi possível continuar' ?></h2>
    <p><?= $reason === 'inativo' ? 'Procure o responsável pelo sistema para confirmar a situação da conta.' : 'Aguarde o período definido pelo produto ou procure o responsável pelo sistema.' ?></p>
    <a class="button button-primary" href="<?= APP_URL ?>/login.php">Voltar para entrar</a>
    <a class="button button-ghost" href="<?= APP_URL ?>/index.php">Abrir demonstração</a>
</div>

<?php require APP_PATH . '/layout/auth_footer.php'; ?>
