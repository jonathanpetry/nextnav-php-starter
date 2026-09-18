<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$pageTitle = 'Visão geral';
$activePage = 'dashboard';
$mock = require APP_PATH . '/data/mock.php';

$clientesAtivos = 0;
$usuariosAtivos = 0;
$receitaMensal = 0.0;

foreach ($mock['clientes'] as $cliente) {
    if ($cliente['status'] === 'Ativo') {
        $clientesAtivos++;
        $usuariosAtivos += $cliente['usuarios'];
        $receitaMensal += $cliente['valor'];
    }
}

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div>
        <span class="eyebrow">Dashboard</span>
        <h2>Seu negócio em um só lugar</h2>
        <p>Acompanhe os números principais e as últimas movimentações.</p>
    </div>
    <a class="button button-primary" href="<?= APP_URL ?>/modules/clientes/index.php">Ver clientes</a>
</section>

<section class="metrics" aria-label="Indicadores principais">
    <article class="metric-card"><span>Clientes ativos</span><strong><?= $clientesAtivos ?></strong><small class="positive">+2 neste mês</small></article>
    <article class="metric-card"><span>Receita mensal</span><strong>R$ <?= number_format($receitaMensal, 2, ',', '.') ?></strong><small class="positive">+12,4% no período</small></article>
    <article class="metric-card"><span>Usuários ativos</span><strong><?= $usuariosAtivos ?></strong><small>Em todos os clientes</small></article>
    <article class="metric-card"><span>Em teste</span><strong><?= count(array_filter($mock['clientes'], fn(array $cliente): bool => $cliente['status'] === 'Teste')) ?></strong><small>Conversão pendente</small></article>
</section>

<div class="content-grid">
    <section class="panel">
        <div class="panel-header">
            <div><h3>Clientes recentes</h3><p>Visão rápida das contas cadastradas.</p></div>
            <span class="tag">Mock</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Cliente</th><th>Plano</th><th>Valor</th><th>Status</th></tr></thead>
                <tbody>
                    <?php foreach (array_slice($mock['clientes'], 0, 4) as $cliente): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($cliente['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong><small><?= htmlspecialchars($cliente['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></td>
                            <td><?= htmlspecialchars($cliente['plano'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td>R$ <?= number_format($cliente['valor'], 2, ',', '.') ?></td>
                            <td><span class="status status-<?= strtolower($cliente['status']) ?>"><?= htmlspecialchars($cliente['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <section class="panel">
        <div class="panel-header"><div><h3>Atividade recente</h3><p>Últimos eventos do sistema.</p></div></div>
        <div class="activity-list">
            <?php foreach ($mock['atividades'] as $atividade): ?>
                <article class="activity-item">
                    <span class="activity-dot" aria-hidden="true"></span>
                    <div><strong><?= htmlspecialchars($atividade['descricao'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong><small><?= htmlspecialchars($atividade['quando'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php require APP_PATH . '/layout/footer.php'; ?>
