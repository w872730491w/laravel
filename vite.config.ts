import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import path from 'path';
import AutoImport from 'unplugin-auto-import/vite';
import { NaiveUiResolver } from 'unplugin-vue-components/resolvers';
import Components from 'unplugin-vue-components/vite';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts', 'resources/js/admin.ts'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        AutoImport({
            imports: [
                'vue',
                {
                    'naive-ui': ['useDialog', 'useMessage', 'useNotification', 'useLoadingBar'],
                },
            ],
            dirs: ['./resources/js/utils'],
            dts: './resources/js/types/auto-imports.d.ts',
        }),
        Components({
            resolvers: [NaiveUiResolver()],
            dts: './resources/js/types/components.d.ts',
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
