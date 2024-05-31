<template>
  <div class="stick">
    <div class="top-bar">
      <div class="top-bar-contain">
        <b> MIỄN SHIP TRÊN TOÀN QUỐC</b>
        <div class="contact">
          <i class="fa-solid fa-phone"></i><span> LIÊN HỆ</span>
          <i class="fa-regular fa-heart"></i><span> YÊU THÍCH </span>
        </div>
        <div class="social-icons">
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-pinterest"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>
    <header class="header">
      <div class="logo">
        <img src="@/assets/images/logo1.png" alt="BookChoix Logo" />
      </div>
      <ul class="nav-links">
        <li class="nav-item">
          <a href="#">TRANG CHỦ </a>
        </li>
        <li class="nav-item" @mouseover="showMegaMenu">
          <router-link to="/products"
            >SẢN PHẨM <i class="fas fa-chevron-down"></i
          ></router-link>
          <transition name="fade">
            <div
              class="mega-menu"
              v-if="megaMenuVisible"
              @mouseleave="hideMegaMenu"
            >
              <div class="column">
                <h3>SÁCH VĂN PHÒNG</h3>
                <a href="#">Kinh Doanh</a>
                <a href="#">Bách Khoa</a>
                <a href="#">Lối Sống</a>
                <a href="#">Thực Phẩm & Nấu Ăn</a>
                <a href="#">Giả Tưởng</a>
              </div>
              <div class="column">
                <h3>SÁCH HOT</h3>
                <a href="#">Kiến Trúc</a>
                <a href="#">Tiểu Sử</a>
                <a href="#">Âm Nhạc</a>
                <a href="#">Động Vật Hoang Dã</a>
                <a href="#">Nấu Ăn</a>
              </div>
              <div class="column">
                <h3>TRANG</h3>
                <a href="#">Về Chúng Tôi</a>
                <a href="#">Liên Hệ</a>
                <a href="#">Chính Sách Bảo Mật</a>
                <a href="#">Chính Sách Hoàn Tiền Và Đổi Trả</a>
                <a href="#">Điều Khoản Và Điều Kiện</a>
              </div>
              <div class="column">
                <img src="@/assets/images/book.jpg" alt="Logo của BookChoix" />
              </div>
            </div>
          </transition>
        </li>
        <li><a href="#">CỬA HÀNG</a></li>
        <li><a href="#">BLOG</a></li>
        <li class="nav-item" @mouseover="showPage">
          <a href="#">TRANG <i class="fas fa-chevron-down"></i></a>
          <ul class="dropdown" @mouseleave="hidePage" v-if="pageVisible">
            <li><a href="#">Về Chúng Tôi</a></li>
            <li><a href="#">Liên Hệ</a></li>
            <li><a href="#">Chính Sách Bảo Mật</a></li>
          </ul>
        </li>
        <li><a href="#">LIÊN HỆ</a></li>
      </ul>

      <div class="cart-search">
        <a href="#" class="wishlist"
          ><i class="fa-solid fa-user" @mouseover="showUserDropdown"></i
        ></a>
        <transition name="fade">
          <div
            class="dropdown1"
            v-if="userDropdownVisible"
            @mouseleave="hideUserDropdown"
          >
            <div>
              <a href="#"
                >Xin chào,
                {{ currentUser ? currentUser.taikhoan : "Khách hàng" }}!</a
              >
            </div>

            <div><router-link to="/profile" href="#">Hồ sơ</router-link></div>
            <div><a href="#">Đơn mua</a></div>
            <div>
              <a style="cursor: pointer" @click="handleLogout">{{
                currentUser ? "Đăng xuất" : "Đăng nhập"
              }}</a>
            </div>
          </div>
        </transition>
        <a href="#" class="cart">
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-count">0</span>
        </a>
        <a href="#" class="search"><i class="fas fa-search"></i></a>
      </div>
    </header>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
export default {
  setup() {
    // Dùng ref thay cho data()
    const megaMenuVisible = ref(false);
    const userDropdownVisible = ref(false); // Thêm một ref mới cho dropdown người dùng
    const pageVisible = ref(false);

    // Các phương thức cho mega menu
    const showMegaMenu = () => {
      megaMenuVisible.value = true;
    };

    const hideMegaMenu = () => {
      megaMenuVisible.value = false;
    };

    // Các phương thức mới cho dropdown người dùng
    const showUserDropdown = () => {
      userDropdownVisible.value = true;
    };

    const hideUserDropdown = () => {
      userDropdownVisible.value = false;
    };

    const showPage = () => {
      pageVisible.value = true;
    };

    const hidePage = () => {
      pageVisible.value = false;
    };
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    const logoutStatus = ref(null);
    const router = useRouter();

    const handleLogout = async () => {
      try {
        const response = await axios.get(
          "http://localhost/LVTN/book-store/src/api/logout.php"
        );
        if (response) {
          localStorage.removeItem("currentUser");
          logoutStatus.value = true;
          // Chuyển hướng đến trang đăng nhập hoặc trang chính của ứng dụng
          router.push("/login");
        } else {
          logoutStatus.value = false;
          alert("Đã xảy ra lỗi khi đăng xuất");
        }
      } catch (error) {
        console.error("Lỗi:", error);
        logoutStatus.value = false;
        alert("Đã xảy ra lỗi khi đăng xuất");
      }
    };
    const redirectIfLoggedIn = () => {
      if (currentUser) {
        router.replace("/"); // Redirect to the main page if logged in
      }
    };

    onMounted(() => {
      if (router.currentRoute.value.path === "/login") {
        redirectIfLoggedIn();
      }
    });

    // Trả về các biến và phương thức cần thiết cho component
    return {
      megaMenuVisible,
      userDropdownVisible, // Biến ref mới cho dropdown người dùng
      pageVisible,
      showMegaMenu,
      hideMegaMenu,
      showUserDropdown,
      hideUserDropdown,
      showPage,
      hidePage,
      currentUser,
      handleLogout,
      logoutStatus,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/navbar.scss";
</style>
