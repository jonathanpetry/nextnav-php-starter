<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Estados do sistema';
$activePage = 'exemplos-estados';

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Exemplos</span><h2>Estados do sistema</h2><p>Amostras visuais: esta página não autentica, bloqueia acesso nem altera o status HTTP para 403/404.</p></div>
</section>

<div class="state-grid">
    <section class="panel state-card"><span class="state-code">VAZIO</span><div class="state-icon">＋</div><h3>Nenhum registro criado</h3><p>Explique o motivo e ofereça a próxima ação possível.</p><button class="button button-primary" type="button" data-toast-trigger>Criar primeiro registro</button></section>
    <section class="panel state-card"><span class="state-code">403</span><div class="state-icon">⊘</div><h3>Acesso não permitido</h3><p>O usuário está autenticado, mas não possui permissão para esta área.</p><a class="button button-secondary" href="<?= APP_URL ?>/index.php">Voltar ao início</a></section>
    <section class="panel state-card"><span class="state-code">404</span><div class="state-icon">?</div><h3>Página não encontrada</h3><p>O endereço pode ter mudado ou o conteúdo não está mais disponível.</p><a class="button button-secondary" href="<?= APP_URL ?>/index.php">Ir para visão geral</a></section>
    <section class="panel state-card"><span class="state-code">ERRO</span><div class="state-icon">!</div><h3>Não foi possível carregar</h3><p>Informe o impacto e como o usuário pode tentar novamente.</p><button class="button button-secondary" type="button" data-toast-trigger>Tentar novamente</button></section>
    <section class="panel state-card"><span class="state-code">MANUTENÇÃO</span><div class="state-icon">⌁</div><h3>Sistema temporariamente indisponível</h3><p>Informe o motivo conhecido e quando o usuário deve tentar novamente.</p><button class="button button-secondary" type="button" data-toast-trigger>Atualizar página</button></section>
    <section class="panel state-card"><span class="state-code">SESSÃO</span><div class="state-icon">◷</div><h3>Sua sessão expirou</h3><p>Proteja os dados e ofereça um caminho claro para entrar novamente.</p><button class="button button-primary" type="button" data-toast-trigger>Entrar novamente</button></section>
</div>

<?php require APP_PATH . '/layout/footer.php'; ?>
