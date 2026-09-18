# Desenvolver sobre o NextNav

Leia primeiro `AGENTS.md`. Este documento é a receita do starter independente e deve acompanhar cada projeto derivado.

## Escolher a referência

| Necessidade | Arquivo de referência |
| --- | --- |
| Consulta PHP com filtros, indicadores e tabela | `public/modules/relatorios/index.php` |
| Cadastro com modal, validação no servidor e retorno assíncrono | `public/modules/exemplos/crud.php` |
| Classes visuais e seleção múltipla funcional | `public/modules/exemplos/componentes.php` |
| Composição visual, não operação implementada | `public/modules/exemplos/padroes.php` |
| Estados visuais, não bloqueio de segurança | `public/modules/exemplos/estados.php` |
| Login, recuperação e estados públicos | `public/login.php` e demais páginas públicas de autenticação |

O CRUD é um exemplo funcional de interface, **não um backend de persistência**. Seu POST apenas valida dados e responde JSON; os registros vivem na aba. Antes de ligá-lo ao banco, defina autenticação, autorização por operação, CSRF, validação e transações. Não copie o ramo de mock como se ele salvasse registros.

## Regra para decidir componentes

O catálogo é contrato, não apenas uma vitrine. Antes de escrever HTML ou CSS novo, responda nesta ordem:

1. O componente já existe em `componentes.php` ou nas páginas de referência?
2. A necessidade pode ser atendida compondo classes e tokens existentes?
3. A diferença é realmente exclusiva da página?
4. O novo comportamento será utilizado em mais de um lugar?

Se a resposta da primeira ou segunda pergunta for “sim”, reutilize. Se for uma diferença exclusiva, mantenha a menor customização possível na própria página, sob uma classe raiz específica. Se for reutilizável, ele pertence aos assets globais, ao catálogo e a esta documentação.

Não crie “só mais uma versão” de componente porque o formato visual parece ligeiramente diferente. Primeiro verifique se a diferença pode ser uma composição, conteúdo, estado ou modificador do componente existente. Um componente global novo precisa resolver uma função ainda não coberta; aparência diferente, sozinha, não é justificativa.

### Inventário aprovado

| Necessidade | Contrato existente |
| --- | --- |
| Estrutura da página | `page-heading`, `panel`, `panel-header`, `form-panel` |
| Resumos e indicadores | `metrics`, `metric-card`, `crud-summary`, `demo-card` |
| Ações | `button` com variante `primary`, `secondary`, `ghost` ou `danger` |
| Formulários | `form-grid`, `form-field`, `check-field`, mensagens e estados de validação |
| Seleção múltipla | `select[multiple][data-multi-select]` |
| Listagens | `table-wrap`, tabela nativa, toolbar, rodapé e paginação |
| Situação curta | `status` com variante semântica |
| Retorno ao usuário | `alert`, `form-message`, toast e estado vazio |
| Contexto complementar | tabs, modal e drawer nativos |
| Ações secundárias | `row-menu-button` com três pontos verticais e `row-menu` |
| Espera | loader global ou `NextNavLoader.setLocal()` |
| Navegação e conta | shell de `app/layout/menu.php`; não duplicar por módulo |
| Autenticação visual | shell de `app/layout/auth_header.php` e páginas públicas correspondentes |

Este inventário descreve famílias de componentes. O markup funcional e atualizado deve ser consultado nas páginas de referência, não reconstruído apenas pelo nome da classe.

Shells completos, como o sistema autenticado e a autenticação pública, são demonstrados pelas próprias páginas de referência em vez de serem incorporados dentro do catálogo de componentes.

### Gate para componente global novo

Antes de acrescentar um componente global:

- registrar qual necessidade existente não foi atendida;
- confirmar que ele não duplica componente ou recurso nativo já utilizado;
- usar tokens globais, sem paleta ou escala paralela;
- definir HTML semântico, nome acessível, foco e teclado;
- prever somente os estados que a função exige: normal, desabilitado, carregando, vazio, sucesso ou erro;
- validar tema claro e escuro, largura pequena e conteúdo maior que o exemplo;
- acrescentar a demonstração funcional em `componentes.php`;
- documentar uso, limites e exemplo mínimo neste arquivo;
- reutilizar a implementação global na página que motivou sua criação.

Se esses pontos não forem necessários porque a solução é exclusiva e pequena, ela não deve virar componente global.

## Página mínima copiável

Para `public/modules/meu-modulo/index.php`:

