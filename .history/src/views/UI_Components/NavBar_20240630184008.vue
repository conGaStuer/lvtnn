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
      <ul class="nav-links" v-if="isSearch">
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
                <router-link
                  v-for="category in categories"
                  :key="category.DanhMuc"
                  :to="{ name: 'Category', params: { name: category.DanhMuc } }"
                >
                  {{ category.DanhMuc }}
                </router-link>
              </div>
              <div class="column">
                <h3>SÁCH VĂN PHÒNG</h3>
                <router-link
                  v-for="category in categories"
                  :key="category.DanhMuc"
                  :to="{ name: 'Category', params: { name: category.DanhMuc } }"
                >
                  {{ category.DanhMuc }}
                </router-link>
              </div>
              <div class="column">
                <h3>TRANG</h3>
                <router-link to="/contact">Liên Hệ</router-link>
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
        <li><router-link to="/about">VỀ CHÚNG TÔI</router-link></li>
        <li class="nav-item" @mouseover="showPage">
          <a href="#">TRANG <i class="fas fa-chevron-down"></i></a>
          <ul class="dropdown" @mouseleave="hidePage" v-if="pageVisible">
            <li><router-link to="/about">Về chúng tôi</router-link></li>
            <li><router-link to="/contact">Liên Hệ</router-link></li>
            <li><a href="#">Chính Sách Bảo Mật</a></li>
          </ul>
        </li>
        <router-link to="/contact">LIÊN HỆ</router-link>
      </ul>
      <div v-else>
        <form @submit.prevent="searchProduct" class="searchForm">
          <input
            type="text"
            name=""
            id=""
            placeholder="Nhập tên sách, thể loại cần tìm kiếm..."
            v-model="searchKeyWord"
            @keyup.enter="searchProduct"
          />
          <i class="pi pi-times-circle" @click="showSearchBar"> </i>
        </form>
      </div>
      <div class="cart-search">
        <a class="wishlist"
          ><i class="fa-solid fa-user" @mouseover="showUserDropdown"></i
        ></a>
        <transition name="fade">
          <div
            class="dropdown1"
            v-if="userDropdownVisible"
            @mouseleave="hideUserDropdown"
          >
            <div>
              <a
                >Xin chào,
                {{ currentUser ? currentUser.taikhoan : "Khách hàng" }}!</a
              >
            </div>

            <div><router-link to="/profile">Hồ sơ</router-link></div>
            <div>
              <a style="cursor: pointer" @click="handleLogout">{{
                currentUser.maND ? "Đăng xuất" : "Đăng nhập"
              }}</a>
            </div>
            <div>
              <router-link to="/order" v-if="currentUser.maVaiTro === '1'"
                >Đơn mua</router-link
              >
            </div>
          </div>
        </transition>
        <router-link
          to="/cart"
          class="cart"
          @click="showSideCart"
          v-if="currentUser.maVaiTro === '1'"
        >
          <i class="fas fa-shopping-cart"></i>
          <span class="cart-count">{{ cartlength ? cartlength : 0 }} </span>
        </router-link>
        <a class="search" @click="showSearchBar"
          ><i class="fas fa-search"></i
        ></a>
      </div>
    </header>
  </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
export default {
  setup() {
    const showCart = ref(false);

    const closeCart = () => {
      showCart.value = false;
    };
    const showSideCart = () => {
      showCart.value = true;
    };
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
    const currentUser = JSON.parse(localStorage.getItem("currentUser")) || {};
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
      fetchCategories();
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getCart.php?userId=${userId}`
        )
        .then((res) => {
          if (res.data) {
            cartItems.value = res.data;
          } else {
            console.log("Khong co san pham");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    });
    const isSearch = ref(true);
    const showSearchBar = () => {
      isSearch.value = !isSearch.value;
    };
    const searchKeyWord = ref("");
    const searchProduct = () => {
      router.push({
        name: "search",
        params: { id: searchKeyWord.value },
      });
      console.log(searchKeyWord.value);
    };
    const categories = ref([]);
    const fetchCategories = () => {
      axios
        .get("http://localhost/LVTN/book-store/src/api/get5cate1.php")
        .then((response) => {
          categories.value = response.data;
        })
        .catch((error) => {
          console.error("Error fetching categories:", error);
        });
    };
    const userId = currentUser.maND || "";
    const cartItems = ref([]);

    const cartlength = computed(() => {
      return cartItems.value.length;
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
      showCart,
      closeCart,
      showSideCart,
      isSearch,
      showSearchBar,
      searchProduct,
      searchKeyWord,
      categories,
      cartlength,
      cartItems,
      userId,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/navbar.scss";
.overlay {
  width: 100%;
  height: 100vh;
  position: fixed;
  top: 0;
  z-index: 999;
  background-color: rgba(59, 57, 57, 0.5);

  .cart-side {
    width: 35%;
    height: 100vh;
    background-color: white;
    position: fixed;
    right: 0;
    z-index: 99;
    top: 0;
    display: flex;
    flex-direction: column;

    .shopping {
      width: 92%;
      height: 50px;
      margin: 0 auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #36363d;
      border-bottom: 0.5px solid #9d9da1;

      span {
        font-size: 18px;
        font-weight: bold;
      }

      i {
        cursor: pointer;
      }
    }

    .product-list {
      width: 90%;
      margin: 20px auto;
      height: 400px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      overflow-y: auto;
      overflow-x: hidden;
      &::-webkit-scrollbar {
        width: 8px;
      }

      /* Thiết lập nút cuộn */
      &::-webkit-scrollbar-thumb {
        background-color: #888;
        border-radius: 4px;
      }

      /* Hiệu ứng hover cho nút cuộn */
      &::-webkit-scrollbar-thumb:hover {
        background-color: #555;
      }
      .product {
        width: 100%;
        height: 100px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-decoration: none;
        .product-image {
          width: 80px;
          height: 80px;
          background-size: cover;
        }

        .product-info {
          width: 60%;
          display: flex;
          flex-direction: column;
          justify-content: center;
          gap: 5px;
          color: #565a61;
          .product-name {
            color: #36363d;
            font-weight: bold;
          }
        }

        i {
          font-size: 20px;
          cursor: pointer;
          color: #9d9da1;
          position: relative;
          right: 10px;
        }
      }
    }

    .product-prices {
      border-top: 0.5px solid #9d9da1;
      border-bottom: 0.5px solid #9d9da1;
      height: 50px;
      width: 90%;
      margin: 0 auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 10px;

      span {
        font-size: 17px;
        font-weight: bold;
      }

      h5 {
        font-size: 17px;
      }
    }

    .btn {
      width: 90%;
      margin: 20px auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;

      button {
        width: 100%;
        height: 50px;
        border-radius: 8px;
        border: 1px solid #5f4ecb;
        color: #5f4ecb;
        font-family: "EB Garamond", serif;
        font-size: 17.5px;
        cursor: pointer;
        transition: all 0.4s ease-in-out;
        a {
          text-decoration: none;
          color: #5f4ecb;
        }
        &:hover {
          background-color: #5f4ecb;
          a {
            color: white;
          }
        }
      }
    }
  }
}
</style>
