// Loader primeiro: uma falha em outro componente não deixa a abertura bloqueada.
const appLoader = document.querySelector('[data-app-loader]');
const loaderMinimumDuration = Number(document.documentElement.dataset.loaderMinimumDuration) || 0;
let loaderShownAt = performance.now();
let loaderHideTimer;
let loadingOperations = 0;
const loaderWaiters = [];

function setAppLoading(loading) {
    if (!appLoader) return;
    clearTimeout(loaderHideTimer);
    if (loading) {
        if (!appLoader.open) {
            loaderShownAt = performance.now();
            appLoader.showModal(); // Camada nativa acima inclusive de um formulário modal.
        }
    } else {
        const remaining = Math.max(0, loaderMinimumDuration - (performance.now() - loaderShownAt));
        loaderHideTimer = setTimeout(() => {
            if (loadingOperations === 0) {
                if (appLoader.open) appLoader.close();
                loaderWaiters.splice(0).forEach(resolve => resolve());
            }
        }, remaining);
    }
}

const localLoading = new WeakMap();
window.NextNavLoader = {
    show() { loadingOperations++; setAppLoading(true); },
    hide() {
        loadingOperations = Math.max(0, loadingOperations - 1);
        if (!appLoader) return Promise.resolve();
        return new Promise(resolve => {
            loaderWaiters.push(resolve);
            if (!loadingOperations) setAppLoading(false);
        });
    },
    setLocal(element, loading) {
        if (!(element instanceof HTMLElement)) return;
        if (loading && !localLoading.has(element)) {
            const children = Array.from(element.children).map(child => [child, child.inert]);
            const previousFocus = element.contains(document.activeElement) ? document.activeElement : null;
            const previousBusy = element.getAttribute('aria-busy');
            const indicator = document.createElement('div');
            indicator.className = 'local-loader';
            indicator.setAttribute('role', 'status');
            indicator.tabIndex = -1;
            indicator.innerHTML = '<span class="button-spinner" aria-hidden="true"></span><span>Carregando...</span>';
            children.forEach(([child]) => { child.inert = true; });
            element.classList.add('is-loading');
            element.setAttribute('aria-busy', 'true');
            element.append(indicator);
            localLoading.set(element, { children, previousFocus, previousBusy, indicator });
            if (previousFocus) indicator.focus();
        } else if (!loading && localLoading.has(element)) {
            const { children, previousFocus, previousBusy, indicator } = localLoading.get(element);
            const restoreFocus = document.activeElement === indicator;
            children.forEach(([child, inert]) => { child.inert = inert; });
            indicator.remove();
            element.classList.remove('is-loading');
            if (previousBusy === null) element.removeAttribute('aria-busy');
            else element.setAttribute('aria-busy', previousBusy);
            localLoading.delete(element);
            if (restoreFocus && previousFocus?.isConnected) previousFocus.focus();
        }
    },
};
appLoader?.addEventListener('cancel', event => event.preventDefault());
window.addEventListener('pageshow', event => { if (event.persisted) loadingOperations = 0; setAppLoading(false); });
setAppLoading(false);

