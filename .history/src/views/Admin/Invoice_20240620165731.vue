<template>
  <div class="invoice-container">
    <h1>Tổng hợp hóa đơn</h1>
    <a-row :gutter="16" class="filters">
      <a-col :span="4">
        <a-select
          v-model="filterType"
          style="width: 100%"
          @change="fetchInvoices"
        >
          <a-select-option value="all">Tất cả</a-select-option>
          <a-select-option value="day">Ngày</a-select-option>
          <a-select-option value="week">Tuần</a-select-option>
          <a-select-option value="month">Tháng</a-select-option>
        </a-select>
      </a-col>
      <a-col :span="8" v-if="filterType === 'day'">
        <a-date-picker
          v-model="filterDate"
          style="width: 100%"
          @change="fetchInvoices"
        />
      </a-col>
      <a-col :span="8" v-else-if="filterType === 'week'">
        <a-week-picker
          v-model="filterWeek"
          style="width: 100%"
          @change="fetchInvoices"
        />
      </a-col>
      <a-col :span="8" v-else-if="filterType === 'month'">
        <a-month-picker
          v-model="filterMonth"
          style="width: 100%"
          @change="fetchInvoices"
        />
      </a-col>
    </a-row>
    <a-spin :spinning="loading" size="large">
      <a-list
        v-if="invoices.length > 0"
        :item-layout="'vertical'"
        :dataSource="invoices"
        class="invoice-list"
        :renderItem="(item) => renderItem(item)"
      />
      <a-empty v-else description="Không có hóa đơn nào" />
    </a-spin>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { message, Spin, List, Empty, Select, DatePicker, Col, Row } from "ant-design-vue";

const { Option } = Select;
const { MonthPicker, WeekPicker, RangePicker } = DatePicker;

const invoices = ref([]);
const filterType = ref("all");
const filterDate = ref("");
const filterWeek = ref("");
const filterMonth = ref("");
const loading = ref(false);

const fetchInvoices = () => {
  loading.value = true;
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
      message.error("Lỗi khi tải danh sách hóa đơn.");
    })
    .finally(() => {
      loading.value = false;
    });
};

const renderItem = (item) => {
  return (
    <List.Item key={item.MaDon} class="invoice-item">
      <a-card title={`Mã đơn: ${item.MaDon}`} style="width: 100%;">
        <p><strong>Tên người dùng:</strong> {item.TenNguoiDung}</p>
        <p><strong>Mã người dùng:</strong> {item.MaND}</p>
        <p><strong>Số điện thoại:</strong> {item.SDT}</p>
        <p><strong>Ngày đặt:</strong> {item.NgayDat}</p>
        <p><strong>Tổng tiền:</strong> {item.TongTien}</p>
        <a :href="`http://localhost/LVTN/book-store/src/api/printInvoice.php?maDon=${item.MaDon}`"
          target="_blank">In hóa đơn
        </a>
      </a-card>
    </List.Item>
  );
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
  max-height: 600px;
  overflow-y: auto;
}

.invoice-item {
  margin-bottom: 16px;
}

a-spin {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}
</style>
