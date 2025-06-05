// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.tsx',
            ],
            refresh: true,
        }),
        react(),
    ],
    server: {
        host: '0.0.0.0', // Coba ini untuk memastikan Vite bisa diakses
        port: 5173,
        hmr: {
            host: 'localhost', // Ini harus 'localhost' atau IP yang bisa diakses dari browser
        },
        // Jika masih ada masalah CORS, bisa tambahkan:
        // cors: {
        //     origin: '*',
        //     methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
        //     preflightContinue: false,
        //     optionsSuccessStatus: 204
        // }
    }
});