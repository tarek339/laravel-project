import { afterAll, beforeEach, describe, expect, it, vi } from "vitest";

const fetchData = async () => {
    try {
        const res = await fetch("https://jsonplaceholder.typicode.com/posts/1");
        if (!res.ok) {
            throw new Error(`HTTP error! status: ${res.status}`);
        }
        return res.json();
    } catch (error) {
        console.error("Error fetching data:", (error as Error).message);
        throw error;
    }
};

describe("FetchData", () => {
    // beforeEach runs before each test in this describe block
    beforeEach(() => {
        vi.clearAllMocks();
    });

    // afterAll runs once after all tests in this describe block are complete
    afterAll(() => {
        vi.restoreAllMocks();
    });

    it("should fetch data successfully", async () => {
        const res = await fetchData();
        expect(res).toBeDefined(); // check if response is defined
        expect(res).toHaveProperty("userId"); // check if userId property exists
        expect(res).toHaveProperty("id"); // check if id property exists
        expect(res).toHaveProperty("title"); // check if title property exists
        expect(res).toHaveProperty("body"); // check if body property exists
        expect(res.userId).toBe(1); // check if userId is correct
        expect(res.id).toBe(1); // check if id is correct
        expect(res.title).toBe("sunt aut facere repellat provident occaecati excepturi optio reprehenderit"); // check if title is correct
        expect(res.body).toBe(
            "quia et suscipit\nsuscipit recusandae consequuntur expedita et cum\nreprehenderit molestiae ut ut quas totam\nnostrum rerum est autem sunt rem eveniet architecto",
        ); // check if body is correct
    });

    it("should handle 404 error", async () => {
        global.fetch = vi.fn().mockRejectedValue(new Error("HTTP error! status: 404"));
        await expect(fetchData()).rejects.toThrow("HTTP error! status: 404");
    });

    it("should handle 500 error", async () => {
        global.fetch = vi.fn().mockRejectedValue(new Error("HTTP error! status: 500"));
        await expect(fetchData()).rejects.toThrow("HTTP error! status: 500");
    });

    it("should handle network error", async () => {
        global.fetch = vi.fn().mockRejectedValue(new Error("Network error"));
        await expect(fetchData()).rejects.toThrow("Network error");
    });
});