// Observe a navegação nativa sem substituir cliques, alvos ou cancelamentos de outros scripts.
document.addEventListener('click', event => {
    if (event.button !== 0 || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
    const link = event.target.closest('a[href]');
    if (!link || link.hasAttribute('download') || link.hasAttribute('data-no-loader')) return;
    const target = link.getAttribute('target') ?? document.querySelector('base[target]')?.target ?? '';
    if (target && target.toLowerCase() !== '_self') return;
    const destination = new URL(link.href, location.href);
    if (!['http:', 'https:'].includes(destination.protocol) || destination.origin !== location.origin) return;
    if (destination.pathname === location.pathname && destination.search === location.search && destination.href.includes('#')) return;
    setTimeout(() => { if (!event.defaultPrevented) NextNavLoader.show(); }, 0);
});

document.addEventListener('submit', event => {
    const form = event.target;
    const submitter = event.submitter;
    const method = (submitter?.getAttribute('formmethod') ?? form.getAttribute('method') ?? 'get').toLowerCase();
    const target = submitter?.getAttribute('formtarget') ?? form.getAttribute('target') ?? document.querySelector('base[target]')?.target ?? '';
    if (method === 'dialog' || (target && target.toLowerCase() !== '_self')) return;
    if (form.hasAttribute('data-no-loader') || submitter?.hasAttribute('data-no-loader')) return;
    const action = new URL(submitter?.getAttribute('formaction') ?? form.getAttribute('action') ?? location.href, location.href);
    if (!['http:', 'https:'].includes(action.protocol) || action.origin !== location.origin) return;
    setTimeout(() => { if (!event.defaultPrevented) NextNavLoader.show(); }, 0);
});

const menu = document.querySelector('[data-menu]');
const overlay = document.querySelector('[data-menu-overlay]');
const appArea = document.querySelector('[data-app-area]');
const openButton = document.querySelector('[data-menu-open]');
const closeButton = document.querySelector('[data-menu-close]');
const backButton = document.querySelector('[data-menu-back]');
const levelHeader = document.querySelector('[data-menu-level-header]');
const menuTitle = document.querySelector('[data-menu-title]');
const menuDescription = document.querySelector('[data-menu-description]');
const accountButton = document.querySelector('[data-account-open]');
const accountMenu = document.querySelector('[data-account-menu]');
const themeToggle = document.querySelector('[data-theme-toggle]');
const levelHistory = ['root'];

function focusableElements(container) {
    return Array.from(container.querySelectorAll('a[href],button:not(:disabled),input:not(:disabled),select:not(:disabled),textarea:not(:disabled),[tabindex="0"]'))
        .filter(element => element.getClientRects().length && !element.closest('[inert]'));
}

function currentPageHistory() {
    const activeLevel = document.querySelector('.menu-entry.is-active')?.closest('[data-menu-level]');
    const history = [];
    let levelId = activeLevel?.dataset.menuLevel ?? 'root';
    while (levelId !== 'root' && !history.includes(levelId)) {
        history.unshift(levelId);
        const parent = Array.from(document.querySelectorAll('[data-menu-target]'))
            .find(button => button.dataset.menuTarget === levelId);
        levelId = parent?.closest('[data-menu-level]')?.dataset.menuLevel ?? 'root';
    }
    return ['root', ...history];
}

function showLevel(levelId) {
    const levels = Array.from(document.querySelectorAll('[data-menu-level]'));
    const level = levels.find(item => item.dataset.menuLevel === levelId) ?? levels[0];
    levels.forEach(item => { item.hidden = item !== level; });
    menuTitle.textContent = level.dataset.levelTitle;
    menuDescription.textContent = level.dataset.levelDescription;
    levelHeader.hidden = level.dataset.menuLevel === 'root';
    (level.querySelector('.menu-entry.is-active') ?? level.querySelector('.menu-entry'))?.focus();
}

function setMenu(open) {
    if (open) setAccountMenu(false);
    menu.classList.toggle('is-open', open);
    overlay.classList.toggle('is-visible', open);
    menu.inert = !open;
    appArea.inert = open;
    openButton.setAttribute('aria-expanded', String(open));
    document.body.classList.toggle('menu-open', open);
    if (open) {
        menu.setAttribute('aria-hidden', 'false');
        levelHistory.splice(0, levelHistory.length, ...currentPageHistory());
        showLevel(levelHistory.at(-1));
    } else {
        openButton.focus();
        menu.setAttribute('aria-hidden', 'true');
    }
}

function setAccountMenu(open) {
    accountMenu.hidden = !open;
    accountButton.setAttribute('aria-expanded', String(open));
    if (open) focusableElements(accountMenu)[0]?.focus();
}

function applyTheme(theme) {
    document.documentElement.dataset.theme = theme;
    try { localStorage.setItem(document.documentElement.dataset.appKey + '_theme', theme); } catch { /* Sem persistência, não sem tema. */ }
    themeToggle?.setAttribute('aria-checked', String(theme === 'dark'));
}

openButton.addEventListener('click', () => setMenu(true));
closeButton.addEventListener('click', () => setMenu(false));
overlay.addEventListener('click', () => setMenu(false));
backButton.addEventListener('click', () => { levelHistory.pop(); showLevel(levelHistory.at(-1) ?? 'root'); });
document.querySelectorAll('[data-menu-target]').forEach(button => {
    button.addEventListener('click', () => { levelHistory.push(button.dataset.menuTarget); showLevel(button.dataset.menuTarget); });
});
document.querySelectorAll('[data-menu-leaf]').forEach(link => link.addEventListener('click', () => setMenu(false)));
menu.addEventListener('keydown', event => {
    if (event.key !== 'Tab') return;
    const items = focusableElements(menu);
    if (event.shiftKey && document.activeElement === items[0]) { event.preventDefault(); items.at(-1)?.focus(); }
    else if (!event.shiftKey && document.activeElement === items.at(-1)) { event.preventDefault(); items[0]?.focus(); }
});
accountButton.addEventListener('click', () => setAccountMenu(accountMenu.hidden));
themeToggle?.addEventListener('click', () => applyTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark'));
accountMenu.addEventListener('keydown', event => {
    const items = focusableElements(accountMenu);
    const index = items.indexOf(document.activeElement);
    if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
        event.preventDefault();
        const next = event.key === 'Home' ? 0 : event.key === 'End' ? items.length - 1 : (index + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
        items[next]?.focus();
    }
});
document.addEventListener('click', event => {
    if (!accountMenu.hidden && !accountMenu.contains(event.target) && !accountButton.contains(event.target)) setAccountMenu(false);
});
document.addEventListener('focusin', event => {
    if (!accountMenu.hidden && !accountMenu.contains(event.target) && !accountButton.contains(event.target)) setAccountMenu(false);
});
document.addEventListener('keydown', event => {
    if (event.key !== 'Escape' || event.defaultPrevented || appLoader?.open || document.querySelector(':popover-open')) return;
    if (!accountMenu.hidden) { setAccountMenu(false); accountButton.focus(); }
    else if (menu.classList.contains('is-open')) setMenu(false);
});
applyTheme(document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light');

// Um posicionamento para todos os popups, fora do fluxo da tabela e dentro da viewport.
function positionPopup(popup, trigger, matchWidth = false) {
    const bounds = trigger.getBoundingClientRect();
    if (matchWidth) popup.style.width = Math.min(Math.max(280, bounds.width), innerWidth - 16) + 'px';
    const width = popup.offsetWidth;
    const height = popup.offsetHeight;
    popup.style.left = Math.max(8, Math.min(matchWidth ? bounds.left : bounds.right - width, innerWidth - width - 8)) + 'px';
    popup.style.top = Math.max(8, innerHeight - bounds.bottom >= height + 12 ? bounds.bottom + 6 : bounds.top - height - 6) + 'px';
}

// O select nativo é a fonte de verdade: valores, POST/FormData, selected, disabled e reset.
document.querySelectorAll('select[multiple][data-multi-select]').forEach((select, index) => {
    if (!('showPopover' in HTMLElement.prototype)) return; // Mantém select nativo utilizável.
    select.id ||= 'multi-select-' + index;
    const wrapper = document.createElement('div');
    wrapper.className = 'multi-select';
    select.before(wrapper);
    wrapper.append(select);
    const trigger = document.createElement('button');
    trigger.type = 'button';
    trigger.id = select.id + '-trigger';
    trigger.className = 'multi-select-trigger';
    const panel = document.createElement('div');
    panel.id = 'multi-select-panel-' + index;
    panel.className = 'multi-select-panel';
    panel.popover = 'auto';
    panel.setAttribute('role', 'group');
    const label = select.labels?.[0];
    const labelText = label?.textContent.trim() || 'Opções';
    panel.setAttribute('aria-label', labelText);
    trigger.setAttribute('aria-controls', panel.id);
    trigger.setAttribute('aria-expanded', 'false');
    trigger.popoverTargetElement = panel;
    if (label) label.htmlFor = trigger.id;
    const search = document.createElement('input');
    search.type = 'search';
    search.className = 'multi-select-search';
    search.placeholder = 'Filtrar opções...';
    search.setAttribute('aria-label', 'Filtrar ' + labelText.toLocaleLowerCase('pt-BR'));
    const actions = document.createElement('div');
    actions.className = 'multi-select-actions';
    const selectAll = document.createElement('button');
    selectAll.type = 'button';
    selectAll.textContent = 'Selecionar resultados';
    const clear = document.createElement('button');
    clear.type = 'button';
    clear.textContent = 'Limpar tudo';
    const options = document.createElement('div');
    options.className = 'multi-select-options';
    const summary = select.closest('.form-field')?.querySelector('[data-multi-summary]');
    actions.append(selectAll, clear);
    panel.append(search, actions, options);
    wrapper.append(trigger, panel);

    const normalized = text => text.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase('pt-BR');
    function updateSummary() {
        const selected = Array.from(select.selectedOptions);
        trigger.textContent = selected.length ? selected.length + ' selecionado(s) ▾' : 'Selecionar opções ▾';
        trigger.setAttribute('aria-label', labelText + ': ' + (selected.length ? selected.map(option => option.label).join(', ') : 'nenhuma seleção'));
        trigger.disabled = select.disabled;
        trigger.setAttribute('aria-invalid', String(!select.validity.valid));
        if (summary) summary.textContent = selected.length ? selected.map(option => option.label).join(', ') : 'Nenhuma opção selecionada.';
        options.querySelectorAll('input').forEach(checkbox => { checkbox.checked = select.options[Number(checkbox.dataset.index)].selected; });
    }
    function renderOptions() {
        options.replaceChildren();
        const term = normalized(search.value);
        Array.from(select.options).forEach((option, optionIndex) => {
            if (!normalized(option.label).includes(term)) return;
            const row = document.createElement('label');
            row.className = 'multi-select-item';
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.dataset.index = optionIndex;
            checkbox.checked = option.selected;
            checkbox.disabled = option.disabled || option.parentElement.disabled;
            checkbox.addEventListener('change', () => {
                option.selected = checkbox.checked;
                select.dispatchEvent(new Event('change', { bubbles: true }));
            });
            row.append(checkbox, document.createTextNode(option.label));
            options.append(row);
        });
        if (!options.children.length) {
            const empty = document.createElement('div');
            empty.className = 'multi-select-empty';
            empty.textContent = 'Nenhuma opção encontrada.';
            options.append(empty);
        }
        if (panel.matches(':popover-open')) positionPopup(panel, trigger, true);
    }
    panel.addEventListener('toggle', event => {
        trigger.setAttribute('aria-expanded', String(event.newState === 'open'));
        if (event.newState === 'open') {
            search.value = '';
            renderOptions();
            positionPopup(panel, trigger, true);
            search.focus();
        }
    });
    search.addEventListener('input', renderOptions);
    search.addEventListener('keydown', event => {
        if (event.key === 'Enter') event.preventDefault();
        if (event.key === 'ArrowDown') { event.preventDefault(); options.querySelector('input:not(:disabled)')?.focus(); }
    });
    options.addEventListener('keydown', event => {
        if (!['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) return;
        const items = focusableElements(options);
        const current = items.indexOf(document.activeElement);
        const next = event.key === 'Home' ? 0 : event.key === 'End' ? items.length - 1 : (current + (event.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
        event.preventDefault();
        items[next]?.focus();
    });
    trigger.addEventListener('keydown', event => {
        if (event.key === 'ArrowDown') { event.preventDefault(); panel.showPopover(); }
    });
    selectAll.addEventListener('click', () => {
        options.querySelectorAll('input:not(:disabled)').forEach(checkbox => { select.options[Number(checkbox.dataset.index)].selected = true; });
        select.dispatchEvent(new Event('change', { bubbles: true }));
    });
    clear.addEventListener('click', () => {
        Array.from(select.options).forEach(option => { if (!option.disabled && !option.parentElement.disabled) option.selected = false; });
        select.dispatchEvent(new Event('change', { bubbles: true }));
    });
    select.addEventListener('change', updateSummary);
    select.addEventListener('invalid', event => { event.preventDefault(); trigger.focus(); panel.showPopover(); });
    select.form?.addEventListener('reset', () => setTimeout(updateSummary, 0));
    wrapper.addEventListener('focusout', () => setTimeout(() => {
        if (!wrapper.contains(document.activeElement) && panel.matches(':popover-open')) panel.hidePopover();
    }, 0));
    window.addEventListener('resize', () => { if (panel.matches(':popover-open')) positionPopup(panel, trigger, true); });
    window.addEventListener('scroll', () => { if (panel.matches(':popover-open')) positionPopup(panel, trigger, true); }, true);
    select.hidden = true;
    updateSummary();
});

document.querySelectorAll('[data-tabs]').forEach((tabs, index) => {
    const buttons = Array.from(tabs.querySelectorAll('[role="tab"]'));
    const panels = Array.from(tabs.querySelectorAll('.tab-panel'));
    function activate(button) {
        buttons.forEach(item => {
            item.setAttribute('aria-selected', String(item === button));
            item.tabIndex = item === button ? 0 : -1;
        });
        panels.forEach(panel => { panel.hidden = panel.id !== button.dataset.tab; });
    }
    buttons.forEach((button, tabIndex) => {
        button.id ||= 'tabs-' + index + '-' + tabIndex;
        button.setAttribute('aria-controls', button.dataset.tab);
        const panel = document.getElementById(button.dataset.tab);
        panel?.setAttribute('role', 'tabpanel');
        panel?.setAttribute('aria-labelledby', button.id);
        if (panel) panel.tabIndex = 0;
        button.addEventListener('click', () => activate(button));
        button.addEventListener('keydown', event => {
            if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
            event.preventDefault();
            const next = event.key === 'Home' ? 0 : event.key === 'End' ? buttons.length - 1 : (tabIndex + (event.key === 'ArrowRight' ? 1 : -1) + buttons.length) % buttons.length;
            activate(buttons[next]);
            buttons[next].focus();
        });
    });
    activate(buttons.find(button => button.getAttribute('aria-selected') === 'true') ?? buttons[0]);
});

document.querySelectorAll('.dialog').forEach(dialog => {
    dialog.addEventListener('click', event => {
        const bounds = dialog.getBoundingClientRect();
        if (!dialog.querySelector('[aria-busy="true"]') && event.target === dialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) dialog.close('cancel');
    });
    dialog.addEventListener('close', () => dialog.querySelectorAll(':popover-open').forEach(popup => popup.hidePopover()));
});

// Delegação permite ações em registros criados sem reinicializar o shell.
document.addEventListener('click', event => {
    const opener = event.target.closest('[data-dialog-open]');
    if (opener) document.getElementById(opener.dataset.dialogOpen)?.showModal();
    const closer = event.target.closest('[data-dialog-close]');
    if (closer) closer.closest('dialog')?.close('cancel');

    const rowButton = event.target.closest('[data-row-menu-button]');
    if (rowButton) {
        const popup = document.getElementById(rowButton.dataset.rowMenuButton);
        if (!popup) return;
        event.preventDefault();
        if (!popup.hasAttribute('popover')) {
            popup.popover = 'auto';
            popup.hidden = false;
            popup.setAttribute('role', 'menu');
            popup.querySelectorAll('button,a').forEach(item => item.setAttribute('role', 'menuitem'));
            popup.addEventListener('toggle', () => rowButton.setAttribute('aria-expanded', String(popup.matches(':popover-open'))));
            popup.addEventListener('keydown', keyEvent => {
                const items = focusableElements(popup);
                const index = items.indexOf(document.activeElement);
                if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(keyEvent.key)) {
                    keyEvent.preventDefault();
                    const next = keyEvent.key === 'Home' ? 0 : keyEvent.key === 'End' ? items.length - 1 : (index + (keyEvent.key === 'ArrowDown' ? 1 : -1) + items.length) % items.length;
                    items[next]?.focus();
                }
            });
            popup.addEventListener('focusout', () => setTimeout(() => {
                if (!popup.contains(document.activeElement) && popup.matches(':popover-open')) popup.hidePopover();
            }, 0));
        }
        rowButton.popoverTargetElement = popup;
        rowButton.setAttribute('aria-haspopup', 'menu');
        popup.togglePopover();
        if (popup.matches(':popover-open')) { positionPopup(popup, rowButton); focusableElements(popup)[0]?.focus(); }
    } else {
        const popup = event.target.closest('.row-menu');
        if (popup?.matches(':popover-open')) popup.hidePopover();
    }

    document.querySelectorAll('.dropdown-demo[open]').forEach(details => { if (!details.contains(event.target) || event.target.closest('button')) details.open = false; });
    const toastButton = event.target.closest('[data-toast-trigger]');
    if (toastButton) {
        const region = document.querySelector('[data-toast-region]');
        if (!region) return;
        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerHTML = '<i aria-hidden="true"></i><div><strong>Exemplo de feedback</strong><span></span></div><button type="button" aria-label="Fechar aviso">×</button>';
        toast.querySelector('span').textContent = toastButton.dataset.toastMessage || 'Demonstração visual. Nenhum dado foi alterado.';
        toast.querySelector('button').addEventListener('click', () => toast.remove());
        region.append(toast);
        setTimeout(() => { if (!toast.contains(document.activeElement)) toast.remove(); }, 5000);
    }
});
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') document.querySelectorAll('.dropdown-demo[open]').forEach(details => { details.open = false; details.querySelector('summary')?.focus(); });
});
window.addEventListener('resize', () => document.querySelectorAll('.row-menu:popover-open').forEach(popup => popup.hidePopover()));
window.addEventListener('scroll', event => document.querySelectorAll('.row-menu:popover-open').forEach(popup => { if (!popup.contains(event.target)) popup.hidePopover(); }), true);

document.querySelectorAll('[data-mock-form]').forEach(form => {
    form.addEventListener('submit', event => {
        event.preventDefault();
        const message = form.querySelector('[data-form-message]');
        if (form.elements.nova_senha && form.elements.nova_senha.value !== form.elements.confirmar_senha.value) {
            message.textContent = 'A confirmação precisa ser igual à nova senha.';
            message.classList.add('is-error');
            message.hidden = false;
            form.elements.confirmar_senha.focus();
            return;
        }
        message.classList.remove('is-error');
        if (form.elements.nova_senha) {
            message.textContent = 'Fluxo validado. Nenhuma senha foi armazenada neste mock.';
            form.reset();
        }
        message.hidden = false;
    });
});
