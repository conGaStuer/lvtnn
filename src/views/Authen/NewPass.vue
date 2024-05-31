<template>
  <div class="container">
    <div class="left-side" :style="backgroundStyle"></div>
    <div class="right-side">
      <span class="missing"
        >Đã nhận được mã?

        <router-link to="/login">Đăng nhập</router-link>
      </span>
      <div class="form-fogot">
        <h3>Quên mật khẩu ?</h3>
        <h4>
          Nhập địa chỉ email của bạn bên dưới nếu như bạn đã có tài khoản và đã
          liên kết email với tài khoản của mình
        </h4>
        <h4>
          Vì lý do bảo mật, chúng tôi sẽ không cấp lại mật khẩu của bạn cho đến
          khi bạn nhận được email từ chúng tôi.
        </h4>
        <p>Địa chỉ Email</p>
        <input type="email" v-model="email" /><br />
        <button @click="resetPassword">Gửi Email</button>
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
    const message = ref("");
    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/forgot.png")})`,
    }));
    const resetPassword = () => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/newpass.php", {
          email: email.value,
        })
        .then((response) => {
          message.value = response.data.message;
          alert("Mật khẩu mới đã được gửi qua email của bạn!!");
        })
        .catch((error) => {
          console.error("Error:", error);
          message.value = "Đã xảy ra lỗi.";
        });
    };

    return {
      email,
      message,
      resetPassword,
      backgroundStyle,
    };
  },
};
</script>
<style lang="scss" scoped>
@import "@/assets/styles/newpass.scss";
</style>
