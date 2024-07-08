import { mount } from "@vue/test-utils";
import Order from "@/views/Pages/Order.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach, expectTypeOf } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/cart",
    name: "cart",
    component: { template: "<div>Cart</div>" }, // Mock the component if needed
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("Order.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");

    await router.isReady();
    wrapper = mount(Order, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("get cart from database based on userID", async () => {
    const userId = 1;
    const response = await axios.post(
      "http://localhost/LVTN/book-store/src/api/getorder.php",
      (userId = userId)
    );

    expect(response.status).toBe(200);
    expectTypeOf(response.data).toBeArray(true);
  });
});
