<template>
  <div>
    <NavBar></NavBar>
    <div class="tabs">
      <div
        v-for="tab in tabs"
        :key="tab.status"
        :class="['tab', { active: activeTab === tab.status }]"
        @click="selectTab(tab.status)"
      >
        {{ tab.name }}
        <span v-if="tab.count" class="count">{{ tab.count }}</span>
      </div>
    </div>
    <div class="orders">
      <div class="title">
        <h1>Đơn hàng {{ getTabName(activeTab) }}</h1>
      </div>
      <div class="order-container" v-if="filteredOrders.length">
        <div class="order" v-for="order in filteredOrders" :key="order.MaDon">
          <!-- Order details here -->
        </div>
      </div>
      <div v-else>
        <p>Không có đơn hàng nào {{ getTabName(activeTab) }}</p>
      </div>
    </div>
    <Footer></Footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import NavBar from "@/views/UI_Components/NavBar.vue";
import Footer from "@/views/UI_Components/Footer.vue";

const tabs = ref([
  { name: "Tất cả", status: "tatca", count: 0 },
  { name: "Chờ thanh toán", status: "chothanhtoan", count: 0 },
  { name: "Vận chuyển", status: "vanchuyen", count: 0 },
  { name: "Chờ giao hàng", status: "chogiaohang", count: 1 },
  { name: "Hoàn thành", status: "hoanthanh", count: 0 },
  { name: "Đã hủy", status: "dahuy", count: 0 },
  { name: "Trả hàng/Hoàn tiền", status: "trahanghoantien", count: 0 },
]);

const activeTab = ref("tatca");
const orders = ref([]);

onMounted(() => {
  fetchOrders();
});

const fetchOrders = () => {
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));
  const userId = currentUser.maND;

  axios
    .post("http://localhost/LVTN/book-store/src/api/getorder.php", {
      userId: userId,
    })
    .then((response) => {
      orders.value = response.data;
      updateTabCounts();
    })
    .catch((error) => {
      console.error("Error fetching orders:", error);
    });
};

const updateTabCounts = () => {
  tabs.value.forEach((tab) => {
    if (tab.status === "tatca") {
      tab.count = orders.value.length;
    } else {
      tab.count = orders.value.filter(
        (order) => order.TrangThai === tab.status
      ).length;
    }
  });
};

const selectTab = (status) => {
  activeTab.value = status;
};

const getTabName = (status) => {
  const tab = tabs.value.find((tab) => tab.status === status);
  return tab ? tab.name : "";
};

const filteredOrders = computed(() => {
  if (activeTab.value === "tatca") {
    return orders.value;
  } else {
    return orders.value.filter((order) => order.TrangThai === activeTab.value);
  }
});
</script>

<style scoped>
.tabs {
  display: flex;
  border-bottom: 1px solid #ddd;
  margin-bottom: 20px;
}

.tab {
  padding: 10px 20px;
  cursor: pointer;
  position: relative;
  color: #333;
  font-weight: bold;
}

.tab.active {
  color: red;
  border-bottom: 2px solid red;
}

.tab .count {
  color: red;
}

.tab:not(.active):hover {
  background-color: #f5f5f5;
}

.orders {
  padding: 20px;
}

.order {
  margin-bottom: 20px;
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 5px;
  background-color: white;
}

.order-header {
  margin-bottom: 10px;
  width: 100%;
  text-indent: 10px;
}

.order-item {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.product-details {
  margin-left: 10px;
}

.product-details p {
  margin: 5px 0;
}

.product-status a-button {
  margin-top: 10px;
}
</style>
