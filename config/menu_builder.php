<?php

return [
    'models' => [
        /*
         *  Specify the Model used when creating Menus
         */
        'menu' => GreenBar\MenuBuilder\Models\Menu::class,
        
        /*
         *  Specify the Model used when creating MenuItems
         */
        'menu_item' => GreenBar\MenuBuilder\Models\MenuItem::class,
    ],

    'table_names' => [
        'menus' => 'menus',
        'menu_items' => 'menu_items',
    ],

    'column_names' => [],
];
