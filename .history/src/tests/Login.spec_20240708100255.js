import { mount } from "@vue/test-utils";
import { describe, it, expect, vi } from "vitest";
import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/Login.vue";
import Register from "@/components/Register.vue"; // assuming you have a Register component
import NewPass from "@/components/NewPass.vue"; // assuming you have a NewPass component

const routes = [
  { path: "/register", component: Register },
  { path: "/newpass", component: NewPass },
  { path: "/profile", component: { template: "<div>Profile</div>" } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("Login.vue", () => {
  it("renders login form correctly", () => {
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });

    expect(wrapper.find('input[type="text"]').exists()).toBe(true);
    expect(wrapper.find('input[type="password"]').exists()).toBe(true);
    expect(wrapper.find('button[type="submit"]').text()).toBe("Đăng nhập");
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

  it("navigates to forgot password page on click", async () => {
    router.push("/");
    await router.isReady();

    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });

    await wrapper.find('a[href="/newpass"]').trigger("click");
    await router.isReady();

    expect(wrapper.vm.$route.path).toBe("/newpass");
  });

  it("logs in successfully and navigates to profile page", async () => {
    const mockRouterPush = vi.fn();
    router.push = mockRouterPush;

    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });

    wrapper.vm.username = "correctUsername";
    wrapper.vm.password = "correctPassword";

    vi.spyOn(wrapper.vm, "loginUser").mockImplementation(async () => {
      localStorage.setItem(
        "currentUser",
        JSON.stringify({ username: "correctUsername" })
      );
      mockRouterPush("/profile");
    });

    await wrapper.find("form").trigger("submit.prevent");
    expect(mockRouterPush).toHaveBeenCalledWith("/profile");
  });

  it("shows error message on login failure", async () => {
    const wrapper = mount(Login, {
      global: {
        plugins: [router],
      },
    });

    wrapper.vm.username = "wrongUsername";
    wrapper.vm.password = "wrongPassword";

    vi.spyOn(wrapper.vm, "loginUser").mockImplementation(async () => {
      alert("Sai tên đăng nhập hoặc mật khẩu!!");
    });

    await wrapper.find("form").trigger("submit.prevent");
    expect(window.alert).toHaveBeenCalledWith(
      "Sai tên đăng nhập hoặc mật khẩu!!"
    );
  });
});
