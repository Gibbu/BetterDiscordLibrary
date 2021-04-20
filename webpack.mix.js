const mix = require('laravel-mix');
const path = require('path');

require('laravel-mix-svelte');

mix.webpackConfig({
    resolve: {
        alias: {
            '$components': path.resolve(__dirname, 'resources/js/Components'),
            '$layout': path.resolve(__dirname, 'resources/js/Layout'),
            '$lib': path.resolve(__dirname, 'resources/js/Lib'),
        }
    }
})


mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss')
    ])
    .svelte();