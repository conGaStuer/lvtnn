<template>
  <div class="statistics">
    <h1>Thống kê đơn hàng và người dùng</h1>
    <a-select
      v-model:value="filterType"
      style="width: 120px"
      @change="handleFilterChange"
    >
      <a-select-option value="time">Tất cả</a-select-option>
      <a-select-option value="date">Date</a-select-option>
      <a-select-option value="week">Week</a-select-option>
      <a-select-option value="month">Month</a-select-option>
      <a-select-option value="year">Year</a-select-option>
    </a-select>
    <template v-if="filterType === 'time'">
      <a-time-picker v-model:value="selectedTime" @change="logTime" />
    </template>
    <template v-else>
      <a-date-picker
        :picker="type"
        v-model:value="selectedDate"
        @change="logDate"
      />
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

const filterType = ref("time");
const selectedTime = ref(null);
const selectedDate = ref(null);

const logDate = (date) => {
  console.log("Selected Date:", date ? date.format("YYYY-MM-DD") : null);
};

const fetchInvoices = () => {
  let filterValue = null;
  if (filterType.value === "date" && selectedDate.value) {
    filterValue = moment(selectedDate.value).format("YYYY-MM-DD");
  }
  // } else if (filterType.value === "week" && filterWeek.value) {
  //   filterValue = moment(filterWeek.value).startOf("week").format("YYYY-MM-DD");
  // } else if (filterType.value === "month" && filterMonth.value) {
  //   filterValue = moment(filterMonth.value).format("YYYY-MM");
  // }

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
