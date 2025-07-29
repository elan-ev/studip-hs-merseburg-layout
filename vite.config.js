import vue from '@vitejs/plugin-vue';
import { resolve } from 'node:path';
import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';

export default defineConfig(({ mode }) => {
    return {
        build: {
            lib: {
                entry: resolve(__dirname, 'resources/js/app.js'),
                formats: ['umd'],
                name: 'studip-layout-hs-merseburg',
                fileName: (format) => `studip-layout-hs-merseburg.${format}.[hash].js`,
            },
            manifest: true,
            rollupOptions: {
                external: ['vue'],
                output: {
                    globals: {
                        vue: 'Vue',
                    }
                }
            },
            sourcemap: true,
        },
        define: { 'process.env.NODE_ENV': `"${mode}"` },
        plugins: [
            vue(),
        ],
        resolve: {
            alias: {
                '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            },
        },
    };
});
