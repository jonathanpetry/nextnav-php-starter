# Contribuir com o NextNav

Obrigado por ajudar a melhorar o NextNav. O objetivo é manter um starter PHP procedural pequeno, previsível e fácil de adaptar.

## Antes de alterar

1. Leia `AGENTS.md` e apenas a documentação relacionada à mudança.
2. Confira se o componente ou comportamento já existe no catálogo.
3. Abra uma issue para mudanças de contrato, dependências, autenticação, arquitetura ou componentes globais relevantes.
4. Não inclua credenciais, tokens, dados pessoais, logs ou arquivos de ambiente.

## Desenvolvimento

- Faça a menor alteração segura que resolva o problema.
- Prefira PHP, HTML, CSS e JavaScript nativos já usados pelo projeto.
- Preserve acessibilidade, tema claro/escuro e responsividade.
- Não use o menu como autorização e não apresente os mocks como persistência real.
- Um componente global novo deve seguir o gate de `docs/DESENVOLVIMENTO.md`.

## Validação

Antes de abrir um pull request:

```bash
php -l caminho/do/arquivo.php
node --check public/assets/app.js
```

Execute também o checklist aplicável de `docs/DESENVOLVIMENTO.md`. Descreva no pull request o que foi validado e o que não pôde ser testado, especialmente navegador, leitor de tela, banco e servidor de produção.

## Pull request

- Explique o problema e a solução de forma objetiva.
- Evite misturar limpeza ou refatoração sem relação com o objetivo.
- Inclua imagem quando a mudança for visual.
- Não adicione dependência sem justificar necessidade, impacto e manutenção.
