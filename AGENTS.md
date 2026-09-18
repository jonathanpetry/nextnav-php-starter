# AGENTS.md — NextNav

## Objetivo

O NextNav é um starter independente e enxuto para novos sistemas em PHP procedural. Ele deve servir tanto para aplicações internas, como BI e sistemas administrativos, quanto como base visual e estrutural para um futuro SaaS.

O starter entrega shell, navegação, identidade configurável, temas, loader e componentes demonstrativos. Ele não define antecipadamente as regras de negócio do produto que será criado sobre ele.

## Ordem de leitura

Antes de alterar este projeto:

1. Leia este arquivo.
2. Leia `README.md` para instalação, estrutura e publicação.
3. Leia por completo os arquivos diretamente envolvidos na tarefa.
4. Consulte `app/config/app.php` antes de alterar identidade, tema ou comportamento global.
5. Para páginas/componentes, leia `docs/DESENVOLVIMENTO.md`; para servidor, leia `docs/INSTALACAO.md`.
6. Preserve escopo, segurança, minimalismo e compatibilidade com o starter.

O NextNav é independente: seu shell, PDO, configuração e assets são locais. Não acople páginas a arquivos, sessões, conexões ou componentes externos ao projeto. Isso não reduz validação, escape, autorização futura ou o escopo da alteração.

## Princípios

- Fazer a menor alteração segura que resolva o pedido.
- Preferir PHP procedural, direto e legível.
- Não criar funções que apenas renomeiam recursos nativos do PHP.
- Não criar abstrações, dependências ou arquivos para uma reutilização apenas imaginada.
- Reutilizar o shell e os componentes existentes antes de criar CSS ou JavaScript local.
- Não implementar autenticação, tenancy ou regras de produto por suposição.
- Não guardar credenciais, tokens ou segredos no código.

## Governança obrigatória de componentes

Uma LLM ou pessoa desenvolvedora não pode inventar livremente uma segunda linguagem visual. As fontes oficiais deste starter são:

- `public/modules/exemplos/componentes.php`: catálogo dos componentes aprovados e suas variações;
- `public/modules/exemplos/crud.php`: referência de cadastro, modal, feedback e ações por registro;
- `public/modules/relatorios/index.php`: referência enxuta para consultas e páginas de BI;
- `public/modules/exemplos/padroes.php`: referência de composição visual, não de persistência;
- `public/modules/exemplos/estados.php`: referência dos estados visuais;
- `public/assets/app.css` e `public/assets/app.js`: implementação global dos componentes e interações.

Antes de criar interface, seguir esta ordem:

1. Procurar o componente ou composição nas fontes oficiais acima.
2. Reutilizar sua estrutura, classes, tokens e comportamento sem criar uma variante paralela.
3. Se não existir pronto, compor a necessidade usando componentes e tokens já aprovados.
4. Usar CSS ou JavaScript local somente quando a necessidade for exclusiva daquela página e não alterar o contrato global.
5. Se a necessidade for reutilizável, implementar uma única versão global, demonstrá-la em `componentes.php` e documentar seu contrato em `docs/DESENVOLVIMENTO.md` antes de utilizá-la como novo padrão.

É proibido criar uma segunda versão de botão, campo, card, tabela, badge, alerta, modal, drawer, loader, multiselect, tabs, dropdown ou menu de ações quando o componente existente resolver a necessidade. Também é proibido introduzir cor, raio, sombra, espaçamento ou interação visual desconectados dos tokens e padrões globais.

“Manter o padrão” não significa copiar qualquer arquivo que exista no projeto. Somente as fontes listadas nesta seção são referências de interface. Uma página de produto contém regra de negócio e pode ter decisões específicas que não devem virar padrão global.

Biblioteca ou dependência nova exige necessidade real, análise de impacto e autorização explícita. Preferir HTML, CSS, JavaScript e PHP nativos já utilizados pelo starter.

## Estrutura atual

```text
app/
  bootstrap.php                inicialização e validação global
  config/app.php               identidade e comportamento do starter
  config/navigation.php       árvore de módulos, grupos e páginas
  config/database.php          leitura das variáveis do banco
  database/connection.php      conexão PDO sob demanda
  data/mock.php                dados demonstrativos
  layout/menu.php              shell, topbar e menu lateral
  layout/footer.php            fechamento do shell e JavaScript
public/
  assets/app.css               estilos e componentes globais
  assets/app.js                comportamento global
  modules/                     módulos e suas páginas
  index.php                    entrada principal
  perfil.php                   perfil demonstrativo
docs/                          desenvolvimento e instalação de referência
```

Somente `public/` deve ser exposto pelo servidor web.

## O que já existe

