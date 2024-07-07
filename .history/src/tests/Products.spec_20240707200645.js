import { mount } from "@vue/test-utils";
import Products from "@/views/Pages/Products.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/bookDetail/:id",
    name: "bookDetail",
    component: { template: "<div>Book Detail</div>" }, // Mock the component if needed
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("Products.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");

    await router.isReady();
    wrapper = mount(Products, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("Check products is valiable in rest api", async () => {
    const response = await axios.get(
      "http://localhost/LVTN/book-store/src/api/getAllBook.php"
    );
    expect(response.status).toBe(200);
    expect(response.data.length).toBeGreaterThan(0);
    expect(response.data[0].MSach).toBeDefined();
  });
});
