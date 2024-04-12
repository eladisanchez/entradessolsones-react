import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/scss/admin/app.scss',
                'resources/scss/ensolsonat/app.scss',
                'resources/js/app.jsx',
                'resources/js/admin/app.js'
            ],
            refresh: true,
        }),
        react(),
    ],

    server : {
        host: '0.0.0.0',
        strictPort: true,
        port: 5173,
        hmr:{
            host: process.env.DDEV_HOSTNAME,
            protocol : 'wss'
        }
     },
});
