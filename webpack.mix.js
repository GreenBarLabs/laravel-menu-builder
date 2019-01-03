let mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        alias: {
            'mb_api': path.resolve(__dirname, 'resources/Nova/js/api.js'),
            'mb_helpers': path.resolve(__dirname, 'resources/Nova/js/helpers.js'),
        },
    },
});

mix.js('resources/Nova/js/tool.js', 'dist/js')
   .sass('resources/Nova/sass/tool.scss', 'dist/css');
