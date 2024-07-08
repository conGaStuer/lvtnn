import { mount } from "@vue/test-utils";
import NewPass from "@/views/Authen/NewPass.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/login",
    name: "login",
    component: { template: "<div>Login</div>" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("NewPass.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");
    await router.isReady();
    wrapper = mount(NewPass, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("Check post data to backend , send new password", async () => {
    const emailInput = wrapper.find('input[type="email"]');
    await emailInput.setValue("test@example.com");
    await wrapper.find("button").trigger("click"); // Trigger click on the button

    // Wait for the axios request to complete
    await wrapper.vm.$nextTick();

    // Assert axios post request was called with the correct URL and data
    expect(axios.post).toHaveBeenCalledWith(
      "http://localhost/LVTN/book-store/src/api/newpass.php",
      { email: "test@example.com" }
    );

    // Assert response status
    const response = await axios.post();
    expect(response.status).toBe(200);
  });
});