- Identidade `NextNav` configurável.
- Cores dos temas claro e escuro derivadas de quatro cores-base.
- Marca textual, logo e favicon locais.
- Menu lateral responsivo com navegação de vários níveis.
- Reabertura do menu no nível da página ativa.
- Topbar fixa com identidade compacta do sistema; rolagem somente na área abaixo dela.
- Perfil e troca de senha apenas demonstrativos.
- Loader global configurável e loader local por JavaScript.
- Catálogo de componentes, padrões, estados e CRUD demonstrativo.
- Dados mock sem persistência; CRUD valida no PHP e mantém registros somente na memória da aba.
- Relatórios é o módulo de consulta copiável: GET, validação e componentes existentes, sem CSS/JS local.
- Multiselect com códigos estáveis, campo nativo, envio por formulário, seleção inicial e reset.
- Popups em camada nativa e controles com teclado; revisão visual em navegador continua parte da aceitação.
- Ações contextuais usam três pontos verticais e opções em dropdown; ações principais permanecem visíveis.
- Configuração de banco por variáveis de ambiente.
- Conexão PDO criada somente quando um módulo solicitar.

## Configuração global

`app/config/app.php` é o ponto de configuração por instalação. Ele controla:

- nome, chave, descrição, ambiente e fuso horário;
- marca, subtítulo, logo, favicon e cores;
- tema inicial e permissão para alternar o tema;
- presença dos módulos demonstrativos;
- aparência e duração mínima do loader global.

Não manter opções sem consumidor implementado. Sessão, login e suporte são decisões futuras, não configurações ativas. O idioma da interface atual é português; não existe internacionalização.

Logo, favicon e imagem do loader devem ficar dentro de `public/` e usar caminho relativo. URLs externas são bloqueadas intencionalmente.

Credenciais do banco são lidas por `getenv()` em `app/config/database.php`. O arquivo `environment.example` documenta apenas os nomes esperados e não é carregado automaticamente.

## Criação de páginas e módulos

- Cada módulo fica em `public/modules/<modulo>/`.
- As páginas de navegação do módulo ficam dentro da própria pasta.
- Toda página carrega `app/bootstrap.php` antes de emitir HTML.
- Defina `$pageTitle` e `$activePage` antes de incluir o menu.
- Inclua `app/layout/menu.php`, escreva o conteúdo e finalize com `app/layout/footer.php`.
- Cadastre a página em `app/config/navigation.php` somente quando ela precisar aparecer na navegação.
- Cada folha usa `href` e `active` único; cada grupo usa `target` com um único pai, sem ciclos.
- `demo => true` marca somente amostras. A opção `show_demo_modules` filtra o menu; não protege rotas nem remove mocks da home/perfil.
- O valor de `$activePage` deve corresponder ao identificador cadastrado no menu.
- CSS ou JavaScript exclusivo só deve ser criado quando os componentes globais não resolverem; manter inline e escopado à página.
- Para consulta copiável, começar por `public/modules/relatorios/index.php`. Para fluxo assíncrono de cadastro, usar `public/modules/exemplos/crud.php` e substituir conscientemente seu armazenamento mock.
- Catálogo e padrões visuais não devem prometer operações inexistentes; identificar amostras como demonstrações.
- Não aprovar uma página nova que recrie componente existente, use cor literal evitável ou introduza comportamento global apenas dentro da página.

## Loader

- O loader global é destinado à primeira renderização, navegação e submissões que realmente mudam de página.
- `method="dialog"` apenas fecha o modal e não envia dados; por isso não ativa loader automaticamente.
- Modal que envia dados ao servidor usa `<form method="post">`; sua submissão ativa o loader global como qualquer formulário real.
- Em formulário POST dentro de modal, o botão Cancelar deve ser `type="button" data-dialog-close` para não enviar o formulário.
- Operação assíncrona iniciada em modal deve controlar `NextNavLoader.show()` e `await NextNavLoader.hide()` em `finally`; só depois devolver foco/fechar o formulário.
- Links externos, downloads, âncoras e novas abas não podem ativar o loader global.
- Use `data-no-loader` quando um link ou formulário não produzir navegação.
- Operações assíncronas globais usam `NextNavLoader.show()` e `NextNavLoader.hide()`.
- Atualizações de um único painel usam `NextNavLoader.setLocal(elemento, estado)`.
- Não bloquear toda a página quando somente um componente estiver carregando.
- Em operações reais, remover o loader em `finally` para evitar bloqueio infinito após erro.
- Cada `show()` exige seu `hide()`; o global conta trabalhos concorrentes e usa `dialog` nativo para aparecer acima de modais.
- O loader local deve envolver um container. Retirar o estado antes de substituir seus filhos.
- Não implementar loader por bloqueio universal de cliques/submits: respeitar evento cancelado, `formmethod`, `formtarget`, protocolo e destino efetivos.
- Tema deve funcionar mesmo se `localStorage` estiver indisponível.

## Banco e segurança

