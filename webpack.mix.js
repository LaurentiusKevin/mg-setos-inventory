const mix = require('laravel-mix');

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

mix
    /**
     * Core UI
     */
    .copy('resources/css/assets','public/css')
    .copy('resources/js/assets','public/js')
    .copy('node_modules/@coreui/coreui/dist/css','public/css/admin')
    .copy('node_modules/@coreui/coreui/dist/js','public/js/admin')

    /**
     * Icon
     */
    .copy('node_modules/@fortawesome/fontawesome-free','public/icons/fontawesome-free')
    .copy('resources/images/avatars/svg','public/icons/avatars')
    .copy('resources/images/delivery/svg','public/icons/delivery')

    /**
     * Jquery
     */
    .scripts([
        'node_modules/jquery/dist/jquery.min.js'
    ],'public/js/jquery.js')

    /**
     * Axios
     */
    .copy('node_modules/axios/dist/axios.min.map','public/js')
    .scripts([
        'node_modules/axios/dist/axios.min.js'
    ],'public/js/axios.js')

    /**
     * Cleave
     */
    .scripts([
        'node_modules/cleave.js/dist/cleave.min.js',
        'node_modules/cleave.js/dist/addons/cleave-phone.id.js'
    ],'public/js/cleave.js')

    /**
     * DateRangePicker
     */
    .styles([
        'node_modules/daterangepicker/daterangepicker.css'
    ],'public/css/daterangepicker.css')
    .scripts([
        'node_modules/daterangepicker/daterangepicker.js'
    ],'public/js/daterangepicker.js')

    /**
     * Bootstrap
     */
    .copy('node_modules/bootstrap/dist/css/bootstrap.css.map','public/css')
    .copy('node_modules/bootstrap/dist/js/bootstrap.js.map','public/js')
    .styles([
        'node_modules/bootstrap/dist/css/bootstrap.css'
    ],'public/css/bootstrap.css')
    .scripts([
        'node_modules/bootstrap/dist/js/bootstrap.js'
    ],'public/js/bootstrap.js')

    /**
     * MomentJs
     */
    .copy('node_modules/moment/moment.js','public/js')

    /**
     * Sweetalert
     */
    .styles([
        'node_modules/@sweetalert2/themes/borderless/borderless.css'
    ],'public/css/sweetalert2-borderless.css')
    .styles([
        'node_modules/@sweetalert2/themes/dark/dark.min.css'
    ],'public/css/sweetalert2-dark.css')
    .styles([
        'node_modules/@sweetalert2/themes/default/default.css'
    ],'public/css/sweetalert2-default.css')
    .scripts([
        'node_modules/sweetalert2/dist/sweetalert2.js'
    ],'public/js/sweetalert2.js')

    /**
     * Datatables
     */
    .styles([
        'node_modules/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
        'node_modules/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css',
        'node_modules/datatables.net-select-bs4/css/select.bootstrap4.min.css',
    ],'public/css/datatables.css')
    .scripts([
        'node_modules/datatables.net/js/jquery.dataTables.min.js',
        'node_modules/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
        'node_modules/datatables.net-buttons/js/dataTables.buttons.min.js',
        'node_modules/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js',
        'node_modules/datatables.net-select/js/dataTables.select.min.js',
        'node_modules/datatables.net-select-bs4/js/select.bootstrap4.min.js'
    ],'public/js/datatables.js')

    /**
     * Select2
     */
    .styles([
        'node_modules/select2/dist/css/select2.css',
        'node_modules/@ttskch/select2-bootstrap4-theme/dist/select2-bootstrap4.css',
    ],'public/css/select2.css')
    .scripts([
        'node_modules/select2/dist/js/select2.js',
    ],'public/js/select2.js')
