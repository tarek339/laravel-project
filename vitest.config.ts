import { resolve } from "node:path";
import { defineConfig } from "vitest/config";

export default defineConfig({
    test: {
        environment: "jsdom",
        globals: true,
        setupFiles: "./vitest.setup.ts",
        exclude: ["**/node_modules/**", "**/vendor/**", "**/bootstrap/**", "**/storage/**"],
    },
    resolve: {
        alias: {
            "@": resolve(__dirname, "resources/js"),
            "ziggy-js": resolve(__dirname, "vendor/tightenco/ziggy"),
        },
    },
});
