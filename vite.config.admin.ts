import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import laravel from 'laravel-vite-plugin'
import { resolve } from 'node:path'
import path from 'path'
import AutoImport from 'unplugin-auto-import/vite'
import { NaiveUiResolver } from 'unplugin-vue-components/resolvers'
import Components from 'unplugin-vue-components/vite'
import { defineConfig } from 'vite'
import monacoEditorPlugin from 'vite-plugin-monaco-editor'

const prefix = `monaco-editor/esm/vs`

export default defineConfig({
    build: {
        cssMinify: 'lightningcss',
    },
    plugins: [
        laravel({
            input: ['resources/js/admin/main.ts'],
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
        vueJsx(),
        AutoImport({
            imports: [
                'vue',
                {
                    'naive-ui': ['useDialog', 'useMessage', 'useNotification', 'useLoadingBar'],
                },
            ],
            dirs: ['./resources/js/admin/utils'],
            dts: './resources/js/admin/types/auto-imports.d.ts',
        }),
        Components({
            dirs: ['resources/js/admin/components'],
            deep: true,
            directoryAsNamespace: true,
            resolvers: [NaiveUiResolver()],
            dts: './resources/js/admin/types/components.d.ts',
        }),
        (monacoEditorPlugin as any).default({
            languageWorkers: ['editorWorkerService', 'json', 'typescript'],
        }),
    ],
    optimizeDeps: {
        include: [
            `${prefix}/language/json/json.worker`,
            `${prefix}/language/typescript/ts.worker`,
            `${prefix}/editor/editor.worker`,
        ],
    },
    resolve: {
        alias: {
            '@admin': path.resolve(__dirname, './resources/js/admin'),
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
})
