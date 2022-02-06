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
    'resources': 'resources/assets/admin/',
    'public': 'public/assets/',
    'public_admin': 'public/assets/admin/vendor/',
    'blade': 'resources/views/',
    'root': ''
};

mix.setPublicPath(path.normalize(paths.public + 'admin'));

mix.js(paths.resources + '/jss/app.js', paths.public + 'admin/js')
    .sass(paths.resources + '/sass/app.scss', paths.public + 'admin/css')
    .options({
        processCssUrls: false
    })
    .sass(paths.resources + 'sass/adminlte.scss', paths.public + 'admin/css')
    .options({
        processCssUrls: false
    })
    .copy(paths.resources + '/img', paths.public + 'admin/img')
    .webpackConfig(require('./webpack.config.admin'))
    .sourceMaps();


// adminlte
mix.copy(paths.node + 'admin-lte/dist', paths.public_admin + 'adminlte');


// Plugins adminlte
mix.copy(paths.node + 'admin-lte/plugins/', paths.public_admin );

// Vue
mix.copy(paths.node + 'vue/dist/', paths.public_admin + 'vue/');

// momentjs
mix.copy(paths.node + 'moment/min', paths.public_admin + 'moment/js');

// dropzone
mix.copy(paths.node + 'dropzone/dist/min/', paths.public_admin + 'dropzone/');

// Datatables
mix.copy(paths.node + 'datatables.net/js', paths.public_admin + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-bs4/js', paths.public_admin + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-bs4/css', paths.public_admin + 'datatables.net/css/');
mix.copy(paths.node + 'datatables.net-responsive/js', paths.public_admin + 'datatables.net/js/');
mix.copy(paths.node + 'datatables.net-responsive-bs4/css', paths.public_admin + 'datatables.net/css/');
mix.copy(paths.node + 'datatables.net-responsive-bs4/js', paths.public_admin + 'datatables.net/js/');

// jquery-bonsai
mix.copy(paths.node + 'jquery-bonsai/jquery.bonsai.js', paths.public_admin + 'jquery-bonsai/js/');
mix.copy(paths.node + 'jquery-bonsai/jquery.bonsai.css', paths.public_admin + 'jquery-bonsai/css/');
mix.copy(paths.node + 'jquery-bonsai/assets/svg-icons.css', paths.public_admin + 'jquery-bonsai/css/assets/');

// jquery-qubit
mix.copy(paths.node + 'jquery-qubit/jquery.qubit.js', paths.public_admin + 'jquery-qubit/js/');

// jquery-sortable
mix.copy(paths.node + 'es-jquery-sortable/source/js/jquery-sortable-min.js', paths.public_admin + 'jquery-sortable/js/');

// TinyMCE
mix.copy(paths.node + 'tinymce', paths.public_admin + 'tinymce');

// Ace-Editor Javascript
mix.copy(paths.node + 'ace-builds/src-min-noconflict', paths.public_admin + 'ace-builds');

// Fontawesome-iconpicker
mix.copy(paths.node + 'fontawesome-iconpicker/dist', paths.public_admin + 'fontawesome-iconpicker');

// Bootstrap-datepicker
mix.copy(paths.node + 'bootstrap-datepicker/dist/css', paths.public_admin + 'bootstrap-datepicker');
mix.copy(paths.node + 'bootstrap-datepicker/dist/js', paths.public_admin + 'bootstrap-datepicker');

// Select2
mix.copy(paths.node + 'select2/dist/js', paths.public_admin + 'select2');
mix.copy(paths.node + 'select2/dist/css', paths.public_admin + 'select2');

// Echarts
mix.copy(paths.node + 'echarts/dist', paths.public_admin + 'echarts');

// jQuery Sparklines
mix.copy(paths.node + 'jquery-sparkline', paths.public_admin + 'jquery-sparkline');

// Chart.js
mix.copy(paths.node + 'chart.js/dist', paths.public_admin + 'chart.js');

if (mix.inProduction()) {
    mix.version();
}
