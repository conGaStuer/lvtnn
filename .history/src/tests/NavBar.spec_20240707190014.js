// NavBar.spec.js
import { mount } from "@vue/test-utils";
import NavBar from "@/views/UI_Components/NavBar.vue";
import { createRouter, createWebHistory } from "vue-router";

// Mô phỏng đối tượng window
global.window = {};

const routes = [
  // Các route của bạn
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

describe("NavBar", () => {
  it("renders correctly", async () => {
    const wrapper = mount(NavBar, {
      global: {
        plugins: [router],
      },
    });
    await router.isReady();
    expect(wrapper.html()).toContain("expected content");
  });
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

  it("show the mega menu on mouseover and hide on mouseleave", async () => {
    const navItem = wrapper.find(".nav-item:nth-child(2) > a");
    await navItem.trigger("mouseover");
    expect(wrapper.vm.megaMenuVisible).toBe(true);
    const megaMenu = wrapper.find(".mega-menu");
    await megaMenu.trigger("mouseleave");
    expect(wrapper.vm.megaMenuVisible).toBe(false);
  });

  it("show user dropdown on mouseover and hide when mouseleave", async () => {
    const userIcon = wrapper.find(".fa-user");
    await userIcon.trigger("mouseover");
    expect(wrapper.vm.userDropdownVisible).toBe(true);
    const userDropdown = wrapper.find(".dropdown1");
    await userDropdown.trigger("mouseleave");
    expect(wrapper.vm.userDropdownVisible).toBe(false);
  });
});
