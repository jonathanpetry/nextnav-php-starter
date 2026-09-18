<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$pageTitle = 'Recuperar senha';
$submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

require APP_PATH . '/layout/auth_header.php';
?>
<a class="auth-back" href="<?= APP_URL ?>/login.php">← Voltar para entrar</a>
<div class="auth-heading">
    <span class="eyebrow">RECUPERAÇÃO</span>
    <h2>Recupere seu acesso</h2>
    <p>Informe o login ou e-mail utilizado no sistema.</p>
</div>

<form method="post" data-auth-demo data-success="Se os dados corresponderem a uma conta elegível, as instruções serão enviadas pelo canal configurado.">
    <div class="form-field">
        <label for="recuperar-identificador">Login ou e-mail</label>
        <input id="recuperar-identificador" name="identificador" type="text" maxlength="160" autocomplete="username" required autofocus>
    </div>
    <button class="button button-primary auth-submit" type="submit">Enviar instruções</button>
    <p class="form-message" role="status" tabindex="-1"<?= $submitted ? '' : ' hidden' ?> data-auth-message><?= $submitted ? 'Se os dados corresponderem a uma conta elegível, as instruções serão enviadas pelo canal configurado.' : '' ?></p>
</form>

<p class="auth-security-note">A resposta é sempre genérica para não revelar se uma conta existe.</p>

<?php require APP_PATH . '/layout/auth_footer.php'; ?>
