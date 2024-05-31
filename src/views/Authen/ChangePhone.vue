<template>
  <div class="container">
    <div class="left-side" :style="backgroundStyle"></div>
    <div class="right-side">
      <span class="missing"
        >Chưa có tài khoản?

        <router-link to="/register">Đăng ký ngay</router-link>
      </span>
      <div class="form-fogot">
        <h3>Đổi số điện thoại</h3>
        <p>Nhập số điện thoại</p>
        <input type="text" v-model="phone" /><br />
        <button @click="sendOTP">Gửi</button>
        <p>Nhập mã OTP gửi về SDT</p>
        <input type="text" v-model="otp" /><br />
        <button @click="verifyOTP">Xác nhận mã OTP</button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from "vue";
import axios from "axios";

export default {
  setup() {
    const email = ref("");
    const phone = ref("");
    const otp = ref("");
    const message = ref("");
    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/forgot_pass_image.png")})`,
    }));

    // Hàm gửi OTP
    const sendOTP = async () => {
      try {
        const response = await axios.post(
          "http://localhost/LVTN/book-store/src/api/updateProfile.php",
          {
            phone: phone.value,
          }
        );
        if (response.data.success) {
          message.value = "Mã OTP đã được gửi đi.";
          // Hiển thị form xác nhận OTP
          // Có thể sử dụng một biến ref để điều khiển việc hiển thị form
        } else {
          message.value = response.data.message;
        }
      } catch (error) {
        console.error("Error sending OTP:", error);
      }
    };

    // Hàm xác nhận OTP
    const verifyOTP = async () => {
      try {
        const response = await axios.post("verifyotp.php", {
          phone: phone.value,
          otp: otp.value,
        });
        if (response.data.success) {
          // Cập nhật CSDL và chuyển hướng đến trang /profile
          // Ví dụ: window.location.href = "/profile";
        } else {
          message.value = response.data.message;
        }
      } catch (error) {
        console.error("Error verifying OTP:", error);
      }
    };

    return {
      email,
      phone,
      otp,
      message,
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
