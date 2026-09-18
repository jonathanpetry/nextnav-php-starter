<?php

declare(strict_types=1);

// Uma página usa href + active; um grupo usa target. Marque apenas amostras como demo.
return [
    'root' => [
        'title' => APP_NAME,
        'description' => $appConfig['brand']['subtitle'],
        'items' => [
            ['label' => 'Visão geral', 'description' => 'Resumo do negócio', 'href' => 'index.php', 'active' => 'dashboard'],
            ['label' => 'Clientes', 'description' => 'Contas e assinaturas', 'demo' => true, 'href' => 'modules/clientes/index.php', 'active' => 'clientes'],
            ['label' => 'Relatórios', 'description' => 'Módulo-modelo com filtros', 'href' => 'modules/relatorios/index.php', 'active' => 'relatorios', 'demo' => true],
            ['label' => 'Exemplos', 'description' => 'Componentes e padrões', 'target' => 'exemplos', 'demo' => true],
            ['label' => 'Financeiro', 'description' => 'Exemplo com 3 níveis', 'target' => 'financeiro', 'demo' => true],
            ['label' => 'Cadastros', 'description' => 'Exemplo com 4 níveis', 'target' => 'cadastros', 'demo' => true],
            ['label' => 'Gestão', 'description' => 'Exemplo com 5 níveis', 'target' => 'gestao', 'demo' => true],
        ],
    ],
    'exemplos' => [
        'title' => 'Exemplos', 'description' => 'Referências para novas telas',
        'items' => [
            ['label' => 'Componentes', 'description' => 'Botões, campos e feedback', 'href' => 'modules/exemplos/componentes.php', 'active' => 'exemplos-componentes'],
            ['label' => 'Padrões de telas', 'description' => 'Lista, formulário e detalhe', 'href' => 'modules/exemplos/padroes.php', 'active' => 'exemplos-padroes'],
            ['label' => 'Estados do sistema', 'description' => 'Vazio, erro e bloqueios', 'href' => 'modules/exemplos/estados.php', 'active' => 'exemplos-estados'],
            ['label' => 'CRUD demonstrativo', 'description' => 'Tabela e ações por registro', 'href' => 'modules/exemplos/crud.php', 'active' => 'exemplos-crud'],
        ],
    ],
    'financeiro' => [
        'title' => 'Financeiro', 'description' => 'Nível 2',
        'items' => [['label' => 'Contas', 'description' => 'Acessar o nível 3', 'target' => 'financeiro-contas']],
    ],
    'financeiro-contas' => [
        'title' => 'Contas', 'description' => 'Nível 3',
        'items' => [
            ['label' => 'Contas a receber', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=contas-receber', 'active' => 'demo-contas-receber'],
            ['label' => 'Contas a pagar', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=contas-pagar', 'active' => 'demo-contas-pagar'],
        ],
    ],
    'cadastros' => [
        'title' => 'Cadastros', 'description' => 'Nível 2',
        'items' => [['label' => 'Produtos', 'description' => 'Acessar o nível 3', 'target' => 'cadastros-produtos']],
    ],
    'cadastros-produtos' => [
        'title' => 'Produtos', 'description' => 'Nível 3',
        'items' => [['label' => 'Catálogo', 'description' => 'Acessar o nível 4', 'target' => 'cadastros-catalogo']],
    ],
    'cadastros-catalogo' => [
        'title' => 'Catálogo', 'description' => 'Nível 4',
        'items' => [
            ['label' => 'Coleções', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=colecoes', 'active' => 'demo-colecoes'],
            ['label' => 'Categorias', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=categorias', 'active' => 'demo-categorias'],
        ],
    ],
    'gestao' => [
        'title' => 'Gestão', 'description' => 'Nível 2',
        'items' => [['label' => 'Comercial', 'description' => 'Acessar o nível 3', 'target' => 'gestao-comercial']],
    ],
    'gestao-comercial' => [
        'title' => 'Comercial', 'description' => 'Nível 3',
        'items' => [['label' => 'Metas', 'description' => 'Acessar o nível 4', 'target' => 'gestao-metas']],
    ],
    'gestao-metas' => [
        'title' => 'Metas', 'description' => 'Nível 4',
        'items' => [['label' => 'Equipes', 'description' => 'Acessar o nível 5', 'target' => 'gestao-equipes']],
    ],
    'gestao-equipes' => [
        'title' => 'Equipes', 'description' => 'Nível 5',
        'items' => [
            ['label' => 'Vendedores', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=vendedores', 'active' => 'demo-vendedores'],
            ['label' => 'Supervisores', 'description' => 'Página demonstrativa', 'href' => 'modules/exemplos/navegacao.php?pagina=supervisores', 'active' => 'demo-supervisores'],
        ],
    ],
];
