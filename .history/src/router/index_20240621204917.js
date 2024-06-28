import { createRouter, createWebHashHistory } from "vue-router";
import HomeView from "../views/Pages/HomeView.vue";

const routes = [
  {
    path: "/",
    name: "home",
    component: HomeView,
  },
  {
    path: "/about",
    name: "about",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/AboutView.vue"),
  },
  {
    path: "/admin",
    name: "admin",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Admin/Admin.vue"),
  },
  {
    path: "/products",
    name: "SHOP",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Products.vue"),
  },
  {
    path: "/notfound",
    name: "notfound",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/NotFound.vue"),
  },
  {
    path: "/search/:id",
    name: "search",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(
        /* webpackChunkName: "about" */ "../views/Pages/SearchProduct.vue"
      ),
  },
  {
    path: "/products/:id",
    name: "bookDetail",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/BookDetail.vue"),
  },
  {
    path: "/author/:id/:name",
    name: "AuthorBook",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/AuthorBook.vue"),
  },
  {
    path: "/publisher/:id/:name",
    name: "Publisher",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Publisher.vue"),
  },
  {
    path: "/languages/:id/:name",
    name: "Languages",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Languages.vue"),
  },
  {
    path: "/category/:name",
    name: "Category",
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Category.vue"),
  },
  {
    path: "/login",
    name: "login",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/Login.vue"),
  },
  {
    path: "/register",
    name: "register",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/Register.vue"),
  },
  {
    path: "/cart",
    name: "cart",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Cart.vue"),
  },
  {
    path: "/order",
    name: "order",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Order.vue"),
  },
  {
    path: "/process/:id",
    name: "process",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/OrderProcess.vue"),
  },
  {
    path: "/contact",
    name: "contact",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Pages/Contact.vue"),
  },
  {
    path: "/newpass",
    name: "newpass",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/NewPass.vue"),
  },
  {
    path: "/changephone",
    name: "changephone",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/ChangePhone.vue"),
  },
  {
    path: "/changemail",
    name: "changemail",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/ChangeMail.vue"),
  },
  {
    path: "/profile",
    name: "profile",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/Authen/Profile.vue"),
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
