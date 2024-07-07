import { mount } from "@vue/test-utils";
import NavBar from "@/components/NavBar.vue"; // Adjust the path as needed
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";

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

describe("NavBar.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");
    await router.isReady();
    wrapper = mount(NavBar, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
});
