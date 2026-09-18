<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Clientes';
$activePage = 'clientes';
$mock = require APP_PATH . '/data/mock.php';

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Cadastros</span><h2>Clientes</h2><p>Exemplo de listagem pronta para receber dados reais.</p></div>
    <button class="button button-primary" type="button" disabled>Adicionar cliente</button>
</section>

<section class="panel">
    <div class="panel-header">
        <div><h3>Todos os clientes</h3><p><?= count($mock['clientes']) ?> registros de demonstração.</p></div>
        <span class="tag">Dados mock</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Cliente</th><th>Plano</th><th>Usuários</th><th>Mensalidade</th><th>Status</th></tr></thead>
            <tbody>
                <?php foreach ($mock['clientes'] as $cliente): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($cliente['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong><small><?= htmlspecialchars($cliente['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></td>
                        <td><?= htmlspecialchars($cliente['plano'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                        <td><?= $cliente['usuarios'] ?></td>
                        <td>R$ <?= number_format($cliente['valor'], 2, ',', '.') ?></td>
                        <td><span class="status status-<?= strtolower($cliente['status']) ?>"><?= htmlspecialchars($cliente['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php require APP_PATH . '/layout/footer.php'; ?>
