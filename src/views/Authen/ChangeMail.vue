<template>
  <div class="container">
    <div class="left-side" :style="backgroundStyle"></div>
    <div class="right-side">
      <span class="missing">
        Chưa có tài khoản?
        <router-link to="/register">Đăng ký ngay</router-link>
      </span>
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
import { ref, computed, onMounted } from "vue";
import axios from "axios";

export default {
  setup() {
    const email = ref("");
    const otp = ref("");
    const message = ref("");
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));

    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/forgot_pass_image.png")})`,
    }));
    const userData = ref(currentUser);

    const sendOTP = () => {
      alert("Mã OTP đã được gửi về email của bạn");
      console.log("Sending OTP with data:", {
        email: email.value,
        userId: userData.value.taikhoan,
      });

      axios
        .post("http://localhost/LVTN/book-store/src/api/sendMail.php", {
          email: email.value,
          userId: userData.value.taikhoan,
        })
        .then((response) => {
          if (response.data.success) {
            message.value = "Mã OTP đã được gửi đi.";
          } else {
            message.value = response.data.message;
          }
        })
        .catch((err) => {
          console.error("Error sending OTP:", error);
          message.value = "Đã xảy ra lỗi khi gửi mã OTP.";
        });
    };

    const verifyOTP = async () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/verifyOTP.php", {
          email: email.value,
          otp: otp.value,
          userId: userData.value.taikhoan,
        })
        .then((response) => {
          if (response.data.success) {
            message.value = "Mã OTP xác nhận thành công.";
          } else {
            message.value = response.data.message;
            window.location.reload();
            alert("Cập nhật email thành công");
          }
        })
        .catch((err) => {
          console.error("Error sending OTP:", err);
          message.value = "Đã xảy ra lỗi khi gửi mã OTP.";
          alert("Mã OTP của bạn nhập không đúng");
        });
    };

    return {
      email,
      otp,
      message,
      backgroundStyle,
      sendOTP,
      verifyOTP,
      userData,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/changemail.scss";
</style>
