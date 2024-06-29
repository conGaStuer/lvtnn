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
    <a-space>
      <a-date-picker
        v-if="filterType === 'day'"
        v-model:value="filterDate"
        @change="handleDateFilterChange"
      />
      <a-week-picker
        v-if="filterType === 'week'"
        v-model:value="filterWeek"
        @change="handleWeekFilterChange"
      />
      <a-month-picker
        v-if="filterType === 'month'"
        v-model:value="filterMonth"
        @change="handleMonthFilterChange"
      />
    </a-space>
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
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
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

const fetchInvoices = () => {
  let filterValue = null;
  if (filterType.value === "day" && filterDate.value) {
    filterValue = moment(filterDate.value).format("YYYY-MM-DD");
    filterDate = filterValue;
  } else if (filterType.value === "week" && filterWeek.value) {
    filterValue = moment(filterWeek.value).startOf("week").format("YYYY-MM-DD");
  } else if (filterType.value === "month" && filterMonth.value) {
    filterValue = moment(filterMonth.value).format("YYYY-MM");
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
  filterDate.value = null;
  filterWeek.value = null;
  filterMonth.value = null;
  fetchInvoices();
};

const handleDateFilterChange = () => {
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
</style>
