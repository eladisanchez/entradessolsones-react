import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";
import { visualizer } from "rollup-plugin-visualizer";

export default defineConfig({
  plugins: [
    laravel({
      input: [
        "resources/scss/app.scss",
        "resources/js/app.jsx",
        "resources/scss/admin.scss",
      ],
      refresh: true,
    }),
    react(),
    visualizer({ open: true }),
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          react: ["react", "react-dom"],
        },
      },
    },
    terserOptions: {
      compress: {
        drop_console: true,
      },
    },
  },
  server: {
    host: "0.0.0.0",
    strictPort: true,
    port: 5173,
    hmr: {
      host: process.env.DDEV_HOSTNAME,
      protocol: "wss",
    },
  },
});
