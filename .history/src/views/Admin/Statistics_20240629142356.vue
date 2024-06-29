<template>
  <div class="statistics">
    <h1>Thống kê đơn hàng và người dùng</h1>
    <a-select
      v-model:value="filterType"
      style="width: 120px"
      @change="handleFilterChange"
    >
      <a-select-option value="all">Tất cả</a-select-option>
      <a-select-option value="day">Ngày</a-select-option>
      <a-select-option value="week">Tuần</a-select-option>
      <a-select-option value="month">Tháng</a-select-option>
    </a-select>
    <template v-if="filterType === 'all'">
      <a-time-picker />
    </template>
    <template v-else>
      <a-date-picker :picker="filterType" />
    </template>
    <div class="stats-container">
      <div class="stat-item">
        <h2>Tổng số đơn hàng</h2>
        <p>{{ stats.total_orders }}</p>
      </div>
      <div class="stat-item">
        <h2>Tổng doanh thu (Trước thanh toán)</h2>
        <p>{{ stats.total_revenue || currency }}</p>
      </div>
    </div>
    <!-- Debugging: Display selected filter type and value -->
    <div class="debug-info">
      <h3>Debug Info</h3>
      <p>Filter Type: {{ filterType }}</p>
      <p>Filter Value: {{ formattedFilterValue }}</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from "vue";
import axios from "axios";
import { Select, DatePicker, Space, message } from "ant-design-vue";
import moment from "moment";

const { Option } = Select;
const { RangePicker, WeekPicker, MonthPicker } = DatePicker;

const stats = ref({
  total_orders: 0,
  total_revenue: 0,
});

const filterType = ref("all");
const filterDate = ref(null);
const filterWeek = ref(null);
const filterMonth = ref(null);

const formattedFilterValue = computed(() => {
  if (filterType.value === "day" && filterDate.value) {
    return moment(filterDate.value).format("YYYY-MM-DD");
  } else if (filterType.value === "week" && filterWeek.value) {
    return moment(filterWeek.value).startOf("week").format("YYYY-MM-DD");
  } else if (filterType.value === "month" && filterMonth.value) {
    return moment(filterMonth.value).format("YYYY-MM");
  }
  return null;
});

const fetchInvoices = () => {
  console.log(
    "Fetching invoices with filter value:",
    formattedFilterValue.value
  );
  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/postStatistics.php", {
      filterType: filterType.value,
      filterValue: formattedFilterValue.value,
    })
    .then((response) => {
      stats.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching invoices:", error);
      message.error("Đã có lỗi xảy ra khi tải dữ liệu hóa đơn");
    });
};

const handleFilterChange = () => {
  filterDate.value = null;
  filterWeek.value = null;
  filterMonth.value = null;
  fetchInvoices();
};

const handleDateFilterChange = (date) => {
  filterDate.value = date;
  fetchInvoices();
};

const handleWeekFilterChange = (date) => {
  filterWeek.value = date;
  fetchInvoices();
};

const handleMonthFilterChange = (date) => {
  filterMonth.value = date;
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

// Watch for changes in the filter values to automatically update statistics
watch([filterDate, filterWeek, filterMonth], () => {
  fetchInvoices();
});
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

.stat-item {
  padding: 20px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.stat-item h2 {
  margin-bottom: 10px;
}

.stat-item p {
  font-size: 24px;
  font-weight: bold;
}

.debug-info {
  margin-top: 20px;
  border-top: 1px solid #ddd;
  padding-top: 20px;
}
</style>
