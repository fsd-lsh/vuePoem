import {fileURLToPath, URL} from 'node:url';
import {defineConfig} from 'vite';
import vue from '@vitejs/plugin-vue';
import vueJsx from '@vitejs/plugin-vue-jsx';
import  * as path from 'path';
import * as fs from 'fs';
// @ts-ignore
import * as ini from 'ini';
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

const envConfig = ini.parse(
    fs.readFileSync(
        path.resolve(__dirname, '../.env')
    ).toString()
);

// https://vitejs.dev/config/
export default defineConfig({

    server: {
        open: true,
        port: envConfig.VUE_PORT ? envConfig.VUE_PORT : 7777,
        strictPort: false,
        proxy: {
            "/admin": {
                target: `http://${envConfig.PHP_HOST}:${envConfig.PHP_PORT}`,
                changeOrigin: true,
                secure: false,
            }
        }
    },

    plugins: [
        vue(),
        vueJsx(),
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            resolvers: [ElementPlusResolver()],
        }),
    ],

    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url)),
            'vue-i18n': 'vue-i18n/dist/vue-i18n.cjs.js',
        },
        extensions: ['.mjs', '.js', '.ts', '.jsx', '.tsx', '.json', '.vue'],
    },

    // https://cn.vitejs.dev/config/build-options.html
    build: {
        outDir: '../server/public',
        assetsDir: 'static',
        target:['edge90','chrome90','firefox90','safari15']
    },
});
