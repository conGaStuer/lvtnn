<template>
  <div class="container">
    <div class="left-side" :style="backgroundStyle"></div>
    <div class="right-side">
      <div class="form-fogot">
        <h3>Đổi email</h3>
        <p>Nhập email</p>
        <input type="text" v-model="email" /><br />
        <button @click="sendOTP">Gửi</button>
        <p>Nhập mã OTP gửi về email</p>
        <input type="text" v-model="otp" /><br />
        <button @click="verifyOTP">Xác nhận mã OTP</button>
      </div>
    </div>
    <router-link class="back" to="/profile">Quay lại</router-link>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import axios from "axios";

export default {
  setup() {
    const email = ref("");
    const otp = ref("");
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));

    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/forgot_pass_image.png")})`,
    }));

    const sendOTP = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/sendMail.php", {
          email: email.value,
          userId: currentUser.taikhoan,
        })
        .then((response) => {
          if (response.data.success) {
            alert("Mã OTP đã được gửi đi.");
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => {
          console.error("Error sending OTP:", error);
          alert("Đã xảy ra lỗi khi gửi mã OTP.");
        });
    };

    const verifyOTP = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/verifyOTP.php", {
          email: email.value,
          otp: otp.value,
          userId: currentUser.taikhoan,
        })
        .then((response) => {
          if (response.data.success) {
            alert("Mã OTP xác nhận thành công. Cập nhật email thành công!");
          } else {
            alert(response.data.message);
          }
        })
        .catch((error) => {
          console.error("Error verifying OTP:", error);
          alert("Đã xảy ra lỗi khi xác nhận mã OTP.");
        });
    };

    return {
      email,
      otp,
      backgroundStyle,
      sendOTP,
      verifyOTP,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/changemail.scss";
</style>
