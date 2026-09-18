<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Componentes';
$activePage = 'exemplos-componentes';

$options = ['alpha' => 'Opção Alpha', 'beta' => 'Opção Beta', 'gama' => 'Opção Gama', 'delta' => 'Opção Delta', 'epsilon' => 'Opção Epsilon', 'zeta' => 'Opção Zeta'];
$selectedOptions = [];
$selectionError = false;
if (isset($_GET['opcoes'])) {
    if (!is_array($_GET['opcoes'])) {
        $selectionError = true;
    } else {
        foreach ($_GET['opcoes'] as $value) {
            if (!is_string($value) || !isset($options[$value])) {
                $selectionError = true;
                continue;
            }
            $selectedOptions[] = $value;
        }
        $selectedOptions = array_values(array_unique($selectedOptions));
    }
} elseif (!isset($_GET['aplicar'])) {
    $selectedOptions = ['beta'];
}

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Exemplos</span><h2>Catálogo de componentes</h2><p>Amostras visuais e interações do shell. Para uma operação completa, use o CRUD demonstrativo.</p></div>
    <a class="button button-secondary" href="crud.php">Abrir página-modelo</a>
</section>

<nav class="catalog-index" aria-label="Seções do catálogo">
    <a href="#fundacao">Fundação</a><a href="#acoes">Ações</a><a href="#entrada">Entrada</a><a href="#feedback">Feedback</a><a href="#resumo">Resumo</a><a href="#contexto">Contexto</a><a href="#dados">Dados</a>
</nav>

<section class="panel catalog-section" id="fundacao">
    <div class="example-heading"><span class="eyebrow">01 · FUNDAÇÃO</span><h3>Cores, superfícies e tipografia</h3><p>Tokens semânticos e uma escala curta mantêm o produto consistente.</p></div>
    <div class="token-grid">
        <article><i class="token-primary"></i><strong>Primária</strong><code>--primary</code></article>
        <article><i class="token-surface"></i><strong>Superfície</strong><code>--surface</code></article>
        <article><i class="token-muted"></i><strong>Neutra</strong><code>--surface-muted</code></article>
        <article><i class="token-success"></i><strong>Sucesso</strong><code>--success</code></article>
        <article><i class="token-warning"></i><strong>Atenção</strong><code>--warning</code></article>
        <article><i class="token-danger"></i><strong>Risco</strong><code>--danger</code></article>
    </div>
    <div class="type-samples"><div><small>H1 · PÁGINA</small><h1>Clareza antes de decoração</h1></div><div><small>H2 · SEÇÃO</small><h2>Informação agrupada por objetivo</h2></div><div><small>H3 · COMPONENTE</small><h3>Um título direto e útil</h3></div><div><small>CORPO</small><p>Textos explicam contexto, consequência e próxima ação.</p></div></div>
</section>

