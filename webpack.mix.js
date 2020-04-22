const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

mix.js('resources/js/app.js', 'public/dist')
    .sass('resources/sass/app.scss', 'public/dist')
    .options({
        processCssUrls: false,
        postCss: [
            tailwindcss('./tailwind.config.js')
        ],
    });

mix.webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve('resources/sass')
            }
        }
    });

