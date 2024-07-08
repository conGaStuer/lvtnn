import { mount } from "@vue/test-utils";
import Login from "@/views/Authen/Login.vue";
import Register from "@/views/Authen/Register.vue";
import NewPass from "@/views/Authen/NewPass.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
const routes = [
  { path: "/register", component: Register },
  { path: "/newpass", component: NewPass },
  { path: "/profile", component: { template: "<div>Profile</div>" } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
export { routes };
export default router;
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
});
