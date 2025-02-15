import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify, { transformAssetUrls } from "vite-plugin-vuetify";
import VueI18nPlugin from "@intlify/unplugin-vue-i18n/vite";
import { resolve } from 'path';

export default defineConfig(() => ({
    define: {
        __VUE_PROD_DEVTOOLS__: true
    },
    plugins: [
        laravel({
            input: [
                'resources/js/assets/scss/app.scss',
                'resources/js/main.ts',
            ],
            refresh: true,
        }),
        VueI18nPlugin({
            runtimeOnly: false,
            /* options */
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({
            autoImport: true,
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': resolve(__dirname, 'resources/js')
        },
        extensions: [".js", ".json", ".jsx", ".mjs", ".ts", ".tsx", ".vue"],
    },
    css: {
        postcss: './postcss.config.js'
    },
}));
