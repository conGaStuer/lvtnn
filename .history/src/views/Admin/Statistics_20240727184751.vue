<template>
  <div class="statistics">
    <h1>Thống kê đơn hàng và người dùng</h1>
    <a-select
      v-model:value="filterType"
      style="width: 120px; margin-bottom: 15px"
      @change="handleFilterChange"
    >
      <a-select-option value="all">Tất cả</a-select-option>
      <a-select-option value="date">Date</a-select-option>
      <a-select-option value="week">Week</a-select-option>
      <a-select-option value="month">Month</a-select-option>
      <a-select-option value="year">Year</a-select-option>
    </a-select>
    <template v-if="filterType === 'all'">
      <a-time-picker v-model:value="selectedTime" />
    </template>
    <template v-else-if="filterType === 'date'">
      <a-date-picker
        :picker="filterType"
        v-model:value="selectedDate"
        @change="logDate"
      />
    </template>
    <template v-else-if="filterType === 'week'">
      <a-date-picker
        :picker="filterType"
        v-model:value="selectedWeek"
        @change="logWeek"
      />
    </template>
    <template v-else-if="filterType === 'month'">
      <a-date-picker
        :picker="filterType"
        v-model:value="selectedMonth"
        @change="logMonth"
      />
    </template>
    <div class="stats-container">
      <div class="stat-items">
        <div class="stat-item">
          <div class="stat">Tổng số đơn hàng</div>
          <div class="stats">{{ stats.total_orders }}</div>
        </div>
        <div class="stat-item">
          <h2>Tổng doanh thu</h2>
          <p>{{ stats.total_revenue || currency }}</p>
        </div>
        <div class="stat-item">
          <h2>Tổng số người dùng đã mua hàng</h2>
          <p>{{ stats.total_users }}</p>
        </div>
        <div class="stat-item">
          <h2>Tổng số lượng sách đã bán được</h2>
          <p>{{ stats.total_quan || currency }}</p>
        </div>
      </div>

      <div class="topPro">
        <h2>Sản phẩm bán chạy</h2>
        <ul>
          <li v-for="product in topProducts" :key="product.id">
            {{ product.name }} - {{ product.unitsSold }} sản phẩm -
            {{ product.revenue }} đồng
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { Select, DatePicker, Space, message } from "ant-design-vue";
import moment from "moment";
import dayjs, { Dayjs } from "dayjs";

const { Option } = Select;
const { RangePicker, WeekPicker, MonthPicker } = DatePicker;

const stats = ref({
  total_orders: 0,
  total_revenue: 0,
});

const filterType = ref("all");
const selectedTime = ref(null);
const selectedDate = ref(null);
const selectedWeek = ref(null);
const selectedMonth = ref(null);

const logDate = (date) => {
  console.log("Selected Date:", date ? date.format("YYYY-MM-DD") : null);
  fetchInvoices();
};
const logWeek = (week) => {
  console.log("Selected Week:", week ? dayjs(week).week() : null);
  fetchInvoices();
};
const logMonth = (month) => {
  console.log("Selected Month:", month ? dayjs(month).month() : null);
  fetchInvoices();
};
const topProducts = ref([]);
const fetchTopProducts = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getTopProducts.php")
    .then((response) => {
      topProducts.value = response.data;
      console.log(topProducts.value);
    })
    .catch((error) => {
      console.error("Error fetching top products:", error);
      message.error("Đã có lỗi xảy ra khi tải dữ liệu sản phẩm bán chạy");
    });
};
onMounted(() => {
  fetchTopProducts();
});
const fetchInvoices = () => {
  let filterValue = null;
  if (filterType.value === "date" && selectedDate.value) {
    filterValue = selectedDate.value.format("YYYY-MM-DD");
  } else if (filterType.value === "week" && selectedWeek.value) {
    filterValue = dayjs(selectedWeek.value).week();
  } else if (filterType.value === "month" && selectedMonth.value) {
    filterValue = dayjs(selectedMonth.value).month() + 1;
  }
  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/postStatistics.php", {
      filterType: filterType.value,
      filterValue: filterValue,
    })
    .then((response) => {
      stats.value = response.data;
      filterValue = "";
    })
    .catch((error) => {
      console.error("Error fetching invoices:", error);
      message.error("Đã có lỗi xảy ra khi tải dữ liệu hóa đơn");
    });
};

const handleFilterChange = () => {
  selectedDate.value = null;
  // filterWeek.value = null;
  // filterMonth.value = null;
  fetchInvoices();
};

const handleDateChange = () => {
  fetchInvoices();
};

const handleWeekFilterChange = () => {
  fetchInvoices();
};

const handleMonthFilterChange = () => {
  fetchInvoices();
};

onMounted(() => {
  fetchStatistics();
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
}

.stats-container {
  display: flex;
  flex-direction: column;
  gap: 20px;
}
.stat-items {
  display: flex;
}
.stat-item {
  border: 1px solid #ddd;
  border-radius: 5px;
  width: 300px;
  margin-right: 20px;
  height: 150px;
  text-align: center;
}
.stat-item:nth-child(1) {
  background-color: #337ab7;
  border: 1px solid #337ab7;
}
.stat-item:nth-child(2) {
  background-color: #5cb85c;
}
.stat-item:nth-child(3) {
  background-color: #f0ad4e;
}
.stat-item:nth-child(4) {
  background-color: #d9534f;
}
.stat {
  width: 100%;
  height: 70%;
}
.stats {
  width: 100%;
  height: 30%;
  background-color: white;
  font-size: 15px;
}
.stat-item h2 {
  margin-bottom: 10px;
}

.stat-item {
  font-size: 20px;
  font-weight: bold;
}
</style>
