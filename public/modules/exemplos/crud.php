<?php

declare(strict_types=1);

require dirname(__DIR__, 3) . '/app/bootstrap.php';

// Endpoint didático: valida dados, mas não grava banco, arquivo ou sessão.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store');
    $dados = [];
    $erros = [];
    foreach (['acao', 'id', 'nome', 'tipo', 'status'] as $campo) {
        if (isset($_POST[$campo]) && !is_string($_POST[$campo])) {
            $erros[] = 'Formato de campo inválido.';
        }
        $dados[$campo] = is_string($_POST[$campo] ?? null) ? trim($_POST[$campo]) : '';
    }
    if (!in_array($dados['acao'], ['salvar', 'excluir'], true)) {
        $erros[] = 'Ação inválida.';
    }
    if (($dados['id'] !== '' && preg_match('/^[1-9][0-9]{0,8}$/D', $dados['id']) !== 1)
        || ($dados['acao'] === 'excluir' && $dados['id'] === '')) {
        $erros[] = 'Registro inválido.';
    }
    if ($dados['acao'] === 'salvar') {
        if (preg_match('/\A[^\r\n]{2,80}\z/u', $dados['nome']) !== 1) {
            $erros[] = 'Informe um nome de 2 a 80 caracteres.';
        }
        if (!in_array($dados['tipo'], ['Assinatura', 'Serviço'], true)) {
            $erros[] = 'Selecione um tipo válido.';
        }
        if (!in_array($dados['status'], ['Ativo', 'Teste', 'Inativo'], true)) {
            $erros[] = 'Selecione um status válido.';
        }
    }
    if ($erros !== []) {
        http_response_code(422);
        echo json_encode(['ok' => false, 'message' => implode(' ', $erros)], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    } elseif (($_POST['simular_falha'] ?? '') === '1') {
        http_response_code(503);
        echo json_encode(['ok' => false, 'message' => 'Falha simulada. Os dados foram preservados. Desmarque a opção e tente novamente.'], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode(['ok' => true, 'record' => $dados['acao'] === 'salvar' ? $dados : ['id' => $dados['id']]], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$pageTitle = 'CRUD demonstrativo';
$activePage = 'exemplos-crud';
$registros = [
    ['id' => 1, 'nome' => 'Plano Essencial', 'tipo' => 'Assinatura', 'status' => 'Ativo', 'atualizado' => 'Dados iniciais'],
    ['id' => 2, 'nome' => 'Plano Profissional', 'tipo' => 'Assinatura', 'status' => 'Ativo', 'atualizado' => 'Dados iniciais'],
    ['id' => 3, 'nome' => 'Serviço de implantação', 'tipo' => 'Serviço', 'status' => 'Teste', 'atualizado' => 'Dados iniciais'],
];

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div><span class="eyebrow">Exemplos</span><h2>CRUD demonstrativo</h2><p>Crie, edite, duplique e exclua. Os registros existem somente nesta aba e reiniciam ao recarregar.</p></div>
    <button class="button button-primary" type="button" id="crud-new" disabled>Novo registro</button>
</section>

<noscript><p class="alert alert-warning">Ative o JavaScript para usar este exemplo interativo. Nenhuma alteração é persistida no servidor.</p></noscript>
<p id="crud-feedback" role="status" aria-live="polite" hidden></p>

<section class="crud-summary" aria-label="Resumo dos registros">
    <article><span>Total</span><strong id="crud-total"><?= count($registros) ?></strong><small>registros nesta aba</small></article>
    <article><span>Ativos</span><strong id="crud-active">2</strong><small>disponíveis para uso</small></article>
    <article><span>Em teste</span><strong id="crud-testing">1</strong><small>aguardando validação</small></article>
</section>

<section class="panel">
    <div class="panel-header"><div><h3>Registros</h3><p id="crud-count">Demonstração sem banco, autenticação ou persistência.</p></div><span class="tag">Mock</span></div>
    <form class="crud-toolbar" id="crud-filters" method="get" data-no-loader>
        <input type="search" name="busca" id="crud-search" aria-label="Buscar por nome ou tipo" placeholder="Buscar por nome ou tipo">
        <select name="status" id="crud-filter-status" aria-label="Filtrar por status"><option value="">Todos os status</option><option>Ativo</option><option>Teste</option><option>Inativo</option></select>
        <button class="button button-secondary" type="reset">Limpar filtros</button>
    </form>
    <div class="table-wrap" id="crud-table">
        <table>
            <thead><tr><th scope="col">Nome</th><th scope="col">Tipo</th><th scope="col">Status</th><th scope="col">Atualizado</th><th scope="col" class="table-actions">Ações</th></tr></thead>
            <tbody id="crud-rows">
                <?php foreach ($registros as $registro): ?>
                    <tr><td><?= htmlspecialchars($registro['nome'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td><td><?= htmlspecialchars($registro['tipo'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td><td><?= htmlspecialchars($registro['status'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></td><td>Dados iniciais</td><td>—</td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="empty-demo" id="crud-empty" hidden><strong>Nenhum registro encontrado</strong><p>Limpe os filtros ou crie um novo registro.</p></div>
</section>

<section class="panel audit-panel">
    <div class="panel-header"><div><h3>Atividade nesta aba</h3><p>Histórico didático, não substitui auditoria real.</p></div><span class="status status-inativo">Mock</span></div>
    <div class="audit-list" id="crud-audit"><p>Nenhuma alteração nesta visita.</p></div>
</section>

<template id="crud-row-template">
    <tr>
        <td><strong data-record-name></strong></td><td data-record-type></td><td><span class="status" data-record-status></span></td><td data-record-updated></td>
        <td class="table-actions">
            <button class="row-menu-button" type="button" aria-haspopup="menu" aria-expanded="false"><span aria-hidden="true">⋮</span></button>
            <div class="row-menu" hidden><button type="button" data-record-action="edit">Editar</button><button type="button" data-record-action="duplicate">Duplicar</button><button type="button" data-record-action="delete" class="danger">Excluir</button></div>
        </td>
    </tr>
</template>

<dialog class="dialog" id="crud-dialog" aria-labelledby="crud-dialog-title">
    <form method="post" id="crud-form">
        <input type="hidden" name="acao" value="salvar"><input type="hidden" name="id">
        <div class="dialog-header"><div><span class="eyebrow">Cadastro demonstrativo</span><h3 id="crud-dialog-title">Novo registro</h3></div><button class="icon-button" type="button" data-dialog-close aria-label="Fechar">×</button></div>
        <p class="alert alert-error" id="crud-error" role="alert" tabindex="-1" hidden></p>
        <div class="form-field"><label for="crud-nome">Nome (obrigatório)</label><input id="crud-nome" name="nome" type="text" minlength="2" maxlength="80" required autocomplete="off"></div>
        <div class="form-field"><label for="crud-tipo">Tipo</label><select id="crud-tipo" name="tipo" required><option>Assinatura</option><option>Serviço</option></select></div>
        <div class="form-field"><label for="crud-status">Status</label><select id="crud-status" name="status" required><option>Ativo</option><option>Teste</option><option>Inativo</option></select></div>
        <label class="check-field"><input type="checkbox" name="simular_falha" value="1">Simular falha do servidor para testar o loader</label>
        <div class="dialog-actions"><button class="button button-secondary" type="button" data-dialog-close>Cancelar</button><button class="button button-primary" type="submit">Salvar mock</button></div>
    </form>
</dialog>

<dialog class="dialog" id="crud-delete-dialog" aria-labelledby="crud-delete-title" aria-describedby="crud-delete-description">
    <form method="post" id="crud-delete-form">
        <input type="hidden" name="acao" value="excluir"><input type="hidden" name="id">
        <div class="dialog-header"><h3 id="crud-delete-title">Excluir registro?</h3><button class="icon-button" type="button" data-dialog-close aria-label="Fechar">×</button></div>
        <p id="crud-delete-description"></p>
        <p class="alert alert-error" id="crud-delete-error" role="alert" tabindex="-1" hidden></p>
        <div class="dialog-actions"><button class="button button-secondary" type="button" data-dialog-close autofocus>Cancelar</button><button class="button button-danger" type="submit">Excluir mock</button></div>
    </form>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let records = <?= json_encode($registros, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    let nextId = 4;
    let pending = false;
    const rows = document.getElementById('crud-rows');
    const template = document.getElementById('crud-row-template');
    const filters = document.getElementById('crud-filters');
    const form = document.getElementById('crud-form');
    const dialog = document.getElementById('crud-dialog');
    const deleteDialog = document.getElementById('crud-delete-dialog');
    const feedback = document.getElementById('crud-feedback');
    const audit = document.getElementById('crud-audit');
    let dialogReturnFocus = document.getElementById('crud-new');

    function renderRecords() {
        const query = filters.elements.busca.value.trim().toLocaleLowerCase('pt-BR');
        const status = filters.elements.status.value;
        const visible = records.filter(record => (!status || record.status === status) && `${record.nome} ${record.tipo}`.toLocaleLowerCase('pt-BR').includes(query));
        rows.replaceChildren();
        visible.forEach(record => {
            const row = template.content.firstElementChild.cloneNode(true);
            row.dataset.recordId = record.id;
            row.querySelector('[data-record-name]').textContent = record.nome;
            row.querySelector('[data-record-type]').textContent = record.tipo;
            row.querySelector('[data-record-updated]').textContent = record.atualizado;
            const badge = row.querySelector('[data-record-status]');
            badge.textContent = record.status;
            badge.classList.add(`status-${record.status.toLowerCase()}`);
            const menuId = `crud-row-menu-${record.id}`;
            const button = row.querySelector('.row-menu-button');
            button.dataset.rowMenuButton = menuId;
            button.setAttribute('aria-controls', menuId);
            button.setAttribute('aria-label', `Ações de ${record.nome}`);
            row.querySelector('.row-menu').id = menuId;
            rows.append(row);
        });
        document.getElementById('crud-table').hidden = visible.length === 0;
        document.getElementById('crud-empty').hidden = visible.length > 0;
        document.getElementById('crud-count').textContent = `${visible.length} de ${records.length} registros. Alterações somente nesta aba.`;
        document.getElementById('crud-total').textContent = records.length;
        document.getElementById('crud-active').textContent = records.filter(record => record.status === 'Ativo').length;
        document.getElementById('crud-testing').textContent = records.filter(record => record.status === 'Teste').length;
    }

    function openRecord(record = null, duplicate = false) {
        dialogReturnFocus = record ? document.querySelector(`[data-row-menu-button="crud-row-menu-${record.id}"]`) : document.getElementById('crud-new');
        form.reset();
        document.getElementById('crud-error').hidden = true;
        form.elements.id.value = record && !duplicate ? record.id : '';
        if (record) {
            form.elements.nome.value = duplicate ? `${record.nome.slice(0, 72)} (cópia)` : record.nome;
            form.elements.tipo.value = record.tipo;
            form.elements.status.value = record.status;
        }
        document.getElementById('crud-dialog-title').textContent = duplicate ? 'Duplicar registro' : record ? 'Editar registro' : 'Novo registro';
        dialog.showModal();
        form.elements.nome.focus();
    }

    document.getElementById('crud-new').disabled = false;
    document.getElementById('crud-new').addEventListener('click', () => openRecord());
    filters.addEventListener('submit', event => event.preventDefault());
    filters.addEventListener('input', renderRecords);
    filters.addEventListener('reset', () => setTimeout(renderRecords, 0));
    rows.addEventListener('click', event => {
        const action = event.target.closest('[data-record-action]');
        if (!action) return;
        const record = records.find(item => item.id === Number(action.closest('tr').dataset.recordId));
        if (!record) return;
        if (action.dataset.recordAction !== 'delete') {
            openRecord(record, action.dataset.recordAction === 'duplicate');
            return;
        }
        document.getElementById('crud-delete-form').elements.id.value = record.id;
        dialogReturnFocus = document.querySelector(`[data-row-menu-button="crud-row-menu-${record.id}"]`);
        document.getElementById('crud-delete-description').textContent = `“${record.nome}” será removido apenas deste mock.`;
        document.getElementById('crud-delete-error').hidden = true;
        deleteDialog.showModal();
    });

    [form, document.getElementById('crud-delete-form')].forEach(currentForm => {
        currentForm.addEventListener('submit', async event => {
            event.preventDefault();
            if (pending) return;
            const body = new FormData(currentForm);
            const deleting = body.get('acao') === 'excluir';
            const currentDialog = currentForm.closest('dialog');
            const error = document.getElementById(deleting ? 'crud-delete-error' : 'crud-error');
            const buttons = [...currentForm.querySelectorAll('button')].map(button => [button, button.disabled]);
            error.hidden = true;
            pending = true;
            buttons.forEach(([button]) => { button.disabled = true; });
            currentDialog.setAttribute('aria-busy', 'true');
            currentForm.setAttribute('aria-busy', 'true');
            NextNavLoader.show();
            let message = '';
            try {
                const response = await fetch(window.location.pathname, {method: 'POST', body, headers: {'Accept': 'application/json'}, signal: AbortSignal.timeout(15000)});
                const result = await response.json();
                if (!response.ok || !result.ok) throw new Error(result.message || 'Não foi possível concluir a operação.');
                const id = Number(body.get('id'));
                const existing = records.find(record => record.id === id);
                if (deleting) {
                    records = records.filter(record => record.id !== id);
                    message = `${existing?.nome || 'Registro'} excluído do mock.`;
                } else {
                    const record = {id: id || nextId++, nome: result.record.nome, tipo: result.record.tipo, status: result.record.status, atualizado: 'Agora'};
                    if (existing) records[records.indexOf(existing)] = record;
                    else records.push(record);
                    message = `${record.nome}: ${existing ? 'alterações salvas' : 'registro criado'} no mock.`;
                }
            } catch (failure) {
                error.textContent = failure.name === 'TimeoutError' ? 'O servidor demorou para responder. Tente novamente.' : failure instanceof SyntaxError || failure instanceof TypeError ? 'Não foi possível comunicar com o servidor. Tente novamente.' : failure.message;
                error.hidden = false;
            } finally {
                await NextNavLoader.hide();
                buttons.forEach(([button, disabled]) => { button.disabled = disabled; });
                currentDialog.removeAttribute('aria-busy');
                currentForm.removeAttribute('aria-busy');
                pending = false;
            }
            if (!message) {
                error.focus();
                return;
            }
            currentDialog.close();
            renderRecords();
            feedback.className = 'alert alert-success';
            feedback.textContent = message;
            feedback.hidden = false;
            if (!audit.querySelector('article')) audit.replaceChildren();
            const entry = document.createElement('article');
            const marker = document.createElement('i');
            const description = document.createElement('strong');
            description.textContent = message;
            const time = document.createElement('time');
            time.textContent = new Date().toLocaleTimeString('pt-BR', {hour: '2-digit', minute: '2-digit'});
            entry.append(marker, description, time);
            audit.prepend(entry);
            if (audit.children.length > 8) audit.lastElementChild.remove();
            document.getElementById('crud-new').focus();
        });
        currentForm.closest('dialog').addEventListener('cancel', event => { if (pending) event.preventDefault(); });
        currentForm.closest('dialog').addEventListener('close', () => {
            (dialogReturnFocus?.isConnected ? dialogReturnFocus : document.getElementById('crud-new')).focus();
        });
    });
    renderRecords();
});
</script>

<?php require APP_PATH . '/layout/footer.php'; ?>
