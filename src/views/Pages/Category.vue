<template>
  {{ categoryName }}
  <div v-if="books.length">
    <div v-for="book in books" :key="book.MaSach">
      {{ book.DanhMuc }}
    </div>
  </div>
  <div v-else>
    <p>No books found for this category.</p>
  </div>
</template>

<script>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";

export default {
  setup() {
    const books = ref([]);
    const route = useRoute();
    const categoryName = route.params.name;
    const getCategoryBook = () => {
      const categoryId = route.params.id;

      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getCategory.php?categoryId=${categoryId}`
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
      getCategoryBook();
    });
    return { getCategoryBook, books, categoryName };
  },
};
</script>

<style></style>
