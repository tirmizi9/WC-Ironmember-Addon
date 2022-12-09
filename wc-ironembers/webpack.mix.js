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

mix.js('assets-src/js/admin-fire-pit', 'assets/js')
    .js('assets-src/js/admin-fire-pit-accessory', 'assets/js')
    .js('assets-src/js/admin-fire-pit-panel-text', 'assets/js')
    .sass('assets-src/scss/admin.scss', 'assets/css')
    .sass('assets-src/scss/frontend.scss', 'assets/css')
    .options({
        processCssUrls: false
    });
