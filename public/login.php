<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$pageTitle = 'Entrar';
$submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

require APP_PATH . '/layout/auth_header.php';
?>
<div class="auth-heading">
    <span class="eyebrow">BOAS-VINDAS</span>
    <h2>Entre na sua conta</h2>
    <p>Use o identificador definido pelo produto: login corporativo ou e-mail validado.</p>
</div>

<div class="alert alert-info auth-demo-note"><i></i><div><strong>Demonstração visual</strong><span>Nenhuma credencial é validada e nenhuma sessão é criada. Não informe uma senha real.</span></div></div>

<form method="post" data-auth-demo data-success="Fluxo demonstrativo concluído. Conecte autenticação e autorização antes de usar dados reais.">
    <div class="form-field">
        <label for="login-identificador">Login ou e-mail</label>
        <input id="login-identificador" name="identificador" type="text" maxlength="160" autocomplete="username" required autofocus>
    </div>
    <div class="form-field">
        <div class="auth-label-row"><label for="login-senha">Senha</label><a href="<?= APP_URL ?>/recuperar-senha.php">Esqueci minha senha</a></div>
        <div class="password-field"><input id="login-senha" name="senha" type="password" autocomplete="current-password" required><button type="button" aria-controls="login-senha" aria-pressed="false" data-password-toggle>Mostrar</button></div>
    </div>
    <button class="button button-primary auth-submit" type="submit">Entrar</button>
    <p class="form-message" role="status" tabindex="-1"<?= $submitted ? '' : ' hidden' ?> data-auth-message><?= $submitted ? 'Fluxo demonstrativo concluído. Nenhuma sessão foi criada.' : '' ?></p>
</form>

<?php require APP_PATH . '/layout/auth_footer.php'; ?>
