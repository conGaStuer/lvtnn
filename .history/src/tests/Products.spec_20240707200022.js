import { mount } from "@vue/test-utils";
import Products from "@/views/Pages/Products.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/about",
    name: "about",
    component: { template: "<div>About</div>" },
  },
  {
    path: "/contact",
    name: "contact",
    component: { template: "<div>Contact</div>" },
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
    expect(response.data).toHaveProperty("data");
    expect(response.data.data.length).toBeGreaterThan(0);
  });
});
