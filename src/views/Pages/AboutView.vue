<template>
  <div>
    <h1>Xin chào, {{ currentUser.tenKH }}!</h1>
    <p>Email: {{ currentUser.sdt }}</p>
    <!-- Hiển thị các thông tin khác của người dùng -->
    <router-link to="/">Home</router-link>
  </div>
</template>

<script>
export default {
  data() {
    return {
      currentUser: JSON.parse(localStorage.getItem("currentUser")),
    };
  },
};
</script>