```php
<?php
declare(strict_types=1);
require dirname(__DIR__, 3) . '/app/bootstrap.php';

$pageTitle = 'Meu módulo';
$activePage = 'meu-modulo';

// Ler e validar filtros; processar POST/JSON/redirect ANTES de emitir HTML.
// Só abrir conexão se esta página precisar dela.

require APP_PATH . '/layout/menu.php';
?>
<section class="page-heading">
    <div><span class="eyebrow">Meu módulo</span><h2>Minha consulta</h2><p>Contexto curto da tarefa.</p></div>
</section>
<section class="panel form-panel">
    <h3>Resultado</h3>
    <p>Substitua este conteúdo pela consulta validada.</p>
</section>
<?php require APP_PATH . '/layout/footer.php'; ?>
```

O caminho acima pressupõe exatamente essa profundidade de pastas. Ajuste `dirname()` se criar subpastas adicionais. O shell já contém o elemento principal da página, CSS e JavaScript; não os duplique. Use `APP_URL` em links/ações/arquivos públicos para funcionar também em subdiretório.

## Cadastrar o menu

Edite apenas a árvore em `app/config/navigation.php`. Para uma página na raiz de módulos, acrescente em `root.items`:

```php
['label' => 'Meu módulo', 'description' => 'Minhas consultas', 'href' => 'modules/meu-modulo/index.php', 'active' => 'meu-modulo'],
```

Para um módulo com várias páginas, a entrada usa `target => 'meu-modulo'`; acrescente um nível `meu-modulo` com `title`, `description` e `items` de páginas. Cada folha tem `href` e um `active` único correspondente a `$activePage`. Cada grupo tem um único pai e a árvore não deve conter ciclos.

Use `demo => true` somente em amostras descartáveis. Não marque módulos reais como demo. Não use índices ou a posição dos itens para decidir quais módulos ficam visíveis. A navegação não é autorização.

## Componentes e comportamento

- Ação usa `button type="button"`; envio usa `type="submit"`; navegação usa `a href`.
- Botões: `button` + `button-primary`, `button-secondary`, `button-ghost` ou `button-danger`.
- Layout: `page-heading`, `panel`, `panel-header`, `form-panel`, `form-grid`, `form-field`, `table-wrap`.
- Feedback: `alert alert-success|error|warning|info`, com `role="status"` ou `role="alert"` conforme a urgência. Estado vazio: `empty-demo`.
- Texto e atributos vindos de dados: `htmlspecialchars($valor, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`. Não crie um wrapper só para renomear essa chamada.
- Dados em script: `json_encode` com `JSON_THROW_ON_ERROR | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT`. No navegador, dados comuns usam `textContent`, nunca `innerHTML`.
- CSS/JS global pertence a `public/assets/`. Customização exclusiva fica na página, escopada sob sua classe raiz. Não crie cores literais quando houver token semântico.

### Multiselect com valores reais

```html
<div class="form-field">
    <label for="categorias">Categorias</label>
    <select id="categorias" name="categorias[]" multiple data-multi-select>
        <option value="10" selected>Categoria Alpha</option>
        <option value="20">Categoria Beta</option>
    </select>
    <small data-multi-summary></small>
</div>
```

O campo nativo continua sendo a fonte de verdade; `selected` define seleção inicial e `disabled` restringe edição. O PHP recebe `$_GET['categorias']` ou `$_POST['categorias']` como array de códigos. Valide o tipo, cada código e a permissão no servidor. Rótulos não são identificadores.

`FormData(form)` funciona sem coleta manual de checkbox. Reset restaura as seleções iniciais do HTML. Alteração por código deve atualizar as opções e emitir `change` para sincronizar o componente. Os selects são aprimorados no carregamento da página; não há inicialização automática de campos inseridos posteriormente por AJAX.

Busca ignora acentos; “Selecionar resultados” inclui somente opções filtradas habilitadas; “Limpar tudo” remove todas as opções habilitadas, inclusive fora da busca. Tab navega controles, espaço marca checkbox, setas navegam resultados e Escape fecha o popup. Sem suporte ao popup, permanece o select nativo; o restante do shell exige navegador moderno.

### Modal e loader

- `data-dialog-open="id"` abre um `dialog`; dê nome acessível com `aria-labelledby`.
- `method="dialog"` só fecha: não consulta nem grava nada.
- Envio real usa `method="post"`. Cancelar usa `type="button" data-dialog-close`, nunca um submit implícito.
- A submissão nativa ativa o loader depois de todos os handlers, somente se não houve `preventDefault()`.
- Em fetch, chame `preventDefault()`, controle duplicidade de envio e use `try/catch/finally`. Sempre verifique `response.ok`; resposta HTTP de erro não rejeita fetch automaticamente.
- `NextNavLoader.show()` / `await NextNavLoader.hide()` são pareados; o fechamento espera a duração mínima e devolve o foco antes da próxima ação.
- `NextNavLoader.setLocal(painel, true|false)` mostra um indicador e torna inertes os filhos existentes durante o trabalho. Use um container, não um input/tbody. Não substitua seus filhos antes de retirar o estado; desligue em `finally` e só então renderize o resultado.
- Use timeout/cancelamento na requisição conforme a operação. Timeout de interface não prova que uma gravação no servidor foi cancelada.
- `loader.enabled=false` desativa somente o loader global; operações ainda precisam evitar envio duplicado e encerrar seus estados locais.

