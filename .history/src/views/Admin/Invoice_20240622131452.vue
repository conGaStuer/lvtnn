<template>
  <div class="invoice-container">
    <h1>Tổng hợp hóa đơn</h1>
    <div class="filters">
      <a-select
        v-model="filterType"
        style="width: 120px"
        @change="fetchInvoices"
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
          format="DD/MM/YYYY"
          @change="fetchInvoices"
        />
        <a-week-picker
          v-if="filterType === 'week'"
          v-model:value="filterWeek"
          format="YYYY-wo"
          @change="fetchInvoices"
        />
        <a-month-picker
          v-if="filterType === 'month'"
          v-model:value="filterMonth"
          format="MM/YYYY"
          @change="fetchInvoices"
        />
      </a-space>
    </div>
    <a-table
      :columns="columns"
      :data-source="invoices"
      :pagination="pagination"
      class="invoice-list"
      :row-key="(record) => record.MaDon"
    >
      <template #title>Chi tiết hóa đơn</template>
      <template #expanded-rowRender="{ record }">
        <a-card>
          <p><strong>Mã đơn:</strong> {{ record.MaDon }}</p>
          <p><strong>Tên người dùng:</strong> {{ record.TenNguoiDung }}</p>
          <p><strong>Mã người dùng:</strong> {{ record.MaND }}</p>
          <p><strong>Số điện thoại:</strong> {{ record.SDT }}</p>
          <p><strong>Ngày đặt:</strong> {{ record.NgayDat }}</p>
          <p><strong>Tổng tiền:</strong> {{ record.TongTien }}</p>
          <p><strong>Mã sách:</strong> {{ record.MaSach }}</p>
          <p><strong>Tên sách:</strong> {{ record.TenSach }}</p>
          <a-button
            :href="`http://localhost/LVTN/book-store/src/api/printInvoice.php?maDon=${record.MaDon}`"
            target="_blank"
          >
            In hóa đơn
          </a-button>
        </a-card>
      </template>
    </a-table>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import {
  Table,
  Select,
  DatePicker,
  Space,
  Card,
  Button,
  message,
} from "ant-design-vue";

const { Option } = Select;
const { RangePicker, WeekPicker, MonthPicker } = DatePicker;

const invoices = ref([]);
const filterType = ref("all");
const filterDate = ref("");
const filterWeek = ref("");
const filterMonth = ref("");

const columns = ref([
  { title: "Mã đơn", dataIndex: "MaDon", key: "MaDon" },
  { title: "Tên người dùng", dataIndex: "TenNguoiDung", key: "TenNguoiDung" },
  { title: "Mã người dùng", dataIndex: "MaND", key: "MaND" },
  { title: "Số điện thoại", dataIndex: "SDT", key: "SDT" },
  { title: "Ngày đặt", dataIndex: "NgayDat", key: "NgayDat" },
  { title: "Tổng tiền", dataIndex: "TongTien", key: "TongTien" },
  { title: "Mã sách", dataIndex: "MaSach", key: "MaSach" },
  { title: "Tên sách", dataIndex: "TenSach", key: "TenSach" },
  {
    title: "Thao tác",
    dataIndex: "action",
    key: "action",
    slots: { customRender: "expanded-rowRender" },
  },
]);

const pagination = ref({
  current: 1,
  pageSize: 1, // Display one invoice per page
  showSizeChanger: true,
  pageSizeOptions: ["1", "10", "20", "50"],
  onChange: (page, pageSize) => {
    pagination.value.current = page;
  },
});

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
      pagination.value.total = response.data.length; // Update total number of invoices for pagination
    })
    .catch((error) => {
      console.error("Error fetching invoices:", error);
      message.error("Đã có lỗi xảy ra khi tải dữ liệu hóa đơn");
    });
};

const handleFilterChange = () => {
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
  fetchInvoices();
});
</script>

<style scoped>
.invoice-container {
  position: relative;
  top: -50px;
  padding: 20px;
  height: 60vh;
}

.filters {
  margin-bottom: 20px;
  position: relative;
  left: 1000px;
}

.invoice-list {
  height: 60vh;
  margin-top: 20px;
  position: relative;
  top: -50px;
}

.action-btn {
  margin-right: 8px;
}
</style>
