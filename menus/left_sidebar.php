<?php

$leftSidebar = [
    [
        'text'        => 'Home',
        'url'         => 'home',
        'icon'        => 'fas fa-fw fa-home',
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
        'text'        => ' Magazzini',
        'url'         => 'warehouses',
        'icon'        => 'fas fa-warehouse',
    ],
    [
        'header' => 'Configurazioni',
        'classes'  => 'text-bold text-center',
        // 'can'  => ['config-read'],
    ],
    [
        'text'        => ' Sessione Inventario',
        'url'         => 'inventary_session',
        'icon'        => 'fas fa-dolly-flatbed',
    ],
    [
        'text'        => ' Tagliandini Inventario',
        'url'         => 'inventory_tickets',
        'icon'        => 'fas fa-barcode',
    ],

];
