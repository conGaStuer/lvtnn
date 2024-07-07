import { mount } from "@vue/test-utils";
import NavBar from "@/views/UI_Components/NavBar.vue";
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

  it("Search Value is true when user typing", async () => {
    const searchInput = wrapper.get(".inputSearch");
    await searchInput.setValue("test search");
    expect(wrapper.vm.searchKeyWord).toBe("test search");
  });
});
