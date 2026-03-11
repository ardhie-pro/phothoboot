import { defineConfig } from 'vite';

export default defineConfig({
  server: {
    proxy: {
      '/upload.php': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
      '/view.php': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
      '/uploads': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
    },
  },
});
