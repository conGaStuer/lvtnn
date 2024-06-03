<template>
  <NavBar></NavBar>
  <Route></Route>
  <div class="author1">
    <div class="author-image">
      <div :style="backgroundStyle"></div>
      <h5>{{ publisherName }}</h5>
    </div>
    <div class="author-discrip">
      <p>
        Đây là 1 nhà xuất bản nổi tiếng trên thế giới với chất lượng in ấn cực
        kì xuất sắc và thuộc top đầu những nhà xuất bản phát hành sách trên thế
        giới
      </p>
    </div>
  </div>
  <!-- <div v-if="books.length">
    <div v-for="book in books" :key="book.MaSach">
      {{ book.TacGia }}
    </div>
  </div>
  <div v-else>
    <p>No books found for this author.</p>
  </div> -->
  <div class="wrapper">
    <router-link
      class="book"
      v-for="book in books"
      :key="book.MaSach"
      @mouseover="hover = book.MaSach"
      @mouseleave="hover = null"
      :to="{ name: 'bookDetail', params: { id: book.MaSach } }"
    >
      <div class="image-container">
        <img :src="book.HinhAnh" :alt="book.TenSach" />
        <transition name="fade">
          <div v-if="hover === book.MaSach" class="overlay">
            <div class="icon"><i class="pi pi-eye"></i></div>
            <div class="icon"><i class="pi pi-heart"></i></div>
            <div class="icon"><i class="pi pi-shopping-cart"></i></div>
          </div>
        </transition>
      </div>
      <div class="book-details">
        <span class="category">{{ book.DanhMuc }}</span>
        <h3>{{ book.TenSach }}</h3>
        <p class="author">Nhà xuất bản: {{ book.NhaXuatBan }}</p>
        <p class="price">{{ book.DonGia }} đồng</p>
        <p class="detail">{{ book.ChiTiet }}</p>
        <button><i class="pi pi-shopping-cart"></i></button>
      </div>
    </router-link>
  </div>
  <Footer></Footer>
</template>

<script>
import axios from "axios";
import { onMounted, ref, computed } from "vue";
import { useRoute } from "vue-router";
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";
export default {
  components: {
    NavBar,
    Route,
    Footer,
  },
  setup() {
    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/kimdong.jpg")})`,
    }));
    const books = ref([]);
    const route = useRoute();
    const publisherName = route.params.name;
    const getPublisher = () => {
      const publisherId = route.params.id;

      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getPublisher.php?publisherId=${publisherId}`
        )
        .then((res) => {
          if (res.data) {
            books.value = res.data;
            console.log(books.value);
          } else {
            console.log("Not found data");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };
    onMounted(() => {
      getPublisher();
    });
    return { getPublisher, books, publisherName, backgroundStyle };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/filt.scss";
</style>
