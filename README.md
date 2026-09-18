# NextNav — starter PHP independente

Base enxuta para iniciar BI interno, intranet ou um produto que futuramente seja SaaS. PHP procedural, HTML, CSS e JavaScript nativo, sem framework, Composer ou etapa de build.

Entrega identidade configurável, shell responsivo, navegação em níveis, temas, loaders, componentes e um kit visual de autenticação. **Não entrega autenticação real, permissões, tenancy ou persistência.** Os exemplos usam dados fictícios; não cadastre informações reais.

## Começar

1. Copie esta pasta inteira para o novo projeto.
2. Configure nome, chave única, marca, cores, tema e loader em `app/config/app.php`.
3. Execute na raiz:

```bash
php -S 127.0.0.1:8080 -t public
```

4. Acesse `http://127.0.0.1:8080`.
5. Abra Exemplos → Componentes e CRUD demonstrativo; consulte também Relatórios.
6. Acesse `http://127.0.0.1:8080/login.php` para conferir o kit visual de autenticação.

Requisitos: PHP 8.3+ mantido/atualizado, navegador moderno com `dialog`, `inert`, Popover API e CSS `color-mix()`. A conferência local usa PHP 8.3.12; a instalação de referência usa PHP 8.4. PDO e seu driver são necessários somente ao conectar um banco. O PHP embutido não é servidor de produção.

## O que ler

- [AGENTS.md](AGENTS.md): objetivo, limites e decisões para desenvolvedores e agentes.
- [Desenvolvimento](docs/DESENVOLVIMENTO.md): página copiável, cadastro no menu, componentes, formulários e checklist.
- [Instalação de referência](docs/INSTALACAO.md): VPS Debian/Nginx/PHP-FPM, HTTPS, ambiente e logs.

Para desenvolver ou contribuir, siga a governança de componentes do `AGENTS.md`. Novas páginas devem reutilizar o catálogo; um componente global novo precisa ter necessidade comprovada, implementação única, demonstração no catálogo e contrato no guia de desenvolvimento.

## Estrutura

```text
app/
  bootstrap.php             inicialização, validação e fronteira de erros
  config/app.php            identidade e comportamentos implementados
  config/navigation.php     módulos, grupos e páginas do menu
  config/database.php       variáveis de ambiente do banco
  database/connection.php   PDO sob demanda
  data/mock.php             dados fictícios
  layout/auth_header.php    abertura do shell público
  layout/auth_footer.php    fechamento do shell público
  layout/menu.php           abertura do shell, navegação e topbar
  layout/footer.php         fechamento do shell e JavaScript global
  layout/theme.php          tema e identidade compartilhados
public/
  assets/app.css            tokens e componentes visuais
  assets/app.js             comportamento dos componentes
  assets/auth.js            comportamento visual da autenticação
  modules/clientes/        listagem demonstrativa
  modules/exemplos/        catálogo e CRUD-modelo
  modules/relatorios/      módulo pequeno de consulta com filtros GET
  index.php                 início
  login.php                 login demonstrativo
  recuperar-senha.php       recuperação demonstrativa
  redefinir-senha.php       redefinição e estados de link
  acesso-indisponivel.php   estados bloqueado e inativo
  perfil.php                perfil e senha apenas demonstrativos
docs/                       desenvolvimento e instalação
environment.example         nomes das variáveis; não é carregado
```

Somente `public/` deve ser acessível pela web. O projeto não depende de arquivos, conexões ou sessões externas.

## Configurações que funcionam hoje

- `app`: chave usada na preferência de tema, nome, descrição, ambiente e fuso.
- `brand`: marca, subtítulo, logo, favicon e quatro cores-base.
- `interface`: tema inicial, troca de tema e exibição das amostras no menu.
- `loader`: ativação global, marca/imagem/texto e duração mínima (0–1000 ms).

