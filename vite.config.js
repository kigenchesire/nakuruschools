import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/app.scss',
                'resources/js/app.js',
                'resources/scss/admin.scss',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Bootstrap 5.3 still uses Sass features newer Dart Sass flags as deprecated.
                quietDeps: true,
                silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'mixed-decls'],
            },
        },
    },
    build: {
        // CKEditor is one large lazy-loaded chunk, only fetched on admin pages with an editor.
        chunkSizeWarningLimit: 2500,
    },
});
