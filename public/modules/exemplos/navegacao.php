<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

// As seis folhas compartilham esta demonstração, mas cada uma tem identidade própria.
$pages = [
    'contas-receber' => ['Contas a receber', 'Financeiro → Contas', 3],
    'contas-pagar' => ['Contas a pagar', 'Financeiro → Contas', 3],
    'colecoes' => ['Coleções', 'Cadastros → Produtos → Catálogo', 4],
    'categorias' => ['Categorias', 'Cadastros → Produtos → Catálogo', 4],
    'vendedores' => ['Vendedores', 'Gestão → Comercial → Metas → Equipes', 5],
    'supervisores' => ['Supervisores', 'Gestão → Comercial → Metas → Equipes', 5],
];
$page = is_string($_GET['pagina'] ?? null) ? $_GET['pagina'] : '';
$selection = $pages[$page] ?? null;
if ($selection === null) {
    http_response_code(404);
}
$pageTitle = $selection[0] ?? 'Página não encontrada';
$activePage = $selection === null ? '' : 'demo-' . $page;

require APP_PATH . '/layout/menu.php';
?>
<section class="page-heading">
    <div><span class="eyebrow">Demonstração de navegação</span><h2><?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></h2><p>Folhas com identidade própria, sem simular regras de negócio.</p></div>
</section>
<section class="panel form-panel">
    <?php if ($selection !== null): ?>
        <h3><?= $selection[2] ?> níveis</h3>
        <p><?= htmlspecialchars($selection[1], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?> → <?= htmlspecialchars($pageTitle, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
        <p>Abra o menu: esta página deve aparecer selecionada no seu nível. Use Voltar para navegar até os módulos.</p>
    <?php else: ?>
        <h3>404</h3><p>Esta demonstração não está cadastrada.</p>
    <?php endif; ?>
    <a class="button button-secondary" href="<?= APP_URL ?>/index.php">Ir ao início</a>
</section>
<?php require APP_PATH . '/layout/footer.php'; ?>