No primeiro acesso direto, nenhum loader HTML pode aparecer antes de o servidor começar a responder. Entre páginas, o loader da página atual cobre essa espera. Ele é feedback, não acelera consulta PHP nem substitui paginação/processamento apropriado.

A implementação completa está no CRUD. A opção “Simular falha do servidor” permite conferir erro, preservação de campos e encerramento do loader sem provocar falha real.

### Kit visual de autenticação

- `login.php` demonstra identificador neutro e senha, sem validar credenciais nem criar sessão.
- `recuperar-senha.php` sempre apresenta resposta genérica para não enumerar contas.
- `redefinir-senha.php` demonstra nova senha e os estados `expirado` e `invalido`; não existe token real.
- `acesso-indisponivel.php` demonstra conta `bloqueado` ou `inativo`; a URL não aplica bloqueio real.
- `auth_header.php` e `auth_footer.php` formam o shell público sem menu, topbar de conta ou conteúdo autenticado.
- `auth.js` controla somente tema, visualização de senha e respostas dos formulários mock.

Não use os formulários mock como endpoint real. Ao implementar, mantenha respostas genéricas, validação no servidor, limitação de tentativas, CSRF onde aplicável, sessão regenerada e tokens de recuperação aleatórios, temporários, de uso único e armazenados somente como hash. SSO, login corporativo e e-mail validado são alternativas de produto; não mantenha campos de senha se o provedor escolhido não os utilizar.

### Tabs e ações por linha

Tabs usam `data-tabs`, `role="tablist"`, botões `role="tab" data-tab="id-do-painel"` e painéis `class="tab-panel" id="..."`. IDs devem ser únicos; o shell sincroniza ARIA, foco, setas, Home e End.

Ações contextuais usam o botão global de três pontos **verticais** `row-menu-button`, nunca reticências horizontais. O botão recebe `data-row-menu-button="id"`, `aria-label` específico do registro, `aria-controls="id"`, `aria-haspopup="menu"` e `aria-expanded="false"`. O painel usa `class="row-menu" id="..." hidden` e apresenta as opções verticalmente. O popup nativo sai do fluxo da tabela, sem criar rolagem para caber. Use o markup do CRUD; as ações são delegadas e funcionam nas linhas recém-criadas.

Ações principais visíveis, como “Novo registro” e “Salvar”, continuam como botões normais. O menu de três pontos serve para ações secundárias de contexto, como editar, duplicar e excluir; não esconda nele a única ação principal da página.

## Checklist de alteração

1. Ler alvo e consumidores; preservar alterações existentes.
2. Validar PHP alterado com `php -l caminho.php` e JS global com `node --check public/assets/app.js`.
3. Conferir resposta HTTP e ausência de warnings/detalhes internos; testar entradas inválidas e estado vazio.
4. No navegador, testar claro/escuro, largura pequena, teclado e foco visível.
5. Abrir/reabrir menu na página ativa; voltar até raiz; conferir os exemplos de 3/4/5 níveis.
6. Modal: abrir/cancelar por botão e Escape; salvar válido; validar erro; simular falha; garantir loader encerrado e foco devolvido.
7. Navegação: âncora inclusive `#`, nova aba, download, `data-no-loader`, evento cancelado, `formmethod="dialog"` e retorno pelo navegador.
8. Multiselect: seleção inicial, busca, selecionar resultados, limpar, reset, FormData/GET e uso dentro de modal.
9. Conferir popup da última linha sem recorte/rolagem; tabs e conta por teclado.
10. Ao trocar configuração: logo/cor/tema, loader desligado, armazenamento do navegador indisponível e remoção de demos sem esconder módulos reais.
11. Publicação: executar também o checklist de `INSTALACAO.md`. Registrar o que foi executado e o que permanece sem validação.
12. Conferir que nenhum componente existente foi recriado e que toda novidade global foi adicionada ao catálogo e à documentação.
13. Na autenticação visual, testar login, recuperação, redefinição, link inválido/expirado, bloqueado/inativo, mostrar senha, tema e funcionamento sem JavaScript; não afirmar que houve autenticação real.

## Módulo de verificação

`public/modules/relatorios/index.php` foi construído com este formato: um arquivo, dados mock já disponíveis, GET validado e componentes globais. Não precisou de helper, CSS, JavaScript ou conexão próprios. Ele é a referência mais simples para uma primeira consulta de BI.
