<template>
  <div>
    <NavBar></NavBar>
    <div class="container">
      <div class="features">
        <div class="feature-co">
          <button>CAM KẾT</button>
          <h2>Để Khách Hàng Của Chúng Tôi</h2>
          <p>Đắm chìm trong kiến thức văn học đầy sáng tạo</p>
          <div class="feature-list">
            <div
              class="feature"
              v-for="(feature, index) in features"
              :key="index"
            >
              <i :class="feature.icon"></i>

              <div class="text">
                <h3>{{ feature.title }}</h3>
                <span>{{ feature.description }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="shop-by-category">
        <button>BỘ SƯU TẬP</button>
        <h2>Mua Sắm Theo Danh Mục</h2>
        <p>Chọn những cuốn sách yêu thích của bạn</p>

        <div class="categories">
          <div
            class="category"
            v-for="(category, index) in categories"
            :key="index"
            :style="{ backgroundImage: `url(${category.image})` }"
            @mouseover="showCategoryText(index)"
            @mouseleave="hideCategoryText(index)"
          >
            <div
              class="cate-text"
              :class="{ visible: isCategoryTextVisible[index] }"
            >
              <span class="cate_name">{{ category.name }}</span>
              <span class="browse">--------- XEM NGAY</span>
            </div>
          </div>
        </div>
      </div>
      <!-- New Arrivals Section -->
      <NewArrives></NewArrives>
      <Testimonials></Testimonials>
      <!-- Shop By Category Section -->

      <!-- Features Section -->
    </div>
    <FeaturedBooks></FeaturedBooks>
    <Footer></Footer>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import NavBar from "@/views/UI_Components/NavBar.vue";
import { PrimeIcons } from "primevue/api";
import Footer from "@/views/UI_Components/Footer.vue";
import FeaturedBooks from "@/views/UI_Components/FeaturedBooks.vue";
import Testimonials from "@/views/UI_Components/Testimonials.vue";
import NewArrives from "@/views/UI_Components/NewArrives.vue";

export default {
  components: {
    NavBar,
    PrimeIcons,
    Footer,
    FeaturedBooks,
    Testimonials,
    NewArrives,
  },
  setup() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    const logoutStatus = ref(null);
    const router = useRouter();

    const categories = ref([
      {
        image: require("@/assets/images/cate1.webp"),
        name: "Kiến trúc",
      },
      {
        image: require("@/assets/images/cate2.jpg"),
        name: "Văn học",
      },
      {
        image: require("@/assets/images/cate3.jpg"),
        name: "Động vật hoang dã",
      },
      {
        image: require("@/assets/images/cate4.jpeg"),
        name: "Kinh doanh",
      },
      {
        image: require("@/assets/images/cate5.jpg"),
        name: "Nấu ăn",
      },
    ]);

    const isCategoryTextVisible = ref(
      new Array(categories.value.length).fill(false)
    );
    const showCategoryText = (index) => {
      isCategoryTextVisible.value[index] = true;
    };

    const hideCategoryText = (index) => {
      isCategoryTextVisible.value[index] = false;
    };
    const features = ref([
      {
        icon: "pi pi-book",
        title: "Hơn 12 Triệu Sách Trực Tuyến",
        description:
          "Mang đến các chiến lược sinh tồn đôi bên cùng có lợi để đảm bảo sự thống trị chủ động.",
      },
      {
        icon: "pi pi-map-marker",
        title: "Miễn Phí Vận Chuyển Toàn Cầu",
        description:
          "Nội dung do người dùng tạo ra trong thời gian thực sẽ có nhiều điểm tiếp xúc cho việc thuê ngoài.",
      },
      {
        icon: "pi pi-chevron-circle-left",
        title: "Hoàn Trả Miễn Phí Trong 30 Ngày",
        description:
          "Tận dụng những cơ hội dễ dàng để xác định hoạt động mang lại giá trị tổng thể để thử nghiệm.",
      },
      {
        icon: "pi pi-headphones",
        title: "Hỗ Trợ Đẳng Cấp Thế Giới",
        description:
          "Vượt qua khoảng cách số với các lần nhấp bổ sung từ DevOps.",
      },
    ]);

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

    return {
      currentUser,
      handleLogout,
      logoutStatus,
      categories,
      features,
      isCategoryTextVisible,
      showCategoryText,
      hideCategoryText,
    };
  },
};
</script>
<style lang="scss" scoped>
@import "@/assets/styles/home.scss";
</style>
