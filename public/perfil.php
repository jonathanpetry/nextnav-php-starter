<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$pageTitle = 'Meu perfil';
$activePage = 'perfil';

require APP_PATH . '/layout/menu.php';
?>

<section class="page-heading">
    <div>
        <span class="eyebrow">Conta</span>
        <h2>Perfil do usuário</h2>
        <p>Demonstração visual, sem autenticação ou gravação. Não informe uma senha real.</p>
    </div>
</section>

<div class="profile-grid">
    <section class="panel form-panel">
        <h3>Dados pessoais</h3>
        <p>Informações usadas para identificar o usuário no sistema.</p>

        <form method="post" data-mock-form>
            <div class="form-grid">
                <div class="form-field form-field-full">
                    <label for="nome">Nome completo</label>
                    <input id="nome" name="nome" type="text" value="Usuário Demonstração" required>
                </div>
                <div class="form-field">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" value="usuario@produto.example" required>
                </div>
                <div class="form-field">
                    <label for="cargo">Cargo</label>
                    <input id="cargo" name="cargo" type="text" value="Administrador">
                </div>
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Salvar perfil</button></div>
            <p class="form-message" role="status" hidden data-form-message>Perfil validado. No protótipo, os dados ainda não são persistidos.</p>
        </form>
    </section>

    <section class="panel form-panel" id="senha">
        <h3>Trocar senha</h3>
        <p>A senha será conectada ao backend quando a autenticação real for implementada.</p>

        <form method="post" data-mock-form>
            <div class="form-grid">
                <div class="form-field form-field-full">
                    <label for="senha-atual">Senha atual</label>
                    <input id="senha-atual" name="senha_atual" type="password" autocomplete="current-password" required>
                </div>
                <div class="form-field form-field-full">
                    <label for="nova-senha">Nova senha</label>
                    <input id="nova-senha" name="nova_senha" type="password" minlength="12" autocomplete="new-password" required>
                </div>
                <div class="form-field form-field-full">
                    <label for="confirmar-senha">Confirmar nova senha</label>
                    <input id="confirmar-senha" name="confirmar_senha" type="password" minlength="12" autocomplete="new-password" required>
                </div>
            </div>
            <div class="form-actions"><button class="button button-primary" type="submit">Atualizar senha</button></div>
            <p class="form-message" role="status" hidden data-form-message>Fluxo validado. Nenhuma senha foi armazenada neste mock.</p>
        </form>
    </section>
</div>

<?php require APP_PATH . '/layout/footer.php'; ?>
