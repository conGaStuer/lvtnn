import { mount } from "@vue/test-utils";
import Login from "@/views/Authen/Login.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/register",
    name: "register",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: { template: "<div>Register</div>" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("Login.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");
    await router.isReady();
    wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });
  });
  it("render the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("testing value user typing in input field username", async () => {
    const username = wrapper.find("input[type='text']");
    await username.setValue("some value");
    expect(wrapper.find("input[type='text']").element.value).toBe("some value");
  });
  it("testing value user typing in input field password", async () => {
    const password = wrapper.find("input[type='password']");
    await password.setValue("some value");
    expect(wrapper.find("input[type='password']").element.value).toBe(
      "some value"
    );
  });
  it("navigates to register page on click", async () => {
    router.push("/");
    await router.isReady();

    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });

    await wrapper.find('a[href="/register"]').trigger("click");
    await router.isReady();

    expect(wrapper.vm.$route.path).toBe("/register");
  });
});
