import Login from "@/pages/auth/login";
import { fireEvent, render, screen } from "@testing-library/react";
import { describe, expect, it } from "vitest";

describe("Login Component", () => {
    it("should render correctly", () => {
        // Test implementation
        render(<Login canResetPassword={true} />);

        // Check for submit button
        expect(screen.getByRole("button", { name: "Log in" })).toBeInTheDocument();

        // Check for text content
        expect(screen.getByText("Don't have an account?")).toBeInTheDocument();

        // Check for links
        expect(screen.getByRole("link", { name: "Sign up" })).toBeInTheDocument();
        expect(screen.getByRole("link", { name: "Forgot password?" })).toBeInTheDocument();
    });

    it("should render email input with correct properties", () => {
        render(<Login canResetPassword={false} />);

        // Find the email input element
        const emailInput = screen.getByLabelText("Email address");

        // Test that the input is rendered
        expect(emailInput).toBeInTheDocument();

        // Test input attributes
        expect(emailInput).toHaveAttribute("type", "email");
        expect(emailInput).toHaveAttribute("id", "email");
        expect(emailInput).toHaveAttribute("autoComplete", "email");
        expect(emailInput).toHaveAttribute("placeholder", "email@example.com");
        expect(emailInput).toHaveAttribute("tabIndex", "1");

        // Test required attribute
        expect(emailInput).toBeRequired();

        // Test that it has focus (autofocus)
        expect(emailInput).toHaveFocus();

        // Test initial value (should be empty)
        expect(emailInput).toHaveValue("");
    });

    it("should render password input with correct properties", () => {
        render(<Login canResetPassword={false} />);

        // Find the password input element
        const passwordInput = screen.getByLabelText("Password");

        // Test that the input is rendered
        expect(passwordInput).toBeInTheDocument();

        // Test input attributes
        expect(passwordInput).toHaveAttribute("type", "password");
        expect(passwordInput).toHaveAttribute("id", "password");
        expect(passwordInput).toHaveAttribute("autoComplete", "current-password");
        expect(passwordInput).toHaveAttribute("placeholder", "Password");
        expect(passwordInput).toHaveAttribute("tabIndex", "2");

        // Test required attribute
        expect(passwordInput).toBeRequired();

        // Test initial value (should be empty)
        expect(passwordInput).toHaveValue("");
    });

    it("should render the checkbox with correct properties", () => {
        render(<Login canResetPassword={false} />);

        // Find the checkbox element
        const checkbox = screen.getByRole("checkbox", { name: "Remember me" });

        // Test that the checkbox is rendered
        expect(checkbox).toBeInTheDocument();

        // Test input attributes
        expect(checkbox).toHaveAttribute("id", "remember");
        expect(checkbox).toHaveAttribute("tabIndex", "3");

        // Test initial checked state
        expect(checkbox).not.toBeChecked();

        // Test that checkbox is clickable (no error when clicked)
        fireEvent.click(checkbox);
        // Note: Since we're using a mocked useForm hook, the actual state won't change
        // In a real app, this would toggle the checked state
    });
});
