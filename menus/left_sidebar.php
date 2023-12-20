<?php

$leftSidebar = [
    [
        'text'        => 'Home',
        'url'         => 'home',
        'icon'        => 'fas fa-fw fa-home',
    ],

    [
        'header' => 'Inventario Semplificato',
        'classes'  => 'text-bold text-center',
        // 'can'  => ['config-read'],
    ],
    [
        'text'        => ' Misurazioni Inventario Simple',
        'url'         => 'inventory/measurements_simple',
        'icon'        => 'fas fa-tasks',
    ],
    [
        'text'        => ' Gestione Inventario Simple',
        'url'         => 'inventory/stats_simple',
        'icon'        => 'fas fa-chart-bar',
    ],

    [
        'header' => 'Inventario Avanzato',
        'classes'  => 'text-bold text-center',
        // 'can'  => ['config-read'],
    ],
    [
        'text'        => ' Misurazioni Inventario Avanz.',
        'url'         => 'inventory/measurements',
        'icon'        => 'fas fa-tasks',
    ],
    [
        'text'        => ' Gestione Inventario Avanz.',
        // 'url'         => 'inventory/stats',
        'url'         => '#',
        'icon'        => 'fas fa-chart-bar',
    ],
    [
        'text'        => ' Tagliandini Inventario',
        'url'         => 'inventory/tickets',
        'icon'        => 'fas fa-barcode',
    ],

    [
        'header' => 'Anagrafiche',
        'classes'  => 'text-bold text-center',
    ],
    [
        'text'        => ' Prodotti',
        'url'         => 'products',
        'icon'        => 'fas fa-boxes',
    ],
    [
        'text'        => ' Magazzini & Ubicazioni',
        'url'         => 'warehouses',
        'icon'        => 'fas fa-warehouse',
    ],
    [
        'header' => 'Configurazioni',
        'classes'  => 'text-bold text-center',
        // 'can'  => ['config-read'],
    ],
    [
        'text'        => ' Sessione Inventario Avanzato',
        'url'         => 'config/inventory/sesions',
        'icon'        => 'fas fa-dolly-flatbed',
    ],

];
