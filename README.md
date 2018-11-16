# laravel-menu-builder

## How to publish migrations
php artisan vendor:publish --provider="GreenBar\MenuBuilder\MenuBuilderServiceProvider" --tag="migrations"

## How to publish config file
php artisan vendor:publish --provider="GreenBar\MenuBuilder\MenuBuilderServiceProvider" --tag="config"

## How to include private repo into your project

Add git hub link to your composer.json file
```
{
    "require": {
        "vendor/my-private-repo": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@bitbucket.org:vendor/the-private-repo.git"
        }
    ]
}
```

The run composer require
```
composer require greenbar/the-private-repo
```
