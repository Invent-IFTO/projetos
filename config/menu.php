<?php

return [
    // Navbar items:
    [
        'type' => 'navbar-search',
        'text' => 'search',
        'topnav_right' => true,
    ],
    [
        'type' => 'fullscreen-widget',
        'topnav_right' => true,
    ],

    // Sidebar items:
    [
        'type' => 'sidebar-menu-search',
        'text' => 'search',
    ],
    [

        'text' => 'Desenvolvimento',
        'icon' => 'fas fa-fw fa-tools',
        'can' => 'Desenvolvedor: libs',
        'submenu' => [
            [
                'text' => 'W3Schools',
                'url' => 'https://www.w3schools.com/',
                'icon' => 'fa-brands fa-w3c',
                'target' => '_blank',
            ],
            [
                'text' => 'JavaScript',
                'url' => 'https://developer.mozilla.org/pt-BR/docs/Web/JavaScript',
                'icon' => 'fa-brands fa-js',
                'target' => '_blank',
            ],
            [
                'text' => 'PHP',
                'url' => 'https://www.php.net/manual/pt_BR/',
                'icon' => 'fa-brands fa-php',
                'target' => '_blank',
            ],
            [
                'text' => 'Laravel',
                'url' => 'https://laravel.com/docs/12.x/blade#main-content',
                'icon' => 'fa-brands fa-laravel',
                'target' => '_blank',
            ],
            [
                'text' => 'Bootstrap 4',
                'url' => 'https://getbootstrap.com/docs/4.6/layout/overview/',
                'icon' => 'fa-brands fa-bootstrap',
                'target' => '_blank',
            ],
            [
                'text' => 'AdminLTE',
                'icon' => 'fa-solid fa-layer-group',
                'submenu' => [
                    [
                        'text' => 'Documentação',
                        'target' => '_blank',
                        'url' => 'https://adminlte.io/docs/3.2/layout.html',
                        'icon' => 'fa-solid fa-book-open',
                    ],
                    [
                        'text' => 'Preview',
                        'target' => '_blank',
                        'url' => 'http://localhost/iservices/vendor/almasaeed2010/adminlte/',
                        'icon' => 'fa-solid fa-chart-line',
                    ],
                    [
                        'text' => 'Laravel+AdminLTE',
                        'target' => '_blank',
                        'url' => 'https://github.com/jeroennoten/Laravel-AdminLTE/wiki',
                        'icon' => 'fa-solid fa-laptop-code',
                    ],

                ],


            ],
            [
                'text' => 'Icons FontAwesome',
                'url' => 'https://fontawesome.com/search?ic=free-collection&q=',
                'icon' => 'fa-brands fa-font-awesome',
                'target' => '_blank',
            ]
        ],
    ],
    [
        'text' => 'Administração',
        'icon' => 'fas fa-fw fa-cogs',
        'active' => ['administracao*'],
        'submenu' => [
            [
                'text' => 'Grupos',
                'route' => 'administracao.grupos.listar',
                'icon' => 'fa-solid fa-users-gear',
                'active' => ['administracao/grupos*'],
                'can' => 'Grupos: listar',
            ],

        ],
    ],
    [
        'text' => 'Projetos',
        'icon' => 'fas fa-fw fa-clipboard-list',
        'active' => ['projetos*'],
        'submenu' => [
            [
                'text' => 'Gestão',
                'route' => 'projetos.listar',
                'icon' => 'fa-solid fa-folder-open',
                'active' => ['projetos/gestao/*'],
                'can' => 'Projetos: listar',
            ],
            [
                'text' => 'Atividades',
                'route' => ['projetos.selecione', ['action' => 'projetos.atividades.index']],
                'icon' => 'fa-solid fa-list-check',
                'active' => ['projetos/*/atividades*'],
                'can' => 'Projetos: atividades',
            ],

        ]

    ],
    [
        'text' => 'Perfil',
        'icon' => 'fas fa-fw fa-user',
        'active' => ['perfil*'],
        'route' => 'perfil',
    ]
];