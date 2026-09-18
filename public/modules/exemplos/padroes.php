<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Padrões de telas';
$activePage = 'exemplos-padroes';

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Exemplos</span><h2>Padrões de telas</h2><p>Composições visuais para listas, formulários e detalhes; os controles desta página são ilustrativos.</p></div>
    <a class="button button-secondary" href="crud.php">Ver modelo funcional</a>
</section>

<div class="pattern-grid">
    <section class="panel pattern-card">
        <span class="pattern-number">01</span><h3>Página de lista</h3><p>Cabeçalho, ação principal, filtros necessários, resultado e paginação.</p>
        <div class="pattern-preview"><div class="preview-heading"></div><div class="preview-filter"></div><div class="preview-lines"><i></i><i></i><i></i></div></div>
        <ul><li>Uma ação principal clara</li><li>Filtros preservados</li><li>Estado vazio previsto</li></ul>
    </section>
    <section class="panel pattern-card">
        <span class="pattern-number">02</span><h3>Formulário</h3><p>Campos agrupados por assunto, validação próxima e ações previsíveis.</p>
        <div class="pattern-preview"><div class="preview-heading"></div><div class="preview-fields"><i></i><i></i><i></i><i></i></div></div>
        <ul><li>Rótulos sempre visíveis</li><li>Servidor revalida os dados</li><li>Cancelar é secundário</li></ul>
    </section>
    <section class="panel pattern-card">
        <span class="pattern-number">03</span><h3>Detalhe</h3><p>Identidade do registro, estado, informações e histórico em ordem.</p>
        <div class="pattern-preview"><div class="preview-heading"></div><div class="preview-detail"><i></i><i></i></div></div>
        <ul><li>Ações conforme permissão</li><li>Estado atual evidente</li><li>Histórico quando necessário</li></ul>
    </section>
</div>

<section class="panel catalog-section pattern-live">
    <div class="example-heading"><span class="eyebrow">LISTA COMPLETA</span><h3>Projetos</h3><p>Busca, filtro, resultado, status e paginação no mesmo contexto.</p></div>
    <div class="table-toolbar"><input type="search" placeholder="Busca ilustrativa" aria-label="Busca ilustrativa"><select aria-label="Status ilustrativo"><option>Todos os status</option><option>Ativo</option></select><button class="button button-secondary" type="button" data-toast-trigger>Filtros</button><button class="button button-primary" type="button" data-toast-trigger>Novo projeto</button></div>
    <div class="table-wrap"><table><thead><tr><th>Projeto</th><th>Responsável</th><th>Status</th><th>Atualizado</th></tr></thead><tbody><tr><td><strong>Projeto Exemplo Alpha</strong><small>PRJ-001</small></td><td>Pessoa Exemplo 01</td><td><span class="status status-ativo">Ativo</span></td><td>Hoje</td></tr><tr><td><strong>Projeto Exemplo Beta</strong><small>PRJ-002</small></td><td>Pessoa Exemplo 02</td><td><span class="status status-teste">Em análise</span></td><td>Ontem</td></tr></tbody></table></div>
</section>

<section class="pattern-live-grid">
    <div class="panel form-panel">
        <div class="example-heading"><span class="eyebrow">FORMULÁRIO COMPLETO</span><h3>Criar projeto</h3><p>Campos agrupados por decisão.</p></div>
        <form method="get" data-no-loader onsubmit="event.preventDefault()"><fieldset class="form-section"><legend><span>1</span><strong>Identificação</strong></legend><div class="form-grid"><div class="form-field form-field-full"><label for="pattern-name">Nome *</label><input id="pattern-name" placeholder="Projeto Exemplo Alpha"></div><div class="form-field"><label for="pattern-code">Código</label><input id="pattern-code" placeholder="Automático"></div><div class="form-field"><label for="pattern-status">Situação *</label><select id="pattern-status"><option>Rascunho</option><option>Ativo</option></select></div></div></fieldset><fieldset class="form-section"><legend><span>2</span><strong>Responsabilidade</strong></legend><div class="form-grid"><div class="form-field"><label for="pattern-owner">Responsável *</label><select id="pattern-owner"><option>Selecione</option><option>Pessoa Exemplo 01</option></select></div><div class="form-field"><label for="pattern-team">Equipe</label><select id="pattern-team"><option>Operação</option></select></div><div class="form-field form-field-full"><label for="pattern-description">Descrição</label><textarea id="pattern-description" rows="4"></textarea></div></div></fieldset><div class="form-actions"><button class="button button-secondary" type="button" data-toast-trigger>Cancelar</button><button class="button button-primary" type="button" data-toast-trigger>Criar projeto</button></div></form>
    </div>
    <aside class="panel detail-example"><span class="eyebrow">DETALHE</span><div class="detail-identity"><span>PE</span><div><h3>Projeto Exemplo Alpha</h3><p>PRJ-001 · Ativo</p></div></div><div class="detail-metrics"><article><small>Responsável</small><strong>Pessoa Exemplo 01</strong></article><article><small>Equipe</small><strong>Operação</strong></article></div><h4>Atividades recentes</h4><ol class="timeline"><li><i></i><div><strong>Status atualizado</strong><p>Projeto ativado para operação.</p><small>Hoje, 10:42</small></div></li><li><i></i><div><strong>Projeto criado</strong><p>Cadastro inicial concluído.</p><small>Ontem, 16:18</small></div></li></ol></aside>
</section>

<?php require APP_PATH . '/layout/footer.php'; ?>
