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

    <template v-if="filterType === 'date'">
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
        <div class="stat-item" @click="showDetails('orders')">
          <div class="stat">Tổng số đơn hàng</div>
          <div class="stats">{{ stats.total_orders }}</div>
        </div>
        <div class="stat-item" @click="showDetails('revenue')">
          <div class="stat">Tổng doanh thu</div>
          <div class="stats">{{ stats.total_revenue || currency }}</div>
        </div>
        <div class="stat-item" @click="showDetails('users')">
          <div class="stat">Tổng số người dùng đã mua hàng</div>
          <div class="stats">{{ stats.total_users }}</div>
        </div>
        <div class="stat-item" @click="showDetails('quantity')">
          <div class="stat">Tổng số lượng sách đã bán được</div>
          <div class="stats">{{ stats.total_quantity || currency }}</div>
        </div>
      </div>

      <div class="topPro">
        <h2>Sản phẩm bán chạy</h2>
        <div class="tops">
          <div class="top" v-for="product in topProducts" :key="product.id">
            <img :src="product.HinhAnh" alt="" />
            <div>
              <b>{{ product.name }}</b>
            </div>
            <div>{{ product.unitsSold }} sản phẩm</div>
            <div>Số lượng còn lại trong kho : {{ product.SoLuong }}</div>
          </div>
        </div>
      </div>
    </div>

    <a-modal
      v-model:visible="isModalVisible"
      :title="modalTitle"
      @ok="handleOk"
      @cancel="handleCancel"
    >
      <div v-if="modalType === 'orders'">
        <ul>
          <li v-for="order in stats.orders" :key="order.madon">
            Mã đơn: <i>{{ order.madon }}</i> - Ngày đặt:
            <i>{{ order.ngaydat }}</i>
          </li>
        </ul>
      </div>
      <div v-else-if="modalType === 'revenue'">
        <ul>
          <li v-for="order1 in stats.revenue_details" :key="order1.madon">
            Mã đơn: <i>{{ order1.madon }}</i> - Ngày đặt:
            <i>{{ order1.ngaydat }}</i> - Tổng doanh thu :
            <i>{{ order1.revenue }} đồng</i>
          </li>
        </ul>
      </div>
      <div v-else-if="modalType === 'users'">
        <ul>
          <li v-for="order in stats.orders" :key="order.madon">
            Mã đơn: <i>{{ order.madon }}</i> - Ngày đặt:
            <i>{{ order.ngaydat }}</i> - Mã người dùng : <i>{{ order.maND }}</i>
          </li>
        </ul>
      </div>
      <div v-else-if="modalType === 'quantity'">
        <ul>
          <li v-for="order2 in stats.quantity_details" :key="order2.madon">
            Mã đơn: <i>{{ order2.madon }}</i> - Ngày đặt:
            <i>{{ order2.ngaydat }}</i> - Số lượng :
            <i>{{ order2.quantity }} sản phẩm</i>
          </li>
        </ul>
      </div>
    </a-modal>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import { Select, DatePicker, Modal, message } from "ant-design-vue";
import moment from "moment";
import dayjs from "dayjs";

const { Option } = Select;
const { RangePicker, WeekPicker, MonthPicker } = DatePicker;

const stats = ref({
  total_orders: 0,
  total_revenue: 0,
});

const filterType = ref("all");
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
  selectedWeek.value = null;
  selectedMonth.value = null;
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

// Modal
const isModalVisible = ref(false);
const modalType = ref("");
const modalTitle = ref("");

const showDetails = (type) => {
  modalType.value = type;
  switch (type) {
    case "orders":
      modalTitle.value = "Chi tiết đơn hàng";
      break;
    case "revenue":
      modalTitle.value = "Chi tiết doanh thu";
      break;
    case "users":
      modalTitle.value = "Chi tiết người dùng đã mua hàng";
      break;
    case "quantity":
      modalTitle.value = "Chi tiết số lượng sách đã bán";
      break;
  }
  isModalVisible.value = true;
};

const handleOk = () => {
  isModalVisible.value = false;
};

const handleCancel = () => {
  isModalVisible.value = false;
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
  color: white;
  cursor: pointer;
}
.stat-item:nth-child(1) {
  background-color: #337ab7;
  border: 1px solid #337ab7;
}
.stat-item:nth-child(1) .stats {
  color: #337ab7;
}
.stat-item:nth-child(2) {
  background-color: #5cb85c;
  border: 1px solid #5cb85c;
}
.stat-item:nth-child(2) .stats {
  color: #5cb85c;
}
.stat-item:nth-child(3) {
  background-color: #f0ad4e;
  border: 1px solid #f0ad4e;
}
.stat-item:nth-child(3) .stats {
  color: #f0ad4e;
}
.stat-item:nth-child(4) {
  background-color: #d9534f;
  border: 1px solid #d9534f;
}
.stat-item:nth-child(4) .stats {
  color: #d9534f;
}
.stat {
  width: 100%;
  height: 70%;
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 26px;
  padding: 10px;
}
.stats {
  width: 100%;
  height: 30%;
  background-color: white;
  font-size: 18px;
  display: flex;
  justify-content: center;
  align-items: center;
}
.stat-item h2 {
  margin-bottom: 10px;
}

.stat-item {
  font-size: 20px;
  font-weight: bold;
}
.top {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 19%;
  height: 300px;
  font-size: 12.5px;
}
img {
  width: 80%;
  height: 200px;
  margin-bottom: 10px;
  border-top-left-radius: 5px;
  border-top-right-radius: 5px;
}
.tops {
  width: 98%;
  display: flex;
  justify-content: space-between;
}
</style>
