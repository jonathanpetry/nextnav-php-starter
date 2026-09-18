<?php

declare(strict_types=1);

/*
 * Configuração global do NextNav.
 *
 * Edite este arquivo para iniciar um novo sistema.
 * Não coloque senhas, tokens, chaves de API ou credenciais aqui.
 */
return [
    'app' => [
        'key' => 'nextnav',
        'name' => 'NextNav',
        'description' => 'Base enxuta para novos sistemas.',
        'environment' => 'development',
        'timezone' => 'America/Sao_Paulo',
    ],

    'brand' => [
        'mark' => 'N',
        'subtitle' => 'Navegação simplificada',
        'logo' => '',
        'favicon' => '',
        'colors' => [
            'primary' => '#262A24',
            'secondary' => '#D6F05A',
            'dark_primary' => '#F4F3ED',
            'dark_secondary' => '#5F6D00',
        ],
    ],

    'interface' => [
        'default_theme' => 'light',
        'allow_theme_toggle' => true,
        'show_demo_modules' => true,
    ],

    'loader' => [
        'enabled' => true,
        'show_brand' => true,
        'custom_image' => '',
        'text' => 'Carregando...',
        'minimum_duration' => 2000,
    ],
];
