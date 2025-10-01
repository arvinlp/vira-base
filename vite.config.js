import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import {glob} from 'glob';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // ورودی اصلی پروژه
                'resources/js/app.js',
                'resources/css/app.css',

                // تمام صفحات Vue داخل ماژول‌ها
                ...glob.sync('Modules/**/Resources/js/Pages/**/*.vue'),

                // تمام CSS داخل ماژول‌ها
                ...glob.sync('Modules/**/Resources/css/**/*.css'),

                // تمام SCSS داخل ماژول‌ها
                ...glob.sync('Modules/**/Resources/scss/**/*.scss'),
            ],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
            '@modules': path.resolve(__dirname, 'Modules'),
        },
    },
});