<div class="example-grid" id="acoes">
    <section class="panel example-section example-wide">
        <div class="example-heading"><h3>Botões</h3><p>Ação principal, comum, leve e destrutiva.</p></div>
        <div class="component-row">
            <button class="button button-primary" type="button" data-toast-trigger>Ação principal</button>
            <button class="button button-secondary" type="button" data-toast-trigger>Ação secundária</button>
            <button class="button button-ghost" type="button" data-toast-trigger>Ação leve</button>
            <button class="button button-danger" type="button" data-toast-trigger>Excluir</button>
            <button class="button button-primary" type="button" disabled>Desabilitado</button><button class="button button-primary" type="button" disabled><span class="button-spinner"></span> Processando</button>
        </div>
    </section>

    <section class="panel example-section">
        <div class="example-heading"><h3>Badges</h3><p>Estados curtos dentro de listas e tabelas.</p></div>
        <div class="component-row">
            <span class="status status-ativo">Ativo</span>
            <span class="status status-teste">Em teste</span>
            <span class="status status-atrasado">Atenção</span>
            <span class="status status-inativo">Inativo</span>
        </div>
    </section>

    <section class="panel example-section example-wide" id="feedback">
        <div class="example-heading"><h3>Alertas e feedback</h3><p>Mensagens que afetam a tarefa atual.</p></div>
        <div class="alert alert-info"><i></i><div><strong>Informação</strong><span>Use uma mensagem curta e orientada à ação.</span></div></div>
        <div class="alert alert-success"><i></i><div><strong>Operação concluída</strong><span>O registro foi salvo com sucesso.</span></div></div>
        <div class="alert alert-warning"><i></i><div><strong>Atenção necessária</strong><span>Revise os dados antes de continuar.</span></div></div>
        <div class="alert alert-error"><i></i><div><strong>Não foi possível concluir</strong><span>Tente novamente ou procure o suporte.</span></div></div>
        <div class="loading-demo"><div><span class="button-spinner"></span><strong>Carregando informações</strong></div><div class="skeleton-lines"><i></i><i></i><i></i></div></div>
    </section>

    <section class="panel example-section" id="entrada">
        <div class="example-heading"><h3>Campos</h3><p>Rótulo visível, ajuda e validação próxima.</p></div>
        <div class="form-field"><label for="exemplo-nome">Nome</label><input id="exemplo-nome" type="text" placeholder="Informe o nome"></div>
        <div class="form-field"><label for="exemplo-plano">Plano</label><select id="exemplo-plano"><option>Essencial</option><option>Profissional</option></select></div>
        <div class="form-field form-field-error"><label for="exemplo-email">E-mail inválido</label><input id="exemplo-email" type="email" value="email-invalido" aria-invalid="true"><small>Informe um endereço válido.</small></div>
        <div class="form-field form-field-success"><label for="exemplo-codigo">Código disponível</label><input id="exemplo-codigo" value="CLI-1024"><small>Código disponível.</small></div>
        <div class="form-field"><label for="exemplo-notas">Observações</label><textarea id="exemplo-notas" rows="3"></textarea></div>
        <label class="check-field"><input type="checkbox" checked><span>Receber atualizações</span></label>
        <form method="get" action="#entrada">
            <div class="form-field">
                <label for="exemplo-opcoes">Seleção múltipla com checkbox</label>
                <select id="exemplo-opcoes" name="opcoes[]" multiple data-multi-select>
                    <?php foreach ($options as $value => $label): ?>
                        <option value="<?= $value ?>"<?= in_array($value, $selectedOptions, true) ? ' selected' : '' ?>><?= htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></option>
                    <?php endforeach; ?>
                </select>
                <small data-multi-summary>Os códigos são enviados; os rótulos são apenas a exibição.</small>
            </div>
            <div class="form-actions component-row"><button class="button button-secondary" type="reset">Restaurar seleção</button><button class="button button-primary" type="submit" name="aplicar" value="1">Enviar seleção</button></div>
            <?php if (isset($_GET['aplicar'])): ?>
                <p class="form-message<?= $selectionError ? ' is-error' : '' ?>" role="status"><?= $selectionError ? 'Seleção inválida. Use apenas as opções disponíveis.' : 'Códigos recebidos pelo PHP: ' . htmlspecialchars(implode(', ', $selectedOptions) ?: 'nenhum', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></p>
            <?php endif; ?>
        </form>
    </section>

    <section class="panel example-section">
        <div class="example-heading"><h3>Dialog e toast</h3><p>Confirmação e retorno breve sem perder o contexto.</p></div>
        <div class="component-row">
            <button class="button button-secondary" type="button" data-dialog-open="example-dialog">Abrir dialog</button>
            <button class="button button-primary" type="button" data-toast-trigger>Mostrar toast</button>
        </div>
    </section>
</div>

<section class="panel catalog-section" id="resumo">
    <div class="example-heading"><span class="eyebrow">06 · RESUMO</span><h3>Cards e indicadores</h3><p>Cards agrupam uma função; badges comunicam estado.</p></div>
    <div class="card-demo-grid">
        <article class="demo-card"><span class="eyebrow">INDICADOR</span><strong class="metric-value">1.284</strong><small>registros processados · +12%</small><div class="metric-progress"><i></i></div></article>
        <article class="demo-card"><span class="demo-card-icon">＋</span><h3>Criar novo registro</h3><p>Uma ação clara e recorrente.</p><button class="button button-secondary" type="button" data-toast-trigger>Começar</button></article>
        <article class="demo-card"><span class="status status-teste">Em análise</span><h3>Projeto demonstrativo</h3><p>Resumo curto com metadados úteis.</p><div class="card-meta"><span>Atualizado hoje</span><strong>3 responsáveis</strong></div></article>
    </div>
</section>

<section class="panel catalog-section" id="contexto">
    <div class="example-heading"><span class="eyebrow">07 · CONTEXTO</span><h3>Tabs, dropdown, modal e drawer</h3><p>Escolha conforme a profundidade e o volume de conteúdo.</p></div>
    <div class="tabs-demo" data-tabs>
        <div class="tab-list" role="tablist" aria-label="Perspectivas do registro"><button type="button" role="tab" aria-selected="true" data-tab="tab-resumo">Resumo</button><button type="button" role="tab" aria-selected="false" data-tab="tab-atividade">Atividade</button><button type="button" role="tab" aria-selected="false" data-tab="tab-acessos">Acessos</button></div>
        <div class="tab-panel" id="tab-resumo"><strong>Visão principal</strong><p>Tabs alternam perspectivas da mesma entidade.</p></div><div class="tab-panel" id="tab-atividade" hidden><strong>Atividade recente</strong><p>O histórico permanece no mesmo contexto.</p></div><div class="tab-panel" id="tab-acessos" hidden><strong>Controle de acesso</strong><p>A interface apenas reflete permissões validadas no servidor.</p></div>
    </div>
    <div class="component-row overlay-actions">
        <button class="row-menu-button" type="button" aria-label="Mais ações do exemplo" aria-haspopup="menu" aria-controls="example-actions" aria-expanded="false" data-row-menu-button="example-actions"><span aria-hidden="true">⋮</span></button>
        <div class="row-menu" id="example-actions" hidden><button type="button" data-toast-trigger>Duplicar</button><button type="button" data-toast-trigger>Arquivar</button><button class="danger" type="button" data-toast-trigger>Excluir</button></div>
        <button class="button button-secondary" data-dialog-open="example-dialog">Abrir modal</button><button class="button button-secondary" data-dialog-open="example-drawer">Abrir drawer</button>
    </div>
</section>

<section class="panel catalog-section" id="dados">
    <div class="example-heading"><span class="eyebrow">08 · OPERAÇÃO</span><h3>Filtros, tabela e paginação</h3><p>Composição completa para páginas de consulta.</p></div>
    <div class="table-toolbar"><input type="search" placeholder="Busca ilustrativa" aria-label="Busca ilustrativa de registros"><select aria-label="Filtro ilustrativo de status"><option>Todos os status</option><option>Ativo</option></select><button class="button button-secondary" type="button" data-toast-trigger>Filtros</button><button class="button button-primary" type="button" data-toast-trigger>Novo registro</button></div>
    <div class="table-wrap"><table><thead><tr><th>Registro</th><th>Responsável</th><th>Status</th><th>Atualização</th></tr></thead><tbody><tr><td><strong>Projeto Exemplo Alpha</strong><small>EX-001</small></td><td>Pessoa Exemplo 01</td><td><span class="status status-ativo">Ativo</span></td><td>Hoje, 10:42</td></tr><tr><td><strong>Projeto Exemplo Beta</strong><small>EX-002</small></td><td>Pessoa Exemplo 02</td><td><span class="status status-teste">Em análise</span></td><td>Ontem, 16:18</td></tr></tbody></table></div>
    <div class="table-footer"><span>Mostrando 1–2 de 18</span><div class="pagination"><button disabled>‹</button><button class="is-active" type="button" data-toast-trigger>1</button><button type="button" data-toast-trigger>2</button><button type="button" data-toast-trigger>3</button><button type="button" data-toast-trigger>›</button></div></div>
    <div class="empty-demo"><span>＋</span><strong>Nenhum resultado encontrado</strong><p>Ajuste os filtros ou limpe a busca.</p><button class="button button-secondary" type="button" data-toast-trigger>Limpar filtros</button></div>
</section>

<dialog class="dialog" id="example-dialog" aria-labelledby="example-dialog-title">
    <form method="dialog">
        <div class="dialog-header"><div><span class="eyebrow">Confirmação</span><h3 id="example-dialog-title">Continuar operação?</h3></div><button class="icon-button" value="cancel" aria-label="Fechar"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18"/></svg></button></div>
        <p>Dialogs mantêm o foco na decisão atual e devolvem o foco ao gatilho quando fechados.</p>
        <div class="form-field"><label for="modal-opcoes">Opções dentro do modal</label><select id="modal-opcoes" name="opcoes_modal[]" multiple data-multi-select><option value="alpha" selected>Opção Alpha</option><option value="beta">Opção Beta</option><option value="gama">Opção Gama</option></select><small data-multi-summary></small></div>
        <div class="dialog-actions"><button class="button button-secondary" value="cancel">Cancelar</button><button class="button button-primary" value="confirm">Confirmar</button></div>
    </form>
</dialog>

<dialog class="dialog dialog-drawer" id="example-drawer" aria-label="Detalhes do registro demonstrativo">
    <form method="dialog"><div class="dialog-header"><div><span class="eyebrow">EDIÇÃO CURTA</span><h3>Detalhes do registro</h3></div><button class="icon-button" value="cancel" aria-label="Fechar">×</button></div><div class="form-field"><label for="drawer-title">Título</label><input id="drawer-title" value="Projeto demonstrativo"></div><div class="form-field"><label for="drawer-notes">Descrição</label><textarea id="drawer-notes" rows="5">Conteúdo fictício.</textarea></div><div class="dialog-actions"><button class="button button-secondary" value="cancel">Cancelar</button><button class="button button-primary" value="confirm">Salvar exemplo</button></div></form>
</dialog>

<?php require APP_PATH . '/layout/footer.php'; ?>
