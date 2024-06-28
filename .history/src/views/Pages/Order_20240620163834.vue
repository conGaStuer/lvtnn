<template>
  <NavBar></NavBar>
  <div class="orders">
    <div class="title">
      <h1>Đơn hàng chờ duyệt</h1>
    </div>
    <div class="order-container" v-if="orderItems.length">
      <div class="info">
        <div>Hình Ảnh</div>
        <div>Thông tin</div>
        <div>Đơn giá</div>
        <div>Số Lượng</div>
        <div>Thành tiền</div>
        <div>Trạng thái</div>
      </div>
      <div class="order-item" v-for="item in orderItems" :key="item.MaSach">
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
          <p>{{ item.DonGia }}</p>
        </div>
        <div class="product-quantity">
          <span>{{ item.SoLuong }}</span>
        </div>
        <div class="total-price">
          <p>{{ item.DonGia * item.SoLuong }}</p>
        </div>
        <div class="product-status">
          <p>{{ displayStatus(item.TrangThai) }}</p>
          <a-button
            v-if="item.TrangThai === 'danggiao'"
            @click="confirmDelivery(item)"
          >
            Đã nhận được hàng
          </a-button>
        </div>
      </div>
    </div>
    <div v-else>
      <p>Không có đơn hàng nào chờ duyệt</p>
    </div>
  </div>
  <Footer></Footer>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import NavBar from "@/views/UI_Components/NavBar.vue";
import Footer from "@/views/UI_Components/Footer.vue";

const orderItems = ref([]);

onMounted(() => {
  fetchOrders();
});

const fetchOrders = () => {
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));
  const userId = currentUser.maND;

  axios
    .post("http://localhost/LVTN/book-store/src/api/getorder.php", {
      userId: userId,
      status: "choduyet",
    })
    .then((response) => {
      orderItems.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching orders:", error);
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
</script>

<style scoped>
@import "@/assets/styles/order.scss";
</style>
