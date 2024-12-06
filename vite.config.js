import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.tsx',
            'resources/js/server.tsx',
        ]),
        // laravel({
        //     input: ['resources/css/app.css', 'resources/js/app.tsx'],
        //     ssr: 'resources/js/server.tsx',
        //     refresh: true,
        // }),
        react()
    ],
    ssr: {
        noExternal: ['@inertiajs/server'],
    },
    build: {
        target: 'esnext',
    },
    server: {
        host: true,
        port: 5173
    },
});
