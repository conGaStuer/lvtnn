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
            <form @submit.prevent="loginUser">
              <input
                type="text"
                name=""
                id=""
                placeholder="Tên đăng nhập"
                v-model="username"
              />
              <input
                type="password"
                name=""
                id=""
                placeholder="Mật khẩu"
                v-model="password"
              />
              <router-link to="/newpass"
                ><span>Quên mật khẩu</span></router-link
              >
              <button type="submit">Đăng nhập</button>
            </form>
            <span class="hoac">Hoặc</span>
            <router-link to="/register">Đăng kí tài khoản</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import gsap from "gsap";
import { ref } from "vue";
import axios from "axios";
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
    const router = useRouter();
    const loginUser = async () => {
      try {
        const response = await axios.post(
          "http://localhost/LVTN/book-store/src/api/login.php",
          {
            username: username.value,
            password: password.value,
          }
        );
        if (response.data.error) {
          alert("Sai tên đăng nhập hoặc mật khẩu!!");
        } else {
          alert("Đăng nhập thành công!");
          localStorage.setItem("currentUser", JSON.stringify(response.data));
          router.push("/profile");
        }
      } catch (error) {
        console.error(error);
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
      loginUser,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/login.scss";
</style>
