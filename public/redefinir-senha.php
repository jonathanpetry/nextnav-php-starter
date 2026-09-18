<?php

declare(strict_types=1);

require dirname(__DIR__) . '/app/bootstrap.php';

$pageTitle = 'Redefinir senha';
$state = $_GET['estado'] ?? '';
$state = is_string($state) ? $state : 'invalido';
$state = in_array($state, ['', 'expirado', 'invalido'], true) ? $state : 'invalido';
$submitted = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
$passwordValid = false;

if ($submitted && $state === '') {
    $password = is_string($_POST['nova_senha'] ?? null) ? $_POST['nova_senha'] : '';
    $confirmation = is_string($_POST['confirmar_senha'] ?? null) ? $_POST['confirmar_senha'] : '';
    $passwordValid = strlen($password) >= 12 && $password === $confirmation;
}

require APP_PATH . '/layout/auth_header.php';
?>
<?php if ($state !== ''): ?>
    <div class="auth-state">
        <span class="auth-state-icon" aria-hidden="true">!</span>
        <span class="eyebrow">LINK <?= $state === 'expirado' ? 'EXPIRADO' : 'INVÁLIDO' ?></span>
        <h2>Solicite uma nova recuperação</h2>
        <p>Este link não pode mais ser utilizado. Por segurança, links de redefinição devem ser temporários e de uso único.</p>
        <a class="button button-primary" href="<?= APP_URL ?>/recuperar-senha.php">Solicitar novo link</a>
        <a class="button button-ghost" href="<?= APP_URL ?>/login.php">Voltar para entrar</a>
    </div>
<?php else: ?>
    <a class="auth-back" href="<?= APP_URL ?>/login.php">← Voltar para entrar</a>
    <div class="auth-heading">
        <span class="eyebrow">NOVA SENHA</span>
        <h2>Defina uma nova senha</h2>
        <p>O produto final deve validar um token temporário antes de exibir este formulário.</p>
    </div>
    <form method="post" data-auth-demo data-success="Fluxo demonstrativo validado. Nenhuma senha foi armazenada.">
        <div class="form-field">
            <label for="nova-senha">Nova senha</label>
            <div class="password-field"><input id="nova-senha" name="nova_senha" type="password" minlength="12" autocomplete="new-password" required autofocus><button type="button" aria-controls="nova-senha" aria-pressed="false" data-password-toggle>Mostrar</button></div>
            <small>Use no mínimo 12 caracteres nesta demonstração.</small>
        </div>
        <div class="form-field">
            <label for="confirmar-senha">Confirmar nova senha</label>
            <div class="password-field"><input id="confirmar-senha" name="confirmar_senha" type="password" minlength="12" autocomplete="new-password" required><button type="button" aria-controls="confirmar-senha" aria-pressed="false" data-password-toggle>Mostrar</button></div>
        </div>
        <button class="button button-primary auth-submit" type="submit">Redefinir senha</button>
        <p class="form-message<?= $submitted && !$passwordValid ? ' is-error' : '' ?>" role="status" tabindex="-1"<?= $submitted ? '' : ' hidden' ?> data-auth-message><?= $submitted ? ($passwordValid ? 'Fluxo demonstrativo validado. Nenhuma senha foi armazenada.' : 'As senhas devem ser iguais e ter pelo menos 12 caracteres.') : '' ?></p>
    </form>
<?php endif; ?>

<?php require APP_PATH . '/layout/auth_footer.php'; ?>
