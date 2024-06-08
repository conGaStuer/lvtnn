<template>
  <NavBar></NavBar>
  <Route></Route>
  <div class="author1">Không có kết quả phù hợp với kết quả tìm kiếm trên.</div>
  <Footer></Footer>
</template>

<script>
import axios from "axios";
import { onMounted, ref, computed, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import NavBar from "../UI_Components/NavBar.vue";
import Route from "../UI_Components/Route.vue";
import Footer from "../UI_Components/Footer.vue";
import router from "@/router";
export default {
  components: {
    NavBar,
    Route,
    Footer,
  },
  setup() {
    const route = useRoute();
    const books = ref();
    const keyword = ref(route.params.id);
    const backgroundStyle = computed(() => ({
      backgroundImage: `url(${require("@/assets/images/kimdong.jpg")})`,
    }));
    const haveBook = ref(false);
    const notFound = ref(true);
    const fetchBooks = () => {
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/searchProduct.php?keyword=${keyword.value}`
        )
        .then((res) => {
          if (res.data && res.data.length > 0) {
            books.value = res.data;
            haveBook.value = true;
          }
          if (res.data === "Deo co du lieu") {
            router.replace("/notfound");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    };
    onMounted(fetchBooks);
    watch(
      () => route.params.id,
      (newId) => {
        keyword.value = newId;
        fetchBooks();
      }
    );

    return {
      books,
      route,
      keyword,
      backgroundStyle,
      haveBook,
      notFound,
    };
  },
};
</script>

<style lang="scss" scoped>
@import "@/assets/styles/filt.scss";
.author1 {
  text-align: center;
  font-size: 1.2em;
  margin: 20px 0;
}
</style>
