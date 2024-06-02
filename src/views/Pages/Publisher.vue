<template>
  {{ publisherName }}
  <div v-if="books.length">
    <div v-for="book in books" :key="book.MaSach">
      {{ book.NhaXuatBan }}
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
    return { getPublisher, books, publisherName };
  },
};
</script>

<style></style>
