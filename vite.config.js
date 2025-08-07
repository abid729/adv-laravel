import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build', // Ensure assets are output to public/build
        manifest: true, // Generate manifest.json
    },
    server: {
        hmr: {
            host: 'localhost', // Ensure HMR works for local development
        },
    },
});
