// vite.config.js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // Pastikan ini ada
                'resources/js/app.tsx',  // Pastikan ini adalah entry point utama Anda
            ],
            refresh: true,
        }),
        react(), // Pastikan plugin React diaktifkan
    ],
    // Opsi resolusi jika Anda menggunakan alias atau base URL (opsional, tapi bisa membantu)
    resolve: {
        alias: {
            // Contoh alias jika Anda menggunakannya di tsconfig.json
            // '@': '/resources/js',
        },
    },
});