import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue2';
import  * as path from 'path';
import * as fs from 'fs';
import * as ini from 'ini';

const envConfig = ini.parse(
    fs.readFileSync(
        path.resolve(__dirname, '../.env')
    ).toString()
);

// https://vitejs.dev/config/
export default defineConfig({

    server: {
        port: envConfig.VUE_PORT ? envConfig.VUE_PORT : 7777,
        strictPort: false,
        proxy: {
            "/admin": {
                target: `http://${envConfig.PHP_HOST}:${envConfig.PHP_PORT}`,
                changeOrigin: true,
                secure: false,
                pathRewrite: {
                    "^/admin": "/admin",
                }
            }
        }
    },

    plugins: [
        vue(),
    ],

    // https://cn.vitejs.dev/config/build-options.html
    build: {
        outDir: '../server/public',
        assetsDir: 'static',
    },
});
