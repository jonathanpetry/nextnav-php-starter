const authThemeButton = document.querySelector('[data-auth-theme]');
const authThemeStorageKey = `${document.documentElement.dataset.appKey}_theme`;

function setAuthTheme(theme) {
    document.documentElement.dataset.theme = theme;
    authThemeButton?.setAttribute('aria-pressed', String(theme === 'dark'));
    try {
        localStorage.setItem(authThemeStorageKey, theme);
    } catch { /* O tema continua funcional sem armazenamento. */ }
}

authThemeButton?.addEventListener('click', () => {
    setAuthTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
});
setAuthTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const input = document.getElementById(button.getAttribute('aria-controls'));
    if (!input) return;
    button.addEventListener('click', () => {
        const visible = input.type === 'text';
        input.type = visible ? 'password' : 'text';
        button.textContent = visible ? 'Mostrar' : 'Ocultar';
        button.setAttribute('aria-pressed', String(!visible));
        input.focus();
    });
});

document.querySelectorAll('[data-auth-demo]').forEach((form) => {
    const message = form.querySelector('[data-auth-message]');
    const password = form.elements.nova_senha;
    const confirmation = form.elements.confirmar_senha;

    confirmation?.addEventListener('input', () => confirmation.setCustomValidity(''));
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        if (password && confirmation && password.value !== confirmation.value) {
            confirmation.setCustomValidity('A confirmação precisa ser igual à nova senha.');
            confirmation.reportValidity();
            return;
        }
        if (!form.reportValidity()) return;

        message.textContent = form.dataset.success;
        message.classList.remove('is-error');
        message.hidden = false;
        if (form.elements.senha) form.elements.senha.value = '';
        if (password) form.reset();
        message.focus();
    });
});
