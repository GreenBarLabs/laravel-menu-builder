# laravel-menu-builder

##**Warning!**
This package is still in active development and is not ready for production deployment. However If you would like to use it in your project you may do so. Just know, we will probably not respond to any issues in the issue tracker.

##Installation
Install the package via composer:
```
composer require greenbar/laravel-menu-builder
```

In Laravel 5.5 the service provider will automatically get registered. In older versions of the framework just add the service provider in config/app.php file:
```php
'providers' => [
    // ...
    GreenBar\MenuBuilder\MenuBuilderServiceProvider::class,
];
```

Publish the migrations:
```
php artisan vendor:publish --provider="GreenBar\MenuBuilder\MenuBuilderServiceProvider" --tag="migrations"
```

Publish the config file:
```
php artisan vendor:publish --provider="GreenBar\MenuBuilder\MenuBuilderServiceProvider" --tag="config"
```

When published, the config/permission.php config file contains:
```php
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
```

## Usage
There are three commands to get you started:
```
/**
 * Creates a menu with the specified position. You can also specify the number of first 
 * level menu items to stub out.
 */
php artisan menus:create-menu {position} {--menu-items=0}

/**
 * TODO: Creates a nested menu item at the desired level and then re-indexes the whole tree
 */
php artisan menus:create-menu-item {menu_id} {parent_id?}

/**
 * Re-indexes the menu's nested set tree
 */
php artisan menus:fix-tree {menu_id}
```

Once you have a menu, you can render the nav ul in the blade template by:
```php
@render_menu('main') // you render the menu by it's unique position name
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
