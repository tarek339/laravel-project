import DriverProfile from "@/pages/drivers/driver-profile";
import DriversTable from "@/pages/drivers/drivers-table";
import { render, screen } from "@testing-library/react";
import { describe, expect, it, vi } from "vitest";

// Mock der Inertia Head Komponente
vi.mock("@inertiajs/react", () => ({
    Head: ({ title }: { title: string }) => (
        <head>
            <title>{title}</title>
        </head>
    ),
}));

// Mock des AppLayout
vi.mock("@/layouts/app-layout", () => ({
    default: ({ children }: { children: React.ReactNode }) => <div data-testid="app-layout">{children}</div>,
}));

describe("DriversTable Component", () => {
    it("should render the drivers table layout", () => {
        // Render the component
        render(<DriversTable />);

        // Test dass der Titel gesetzt wird
        expect(document.title).toBe("Drivers Listing");

        // Test dass das Layout gerendert wird
        expect(screen.getByTestId("app-layout")).toBeInTheDocument();
    });

    it("should render the driver profile content", () => {
        // Render the component
        render(<DriverProfile />);

        const headingElement = screen.getByRole("heading", {
            name: "Driver Profile",
            level: 1,
        });
        expect(headingElement).toBeInTheDocument();

        // Verwende getByText statt getByRole für den Paragraph-Text
        const paragraphElement = screen.getByText(
            "This is a placeholder for the driver profile page. You can add more details about the driver here.",
        );
        expect(paragraphElement).toBeInTheDocument();
    });
});
