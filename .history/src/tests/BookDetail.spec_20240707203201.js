import { mount } from "@vue/test-utils";
import BookDetail from "@/views/Pages/BookDetail.vue";
import { createRouter, createWebHistory } from "vue-router";
import { describe, it, expect, beforeEach } from "vitest";
import axios from "axios";

const routes = [
  { path: "/", name: "home", component: { template: "<div>Home</div>" } },
  {
    path: "/author/:id/:name",
    name: "AuthorBook",
    component: { template: "<div>AuthorBook</div>" },
  },
  {
    path: "/publisher/:id/:name",
    name: "Publisher",
    component: { template: "<div>Publisher</div>" },
  },
  {
    path: "/languages/:id/:name",
    name: "Languages",
    component: { template: "<div>Languages</div>" },
  },
  {
    path: "/category/:name",
    name: "Category",
    component: { template: "<div>Category</div>" },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});
describe("BookDetail.vue", () => {
  let wrapper;

  beforeEach(async () => {
    router.push("/");

    await router.isReady();
    wrapper = mount(BookDetail, {
      global: {
        plugins: [router],
      },
    });
  });
  it("renders the component", () => {
    expect(wrapper.exists()).toBe(true);
  });
  it("Detail of the book is loading perfect", async () => {
    const response = await axios.get(
      "http://localhost/LVTN/book-store/src/api/getDetailBook.php?id=2"
    );
    expect(response.status).toBe(200);
  });
});
