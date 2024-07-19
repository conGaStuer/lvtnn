<template>
  <NavBar></NavBar>
  <div class="orders">
    <div class="title">
      <h1>Quản lý đơn hàng</h1>
    </div>
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
          <div class="order-header">
            <h2>Đơn hàng: {{ order.MaDon }}</h2>
            <p>Ngày đặt: {{ order.NgayDat }}</p>
            <p>Tổng tiền: {{ calculateTotalPrice(order) }}</p>
          </div>
          <div class="order-items">
            <div class="info">
              <div>Hình Ảnh</div>
              <div>Thông tin</div>
              <div>Đơn giá</div>
              <div>Số Lượng</div>
              <div>Thành tiền</div>
              <div>Trạng thái</div>
            </div>
            <div
              class="order-item"
              v-for="(item, index) in parseOrderItems(order)"
              :key="index"
            >
              <img :src="item.HinhAnh" alt="product-image" />
              <div class="product-details">
                <p class="name">{{ item.TenSach }}</p>
                <h4>{{ item.DanhMuc }}</h4>
                <p>
                  Tác giả: <span>{{ item.TacGia }}</span>
                </p>
                <p>
                  Nhà xuất bản: <span>{{ item.NhaXuatBan }}</span>
                </p>
                <p>
                  Ngôn ngữ: <span>{{ item.NgonNgu }}</span>
                </p>
              </div>
              <div class="product-price">
                <p>
                  {{ item.GiaDonHang }}
                </p>
              </div>
              <div class="product-quantity">
                <span>{{ item.SoLuong }}</span>
              </div>
              <div class="total-price">
                <p>
                  {{ item.GiaDonHang }}
                </p>
              </div>
              <div class="product-status">
                <router-link to="/process" @click="process(order)">{{
                  displayStatus(order.TrangThai)
                }}</router-link>

                <a-button
                  v-if="order.TrangThai === 'giaohangthanhcong'"
                  @click="printInvoice(order.MaDon)"
                >
                  In hóa đơn tại đây
                </a-button>
                <a-button
                  v-if="order.TrangThai === 'choduyet'"
                  @click="cancelOrder(order.MaDon)"
                >
                  Hủy đơn hàng
                </a-button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-else>
        <p>Không có đơn hàng nào</p>
      </div>
    </div>
  </div>
  <Footer></Footer>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "@/views/UI_Components/NavBar.vue";
import Footer from "@/views/UI_Components/Footer.vue";
import { useRouter } from "vue-router";

const selectedStatus = ref("all");
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

const calculateTotalPrice = (order) => {
  if (!order.Items || !Array.isArray(order.Items)) {
    return 0; // Return 0 if Items is not an array or is undefined
  }

  return order.Items.reduce((total, item) => {
    const price = parseInt(item.GiaDonHang) || 0;
    const quantity = parseInt(item.SoLuong) || 1;
    return total + price * quantity;
  }, 0);
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
const parseOrderItems = (order) => {
  return order.Items.map((item) => {
    return {
      MaSach: item.MaSach,
      TenSach: item.TenSach,
      HinhAnh: item.HinhAnh,
      DonGia: parseInt(item.DonGia),
      GiaDonHang: parseInt(item.GiaDonHang),
      SoLuong: item.SoLuong,
      TacGia: item.TacGia,
      NgonNgu: item.NgonNgu,
      DanhMuc: item.DanhMuc,
      NhaXuatBan: item.NhaXuatBan,
      KhuyenMai: item.KhuyenMai,
    };
  });
};

const confirmDelivery = (order) => {
  axios
    .post("http://localhost/LVTN/book-store/src/api/updateOrderStatus.php", {
      maDon: order.MaDon,
      status: "giaohangthanhcong",
    })
    .then(() => {
      order.TrangThai = "giaohangthanhcong"; // Cập nhật trạng thái đơn hàng
    })
    .catch((error) => {
      console.error("Error confirming delivery:", error);
    });
};
const router = useRouter();
const process = (order) => {
  console.log(order.MaDon);
  axios
    .post("http://localhost/LVTN/book-store/src/api/orderDetails.php", {
      maDon: order.MaDon,
    })
    .then((response) => {
      router.push({ name: "process", params: { id: order.MaDon } });
    })
    .catch((error) => {
      console.error("Error confirming delivery:", error);
    });
};
const filteredOrders = computed(() => {
  if (activeTab.value === "tatca") {
    return orders.value;
  } else {
    return orders.value.filter((order) => order.TrangThai === activeTab.value);
  }
});
const printInvoice = (maDon) => {
  window.open(
    `http://localhost/LVTN/book-store/src/api/printInvoice.php?maDon=${maDon}`,
    "_blank"
  );
};

const displayStatus = (status) => {
  switch (status) {
    case "choduyet":
      return "Chờ duyệt";
    case "daduyet":
      return "Đã duyệt";
    case "danggiao":
      return "Đang giao";
    case "huydon":
      return "Đã hủy";
    case "giaohangthanhcong":
      return "Giao hàng thành công";
    default:
      return "Không xác định";
  }
};

const cancelOrder = (maDon) => {
  axios
    .post("http://localhost/LVTN/book-store/src/api/updateOrderStatus.php", {
      maDon: maDon,
      status: "huydon",
    })
    .then((response) => {
      fetchOrders(selectedStatus.value); // Refresh the orders list after cancellation
    })
    .catch((error) => {
      console.error("Error canceling order:", error);
    });
};

const changeTab = (status) => {
  selectedStatus.value = status;
  fetchOrders(status);
};
</script>

<style scoped>
@import "@/assets/styles/order.scss";

.orders {
  padding: 20px;
}

.tabs {
  display: flex;
  justify-content: space-around;
  margin-bottom: 20px;
}

.tabs button {
  padding: 10px 20px;
  border: none;
  background: #ddd;
  cursor: pointer;
}

.tabs button.active {
  background: #333;
  color: white;
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
