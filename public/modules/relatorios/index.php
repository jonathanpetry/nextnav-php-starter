<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Relatório de clientes';
$activePage = 'relatorios';
$mock = require APP_PATH . '/data/mock.php';
$busca = is_string($_GET['busca'] ?? null) ? trim($_GET['busca']) : '';
$status = is_string($_GET['status'] ?? null) ? $_GET['status'] : '';
$filtroInvalido = (isset($_GET['busca']) && !is_string($_GET['busca']))
    || (isset($_GET['status']) && !is_string($_GET['status']))
    || !in_array($status, ['', 'Ativo', 'Teste', 'Atrasado'], true)
    || preg_match('/\A[^\r\n]{0,80}\z/u', $busca) !== 1;
$clientes = [];
$receita = 0.0;
$usuarios = 0;

if (!$filtroInvalido) {
    foreach ($mock['clientes'] as $cliente) {
        if (($status !== '' && $cliente['status'] !== $status)
            || ($busca !== '' && preg_match('/' . preg_quote($busca, '/') . '/iu', $cliente['nome'] . ' ' . $cliente['email']) !== 1)) {
            continue;
        }
        $clientes[] = $cliente;
        $receita += $cliente['valor'];
        $usuarios += $cliente['usuarios'];
    }
} else {
    http_response_code(422);
}

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Relatórios</span><h2>Relatório de clientes</h2><p>Módulo-modelo: filtros GET, validação no PHP, indicadores e tabela. Dados fictícios, sem banco.</p></div>
</section>

<?php if ($filtroInvalido): ?>
    <p class="alert alert-error" role="alert">Filtro inválido. Use um status da lista e uma busca de até 80 caracteres.</p>
<?php endif; ?>

<section class="panel">
    <div class="panel-header"><div><h3>Filtros</h3><p>A URL mantém os filtros para compartilhar ou recarregar a consulta.</p></div><span class="tag">Mock</span></div>
    <form class="crud-toolbar" method="get">
        <input type="search" name="busca" maxlength="80" aria-label="Buscar cliente ou e-mail" placeholder="Buscar cliente ou e-mail" value="<?= htmlspecialchars($busca, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
        <select name="status" aria-label="Filtrar por status">
            <?php foreach (['' => 'Todos os status', 'Ativo' => 'Ativo', 'Teste' => 'Teste', 'Atrasado' => 'Atrasado'] as $valor => $rotulo): ?>
                <option value="<?= htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>"<?= $status === $valor ? ' selected' : '' ?>><?= htmlspecialchars($rotulo, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
        <div class="component-row"><button class="button button-primary" type="submit">Consultar</button><a class="button button-secondary" href="<?= APP_URL ?>/modules/relatorios/index.php">Limpar</a></div>
    </form>
</section>

<section class="crud-summary audit-panel" aria-label="Resumo do resultado">
    <article><span>Clientes encontrados</span><strong><?= count($clientes) ?></strong><small>conforme os filtros acima</small></article>
    <article><span>Usuários</span><strong><?= number_format($usuarios, 0, ',', '.') ?></strong><small>nos clientes encontrados</small></article>
    <article><span>Mensalidades</span><strong>R$ <?= number_format($receita, 2, ',', '.') ?></strong><small>soma dos valores demonstrativos</small></article>
</section>

<section class="panel">
    <div class="panel-header"><div><h3>Resultado</h3><p><?= count($clientes) ?> registros encontrados.</p></div></div>
    <?php if ($clientes === []): ?>
        <div class="empty-demo"><strong><?= $filtroInvalido ? 'Revise os filtros' : 'Nenhum cliente encontrado' ?></strong><p><?= $filtroInvalido ? 'Corrija os valores informados para executar a consulta.' : 'Experimente outra busca ou limpe os filtros.' ?></p><a class="button button-secondary" href="<?= APP_URL ?>/modules/relatorios/index.php">Limpar filtros</a></div>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead><tr><th scope="col">Cliente</th><th scope="col">Plano</th><th scope="col">Usuários</th><th scope="col">Mensalidade</th><th scope="col">Status</th></tr></thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($cliente['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong><small><?= htmlspecialchars($cliente['email'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></small></td>
                            <td><?= htmlspecialchars($cliente['plano'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td>
                            <td><?= number_format($cliente['usuarios'], 0, ',', '.') ?></td>
                            <td>R$ <?= number_format($cliente['valor'], 2, ',', '.') ?></td>
                            <td><span class="status status-<?= strtolower($cliente['status']) ?>"><?= htmlspecialchars($cliente['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require APP_PATH . '/layout/footer.php'; ?>
