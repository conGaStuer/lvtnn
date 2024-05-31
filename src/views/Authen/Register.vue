<template>
  <div class="over">
    <div class="container">
      <div class="login-form">
        <div class="left-side">
          <Transition
            names="slide"
            appear
            @before-enter="beforeupDown"
            @enter="upDown"
          >
            <img
              src="@/assets/images/left-side-person.png"
              alt=""
              width="50px"
              class="img-per"
            />
          </Transition>
          <Transition
            names="slide"
            appear
            @before-enter="beforeEnter1"
            @enter="enter1"
          >
            <img
              src="@/assets/images/left-side-book.png"
              alt=""
              width="50px"
              class="img-book"
            />
          </Transition>
          <Transition
            names="slide"
            appear
            @before-enter="beforedownUp"
            @enter="downUp"
          >
            <img
              src="@/assets/images/left-side-book1.png"
              alt=""
              width="50px"
              class="img-book1"
          /></Transition>
          <Transition
            names="slide"
            appear
            @before-enter="beforerightLeft"
            @enter="rightLeft"
          >
            <img
              src="@/assets/images/left-side-book2.png"
              alt=""
              width="50px"
              class="img-book2"
          /></Transition>
        </div>
        <div class="right-side">
          <div class="form-login">
            <h2>Xin chào!</h2>
            <h4>Chào mừng bạn đến với cửa hàng Bookapee</h4>
            <form @submit.prevent="handleRegister">
              <input
                type="text"
                name=""
                id=""
                placeholder="Tên đăng nhập"
                v-model="username"
                @input="handleUsername"
                required
              />
              <input
                type="password"
                name=""
                id=""
                placeholder="Mật khẩu"
                v-model="password"
                required
                @input="handlePassword"
              />
              <input
                type="password"
                name=""
                id=""
                placeholder="Xác nhận Mật khẩu"
                @input="checkPasswords"
                v-model="confirmPassword"
                required
              />
              <span v-if="passwordsMatch" class="checkpass">
                &check; Mật khẩu khớp</span
              >
              <span v-else class="checkpass1">Mật khẩu không khớp</span>
              <div v-if="passwordLengthValid" class="checkpass22">
                &check; Mật khẩu tối thiểu 8 kí tự
              </div>
              <div v-else class="checkpass2">Mật khẩu tối thiểu 8 kí tự</div>
              <div v-if="passwordSpecialCharValid" class="checkpass33">
                &check; Mật khẩu phải có ít nhất 1 kí tự đặc biệt
              </div>

              <div v-else class="checkpass3">
                Mật khẩu phải có ít nhất 1 kí tự đặc biệt
              </div>

              <div class="missing">
                <router-link to="/newpass">Quên mật khẩu</router-link>
              </div>
              <button type="submit">Đăng kí</button>
            </form>
            <span class="hoac">Hoặc</span>
            <router-link to="/login">Đăng nhập</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import gsap from "gsap";
import { ref, computed } from "vue";
import { useRouter } from "vue-router";

export default {
  setup() {
    const beforeEnter1 = (el) => {
      el.style.transform = "translateX(-100px)";
      el.style.opacity = "0";
    };
    const enter1 = (el) => {
      gsap.to(el, {
        duration: 3.5,
        x: 0,
        opacity: 1,
        ease: "bounce.out",
      });
    };
    const beforeupDown = (el) => {
      el.style.transform = "translateY(-100px)";
      el.style.opacity = "0";
    };
    const upDown = (el) => {
      gsap.to(el, {
        duration: 3.5,
        y: 0,
        opacity: 1,
        ease: "elastic.out",
      });
    };
    const beforedownUp = (el) => {
      el.style.transform = "translateY(100px)";
      el.style.opacity = "0";
    };
    const downUp = (el) => {
      gsap.to(el, {
        duration: 3.5,
        y: 0,
        opacity: 1,
        ease: "elastic.out",
      });
    };
    const beforerightLeft = (el) => {
      el.style.transform = "translateX(100px)";
      el.style.opacity = "0";
    };
    const rightLeft = (el) => {
      gsap.to(el, {
        duration: 3.5,
        x: 0,
        opacity: 1,
        ease: "bounce.out",
      });
    };

    const username = ref("");
    const password = ref("");
    const confirmPassword = ref("");
    const passwordsMatch = ref(false);
    let specialCharacterRegex = new RegExp('[!@#$%^&*(),.?":{}|<>]');

    const checkPasswords = () => {
      passwordsMatch.value = password.value === confirmPassword.value;
    };
    const handleUsername = () => {
      if (username.value.length >= 15) {
        alert("Tên đăng nhập không được quá 15 kí tự");
        username.value = "";
      } else if (specialCharacterRegex.test(username.value)) {
        alert("Tên đăng nhập không được chứa ký tự đặc biệt");
        username.value = "";
      }
    };

    const passwordLengthValid = computed(() => password.value.length >= 8);
    const passwordSpecialCharValid = computed(() =>
      /[!@#$%^&*(),.?":{}|<>]/.test(password.value)
    );

    const router = useRouter();
    const handleRegister = () => {
      if (passwordLengthValid.value || passwordSpecialCharValid.value) {
        axios
          .post("http://localhost/LVTN/book-store/src/api/register.php", {
            username: username.value,
            password: password.value,
          })
          .then((response) => {
            if (response.data.error) {
              alert("Trùng tên đăng nhập. Vui lòng chọn tên đăng nhập khác!");
            } else {
              alert("Đăng kí thành công!");
              localStorage.setItem(
                "currentUser",
                JSON.stringify(response.data)
              );
              router.push("/profile");
            }
          })
          .catch((err) => {
            console.log("Error", err);
          });
      } else {
        alert("Vui lòng nhập mật khẩu đúng với yêu cầu");
      }
    };
    return {
      beforeEnter1,
      enter1,
      beforedownUp,
      beforerightLeft,
      upDown,
      rightLeft,
      beforeupDown,
      downUp,
      username,
      password,
      confirmPassword,
      passwordsMatch,
      checkPasswords,
      handleRegister,
      handleUsername,
      passwordLengthValid,
      passwordSpecialCharValid,
      specialCharacterRegex,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/register.scss";
</style>
