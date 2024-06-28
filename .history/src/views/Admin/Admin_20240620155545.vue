<template>
  <div class="container">
    <a-menu
      v-model:openKeys="state.openKeys"
      v-model:selectedKeys="state.selectedKeys"
      :mode="state.mode"
      :items="items"
      :theme="state.theme"
      class="menu"
      @click="handleMenuClick"
    ></a-menu>
    <div class="content">
      <component :is="currentComponent" />
    </div>
  </div>
</template>

<script setup>
import { h, reactive, ref } from "vue";
import User from "./User.vue";
import Book from "./Book.vue";
import Cate from "./Cate.vue";
import Author from "./Author.vue";
import Language from "./Language.vue";
import Publisher from "./Publisher.vue";
import Discount from "./Discount.vue";
import DashBoard from "./DashBoard.vue";

import {
  MailOutlined,
  CalendarOutlined,
  AppstoreOutlined,
  SettingOutlined,
  HomeOutlined,
  UserOutlined,
  PieChartOutlined,
  BarChartOutlined,
  LineChartOutlined,
  RightCircleOutlined,
} from "@ant-design/icons-vue";

const state = reactive({
  mode: "inline",
  theme: "dark",
  selectedKeys: ["1"],
  openKeys: ["sub1"],
});

function getItem(label, key, icon, children, type) {
  return {
    key,
    icon,
    children,
    label,
    type,
  };
}

const items = reactive([
  getItem("DashBoard", "1", h(HomeOutlined)),
  getItem("Quản lý người dùng", "2", h(UserOutlined)),
  getItem("Quản lý Sản Phẩm", "3", h(PieChartOutlined), [
    getItem("Quản lý Sách", "sub1", h(RightCircleOutlined)),

    getItem("Quản lý Danh Mục", "sub2", h(RightCircleOutlined)),
    getItem("Quản lý Ngôn Ngữ", "sub3", h(RightCircleOutlined)),
    getItem("Quản lý Nhà Xuất Bản", "sub4", h(RightCircleOutlined)),
    getItem("Quản lý Tác Giả", "sub5", h(RightCircleOutlined)),
  ]),
  getItem("Quản lý khuyến mãi", "4", h(BarChartOutlined)),

  getItem("Quản lý đơn hàng", "5", h(LineChartOutlined)),
]);

const currentComponent = ref(DashBoard); // Default component

const handleMenuClick = (e) => {
  if (e.key === "2") {
    currentComponent.value = User;
  } else if (e.key === "sub1") {
    currentComponent.value = Book;
  } else if (e.key === "sub2") {
    currentComponent.value = Cate;
  } else if (e.key === "sub3") {
    currentComponent.value = Language;
  } else if (e.key === "sub4") {
    currentComponent.value = Publisher;
  } else if (e.key === "sub5") {
    currentComponent.value = Author;
  } else if (e.key === "4") {
    currentComponent.value = Discount;
  } else if (e.key === "1") {
    currentComponent.value = DashBoard;
  }

  // Add more conditions here for other navigation items
};
</script>

<style>
.container {
  display: flex;
  height: 100vh;
  overflow: hidden;
}
.menu {
  width: 256px;
  height: 100vh;
  overflow: hiiden;
}
.content {
  flex-grow: 1;
  padding: 20px;
}
</style>
