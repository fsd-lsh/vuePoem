import { defineConfig } from "vite";
import vue from '@vitejs/plugin-vue2';

// https://vitejs.dev/config/
export default defineConfig({

    server: {
        proxy: {
            "/admin": {
                target: "http://127.0.0.1:8899",
                changeOrigin: true,
                secure: false,
                pathRewrite: {
                    "^/admin": "/admin"
                }
            }
        }
    },

    plugins: [vue()],

    // https://cn.vitejs.dev/config/build-options.html
    build: {
        outDir: '../server/public',
        assetsDir: 'static'
    },
});
