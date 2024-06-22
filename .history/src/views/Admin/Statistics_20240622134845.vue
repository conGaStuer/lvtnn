<template>
  <div class="statistics">
    <h1>Thống kê đơn hàng và người dùng</h1>
    <div class="stats-container">
      <div class="stat-item">
        <h2>Tổng số đơn hàng</h2>
        <p>{{ stats.total_orders }}</p>
      </div>
      <div class="stat-item">
        <h2>Tổng số người dùng</h2>
        <p>{{ stats.total_users }}</p>
      </div>
      <div class="stat-item">
        <h2>Tổng doanh thu</h2>
        <p>{{ stats.total_revenue | currency }}</p>
      </div>
      <div class="stat-item">
        <h2>Số lượng sách nhập kho (tháng này)</h2>
        <p>{{ stats.total_books_imported }}</p>
      </div>
      <div class="stat-item">
        <h2>Doanh số từ sách nhập kho (tháng này)</h2>
        <p>{{ stats.total_import_revenue | currency }}</p>
      </div>
    </div>

    <h1>Danh sách sách đã nhập</h1>
    <div class="books-container">
      <div class="book-item" v-for="book in books" :key="book.MaSach">
        <h2>{{ book.TenSach }}</h2>
        <img
          :src="book.HinhAnh"
          alt="Book Cover"
          style="max-width: 100px; max-height: 100px"
        />
        <p>Đơn giá: {{ book.DonGia | currency }}</p>
        <p>Số lượng: {{ book.SoLuong }}</p>
        <p>Nhà xuất bản: {{ book.NhaXuatBan }}</p>
        <p>Danh mục: {{ book.DanhMuc }}</p>
        <p>Tác giả: {{ book.TacGia }}</p>
        <p>Ngôn ngữ: {{ book.NgonNgu }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const stats = ref({
  total_orders: 0,
  total_users: 0,
  total_revenue: 0,
  total_books_imported: 0,
  total_import_revenue: 0,
});

const books = ref([]);

onMounted(() => {
  fetchStatistics();
  fetchBooks();
});

const fetchStatistics = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getStatistics.php")
    .then((response) => {
      stats.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching statistics:", error);
    });
};

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getBooks.php")
    .then((response) => {
      books.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching books:", error);
    });
};
</script>

<script>
export default {
  filters: {
    currency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },
  },
};
</script>

<style scoped>
.statistics {
  padding: 20px;
  height: 300vh;
}

.stats-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.books-container {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
}

.stat-item {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.book-item {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
  width: 300px;
}

.stat-item h2 {
  margin-bottom: 10px;
}

.stat-item p {
  font-size: 24px;
  font-weight: bold;
}
</style>