- Não abrir conexão em todas as páginas automaticamente.
- Quando necessário, carregar `app/database/connection.php` depois do bootstrap.
- Usar consultas preparadas e parâmetros para todos os valores dinâmicos.
- Não devolver SQL, credenciais, caminhos internos ou detalhes de exceções ao navegador.
- Operações de escrita futuras devem ter autenticação, autorização, validação no servidor e proteção CSRF.
- O menu oculto não é autorização. Toda página protegida deve validar acesso no servidor.

## Autenticação e autorização planejadas

Autenticação, usuários reais, recuperação de senha e autorização ainda não estão implementados. O perfil atual é apenas visual.

O modelo planejado é granular: uma página pode ser liberada para vários usuários e um usuário pode receber várias páginas. A existência da permissão concede acesso; a ausência bloqueia.

Catálogo inicial proposto:

```text
ADM_USUARIO
ADM_MODULO
ADM_PAGINA
ADM_USUARIO_PAGINA
```

Nomenclatura preferida para futuras tabelas administrativas:

- `ADM_*` para tabelas administrativas.
- `CD_*` para códigos e chaves.
- `NM_*` para nomes.
- `DS_*` para descrições e conteúdos textuais.
- `TP_*` para tipos.
- `FL_*` para indicadores.
- `DT_*` para datas e horários.

Exemplo conceitual, ainda não implementado:

```text
ADM_USUARIO
- CD_USUARIO
- NM_LOGIN
- NM_USUARIO
- DS_SENHA
- TP_USUARIO
- FL_ATIVO
```

`DS_SENHA` é apenas o nome proposto para um hash produzido por `password_hash()` e verificado por `password_verify()`, nunca senha em texto puro. A estrutura não foi criada.

`TP_USUARIO = 1` está reservado conceitualmente ao administrador global da plataforma. Os demais tipos ainda não foram fechados e não devem ser inventados silenciosamente.

Ainda não foi decidido se a autenticação usará exclusivamente `NM_LOGIN`, e-mail validado ou ambos. Sistemas internos podem preferir `NM_LOGIN`; um SaaS pode exigir e-mail validado. Essa decisão deve ser tomada antes da implementação do login.

Quando forem criados mocks de autenticação e permissão, usar nomes próximos das futuras colunas do banco. Não renomear mocks existentes fora do escopo apenas para antecipar essa mudança.

## Expansão opcional para SaaS

Tenant e empresa não fazem parte obrigatória do núcleo visual. Quando o produto for SaaS, a expansão conceitual prevista é:

```text
ADM_TENANT
ADM_EMPRESA
ADM_TENANT_USUARIO
ADM_USUARIO_EMPRESA
ADM_TENANT_USUARIO_PAGINA
```

- Um tenant pode possuir várias empresas.
- Um usuário pode participar de vários tenants.
- O papel de administrador do tenant pertence ao vínculo `ADM_TENANT_USUARIO`, não ao tipo global em `ADM_USUARIO`.
- O mesmo usuário pode ser administrador em um tenant e usuário comum em outro.
- Permissões e consultas de dados devem respeitar o tenant ativo.

Para um BI ou sistema de uma única empresa, não introduzir tenancy sem necessidade real.

## Perfis de acesso futuros

Perfis podem ser acrescentados posteriormente com tabelas como `ADM_PERFIL`, `ADM_PERFIL_PAGINA` e `ADM_USUARIO_PERFIL`. Na primeira versão, preferir permissões diretas por usuário e página.

Não implementar regras de negação, precedência ou exceção entre perfil e usuário antes de existir uma necessidade documentada.

## Publicação

- O `DocumentRoot` deve apontar para `public/`.
- Configurar as variáveis do banco no ambiente do servidor.
- Habilitar HTTPS em produção.
- Se o produto criar armazenamento local, mantê-lo fora de `public/` e conceder escrita somente às subpastas necessárias.
- Desativar a exibição e remover/proteger as rotas demonstrativas na cópia do produto; a configuração do menu sozinha não controla acesso.
- Nunca publicar credenciais ou arquivos internos dentro de `public/`.

## Validação

- Executar lint em todo PHP alterado.
- Executar `node --check public/assets/app.js` quando o JavaScript mudar.
- Testar as rotas afetadas pelo servidor web e seus casos inválidos.
- Executar o checklist de `docs/DESENVOLVIMENTO.md`, incluindo loader/cancelamento, foco, multiselect e retorno do navegador.
- Quando houver componente global novo, conferir sua demonstração no catálogo, contrato documentado, claro/escuro, teclado, celular, carregamento, vazio, sucesso e erro aplicáveis.
- Uma nova abstração deve ter uso concreto. Os callbacks de eventos/erros e APIs compartilhadas de loader são fronteiras reais, não wrappers para nativas.
- Para alterações visuais ou interativas, registrar quando o navegador não foi testado.
- Não afirmar que banco, autenticação, VPS ou fluxo real foi validado quando isso não ocorreu.