Logo, favicon e imagem do loader são caminhos relativos dentro de `public/`, por exemplo `assets/images/logo.svg`. URLs externas são bloqueadas. Não há configurações de login ou sessão com efeito aparente mas sem implementação.

A cor do texto em botões sólidos é escolhida por contraste de luminância. Ao mudar a marca, confira também links, fundos suaves, foco e estados nos dois temas: o cálculo não certifica automaticamente a acessibilidade de toda paleta.

`show_demo_modules=false` retira do menu os itens marcados `demo`, preservando novos módulos reais. **Não bloqueia suas URLs nem remove os dados mock da página inicial/perfil.** Ao entregar um produto, remova as demonstrações da cópia distribuída ou proteja suas rotas na autorização do produto.

## Banco, ambiente e dados

`environment.example` lista `APP_ENV`, `DB_DSN`, `DB_USERNAME` e `DB_PASSWORD`. As variáveis devem existir no processo PHP; não há carregamento automático de `.env`. Credenciais nunca entram em `app/config/app.php`.

Após o bootstrap, uma página que realmente precisa de banco pode executar:

```php
$pdo = require APP_PATH . '/database/connection.php';
```

Utilize consultas preparadas e transações quando houver escritas dependentes. Abrir a conexão não implementa autorização, CSRF nem regras de negócio.

O CRUD valida requisições no PHP e mantém os resultados somente na memória da aba. Recarregar restaura os dados iniciais. Relatórios demonstra filtros GET validados, indicadores, saída escapada e estado vazio sem JavaScript específico.

## Autenticação visual

O starter inclui telas responsivas para entrar, recuperar e redefinir senha, além de link expirado/inválido e conta bloqueada/inativa. Elas reutilizam identidade, cores, tema, campos, botões e alertas. Os formulários apenas demonstram o fluxo: não consultam usuário, não enviam e-mail, não validam token, não criam sessão e não armazenam senha.

Um produto real deve escolher login, e-mail ou SSO e então implementar autenticação, autorização no servidor, política de sessão, limitação de tentativas, CSRF e recuperação segura. As regras obrigatórias estão em [AGENTS.md](AGENTS.md) e o contrato visual em [Desenvolvimento](docs/DESENVOLVIMENTO.md).

## Loader

Navegação interna na mesma aba e envio real de formulário usam o loader automaticamente. Cancelar um modal, `method="dialog"`, eventos cancelados, âncoras, downloads, destinos externos e outras abas não devem ativá-lo. `data-no-loader` é um atributo booleano: sua presença já desliga o automatismo.

Em operações assíncronas:

```javascript
NextNavLoader.show();
try {
    // await fetch(...); verificar response.ok e tratar o resultado.
} finally {
    await NextNavLoader.hide();
}
```

O `await hide()` aguarda o fechamento efetivo antes de devolver foco ou fechar o formulário modal. Cada `show()` deve ter um `hide()`. Para um painel, use `NextNavLoader.setLocal(painel, true)` e `setLocal(painel, false)` em `finally`; não use ambos no mesmo trabalho.

## Antes de levar dados reais

Defina autenticação, autorização no servidor, CSRF, política de sessão, tratamento de erros esperados e persistência. Isolamento de tenant/empresa só entra quando o produto precisar. Ocultar menu não protege dados.

Execute o checklist em [Desenvolvimento](docs/DESENVOLVIMENTO.md). Lint e respostas HTTP não substituem teste em navegador, leitores de tela, PHP-FPM, banco ou VPS.

## Contribuir

Leia [CONTRIBUTING.md](CONTRIBUTING.md) e o contrato técnico em [AGENTS.md](AGENTS.md). Correções pequenas e objetivas são preferíveis a novas abstrações ou dependências. Nenhuma contribuição deve incluir credenciais, dados pessoais ou cópias de componentes já existentes.

## Licença

Distribuído sob a [licença MIT](LICENSE). Você pode usar, modificar e distribuir o NextNav preservando o aviso de copyright e a licença.
