import { defineConfig } from 'vite';

export default defineConfig({
  base: './',
  server: {
    proxy: {
      '/upload.php': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
      '/manage_templates.php': {
        target: 'http://localhost:8080',
        changeOrigin: true,
      },
      '/admin.php': {
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
