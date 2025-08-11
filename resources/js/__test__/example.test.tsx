import { describe, expect, it } from "vitest";

function sum(a: number, b: number): number {
    return a + b;
}

describe("Example Component", () => {
    it("it should return the result", () => {
        const result = sum(2, 3);
        expect(result).toBe(5);
    });
});
