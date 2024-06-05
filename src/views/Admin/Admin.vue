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
import {
  MailOutlined,
  CalendarOutlined,
  AppstoreOutlined,
  SettingOutlined,
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
  getItem("Navigation One", "1", h(MailOutlined)),
  getItem("Navigation Two", "2", h(CalendarOutlined)),
  getItem("Navigation Two", "sub1", h(AppstoreOutlined), [
    getItem("Option 3", "3"),
    getItem("Option 4", "4"),
    getItem("Submenu", "sub1-2", null, [
      getItem("Option 5", "5"),
      getItem("Option 6", "6"),
    ]),
  ]),
  getItem("Navigation Three", "sub2", h(SettingOutlined), [
    getItem("Option 7", "7"),
    getItem("Option 8", "8"),
    getItem("Option 9", "9"),
    getItem("Option 10", "10"),
  ]),
]);

const currentComponent = ref(User); // Default component

const handleMenuClick = (e) => {
  if (e.key === "2") {
    currentComponent.value = User;
  }
  // Add more conditions here for other navigation items
};
</script>

<style>
.container {
  display: flex;
  height: 100vh;
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
