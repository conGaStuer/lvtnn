import { mount } from "@vue/test-utils";
import Products from "@/views/Pages/Products.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";

const router = createRouter({
  history: createWebHistory(),
  routes: [
    // Define your routes here if needed
  ],
});

describe("Products.vue", () => {
  let wrapper;

  beforeEach(async () => {
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
});
