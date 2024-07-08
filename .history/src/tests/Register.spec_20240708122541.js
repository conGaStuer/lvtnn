import { mount } from "@vue/test-utils";
import Login from "@/views/Authen/Login.vue";
import Register from "@/views/Authen/Register.vue";
import NewPass from "@/views/Authen/NewPass.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
const routes = [
  { path: "/login", component: Login },
  { path: "/newpass", component: NewPass },
  { path: "/profile", component: { template: "<div>Profile</div>" } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
export { routes };
export default router;
describe("Register.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");
    await router.isReady();
    wrapper = mount(Register, {
      global: {
        plugins: [router],
      },
    });
  });
  it("render the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("Check user typing in input field username", async () => {
    const username = wrapper.find("input[type="text"]")
  })
});
