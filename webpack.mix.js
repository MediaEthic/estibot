const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.copyDirectory('resources/assets', 'public/assets');

mix.js('resources/js/app.js', 'dist')
    .sass('resources/sass/app.scss', 'dist');

mix.webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve('resources/sass')
            }
        }
    });

