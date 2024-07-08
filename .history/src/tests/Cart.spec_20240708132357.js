import { mount } from "@vue/test-utils";
import Cart from "@/views/Pages/Cart.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/order",
    name: "order",
    component: { template: "<div>Order</div>" }, // Mock the component if needed
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("Cart.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");

    await router.isReady();
    wrapper = mount(Cart, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
});
