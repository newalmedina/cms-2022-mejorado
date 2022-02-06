const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */


let paths = {
    'vendor': 'vendor/',
    'node': 'node_modules/',
    'resources': 'resources/assets/front/',
    'public': 'public/assets/',
    'public_front': 'public/assets/front/vendor/',
    'blade': 'resources/views/',
    'root': ''
};

mix.setPublicPath(path.normalize(paths.public + 'front'));

// Recursos de frontend
mix.js(paths.resources + '/jss/app.js', paths.public + 'front/js')
    .sass(paths.resources + '/sass/app.scss', paths.public + 'front/css')
    .options({
        processCssUrls: false
    })
	.copy(paths.resources + 'vendor', paths.public + 'front/vendor')
	.copy(paths.resources + 'img', paths.public + 'front/img')
	.copy(paths.resources + 'css', paths.public + 'front/css')
	.copy(paths.resources + 'fonts', paths.public + 'front/fonts')
	.copy(paths.resources + 'js', paths.public + 'front/js')
    .webpackConfig(require('./webpack.config.front'))
    .sourceMaps();
// Vue
mix.copy(paths.node + 'vue/dist/', paths.public_front + 'vue/');

// Datatables
mix.copy(paths.node + 'datatables.net/js', paths.public_front + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-bs4/js', paths.public_front + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-bs4/css', paths.public_front + 'datatables.net/css/');
mix.copy(paths.node + 'datatables.net-responsive/js', paths.public_front + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-responsive-bs4/css', paths.public_front + 'datatables.net/css/');
mix.copy(paths.node + 'datatables.net-responsive-bs4/js', paths.public_front + 'datatables.net/js/');

if (mix.inProduction()) {
    mix.version();
}
