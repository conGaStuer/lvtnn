<template>
  {{ authorName }}
  <div v-if="books.length">
    <div v-for="book in books" :key="book.MaSach">
      {{ book.TacGia }}
    </div>
  </div>
  <div v-else>
    <p>No books found for this author.</p>
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
    const authorName = route.params.name;
    const getAuthorBook = () => {
      const authorId = route.params.id;

      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getAuthor.php?authorId=${authorId}`
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
      getAuthorBook();
    });
    return { getAuthorBook, books, authorName };
  },
};
</script>

<style></style>
