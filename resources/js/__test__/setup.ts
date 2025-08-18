import "@testing-library/jest-dom/vitest";
import { cleanup } from "@testing-library/react";
import React from "react";
import { afterEach, vi } from "vitest";

// runs a clean after each test case (e.g. clearing jsdom)
afterEach(() => {
    cleanup();
});

// Mock ResizeObserver
global.ResizeObserver = vi.fn().mockImplementation(() => ({
    observe: vi.fn(),
    unobserve: vi.fn(),
    disconnect: vi.fn(),
}));

// Mock the global route function used by Laravel/Inertia
(globalThis as typeof globalThis & { route: (name: string) => string }).route = vi.fn((name: string) => {
    // Return mock URLs for different routes
    const routes: Record<string, string> = {
        login: "/login",
        register: "/register",
        "password.request": "/forgot-password",
        home: "/",
        dashboard: "/dashboard",
    };

    return routes[name] || `/${name}`;
});

// Mock Inertia.js hooks and components
vi.mock("@inertiajs/react", () => ({
    Head: () => null,
    Link: ({ children, href, ...props }: { children: React.ReactNode; href: string; [key: string]: unknown }) =>
        React.createElement("a", { href, ...props }, children),
    useForm: () => ({
        data: {
            email: "",
            password: "",
            remember: false,
        },
        setData: vi.fn(),
        post: vi.fn(),
        processing: false,
        errors: {},
        reset: vi.fn(),
    }),
}));
