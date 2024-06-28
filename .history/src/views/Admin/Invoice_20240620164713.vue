<template>
  <div class="invoice-container">
    <h1>Tổng hợp hóa đơn</h1>
    <div class="filters">
      <label for="filter">Lọc theo:</label>
      <select id="filter" v-model="filterType" @change="fetchInvoices">
        <option value="all">Tất cả</option>
        <option value="day">Ngày</option>
        <option value="week">Tuần</option>
        <option value="month">Tháng</option>
      </select>
      <input
        v-if="filterType === 'day'"
        type="date"
        v-model="filterDate"
        @change="fetchInvoices"
      />
      <input
        v-if="filterType === 'week'"
        type="week"
        v-model="filterWeek"
        @change="fetchInvoices"
      />
      <input
        v-if="filterType === 'month'"
        type="month"
        v-model="filterMonth"
        @change="fetchInvoices"
      />
    </div>
    <div v-if="invoices.length" class="invoice-list">
      <div
        v-for="invoice in invoices"
        :key="invoice.MaDon"
        class="invoice-item"
      >
        <p>Mã đơn: {{ invoice.MaDon }}</p>
        <p>Ngày đặt: {{ invoice.NgayDat }}</p>
        <p>Tổng tiền: {{ invoice.TongTien }}</p>
        <a
          :href="`http://localhost/LVTN/book-store/src/api/printInvoice.php?maDon=${invoice.MaDon}`"
          target="_blank"
          >In hóa đơn</a
        >
      </div>
    </div>
    <div v-else>
      <p>Không có hóa đơn nào</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const invoices = ref([]);
const filterType = ref("all");
const filterDate = ref("");
const filterWeek = ref("");
const filterMonth = ref("");

const fetchInvoices = () => {
  let filterValue;
  if (filterType.value === "day") {
    filterValue = filterDate.value;
  } else if (filterType.value === "week") {
    filterValue = filterWeek.value;
  } else if (filterType.value === "month") {
    filterValue = filterMonth.value;
  }

  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/getInvoices.php", {
      filterType: filterType.value,
      filterValue: filterValue,
    })
    .then((response) => {
      invoices.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching invoices:", error);
    });
};

onMounted(() => {
  fetchInvoices();
});
</script>

<style scoped>
.invoice-container {
  padding: 20px;
}

.filters {
  margin-bottom: 20px;
}

.invoice-list {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.invoice-item {
  border: 1px solid #ddd;
  padding: 10px;
}
</style>
