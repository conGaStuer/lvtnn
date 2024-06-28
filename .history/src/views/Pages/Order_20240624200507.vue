<template>
  <NavBar></NavBar>
  <div class="orders">
    <div class="title">
      <h1>Đơn hàng chờ duyệt</h1>
    </div>
    <div class="order-container" v-if="orders.length">
      <div class="order" v-for="order in orders" :key="order.MaDon">
        <div class="order-header">
          <h2>Đơn hàng: {{ order.MaDon }}</h2>
          <p>Ngày đặt: {{ order.NgayDat }}</p>
          <p>Tổng tiền: {{ order.DonGia * order.SoLuong }}</p>
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
              <p>{{ item.DonGia - (item.DonGia * item.KhuyenMai) / 100 }}</p>
            </div>
            <div class="product-quantity">
              <span>{{ item.SoLuong }}</span>
            </div>
            <div class="total-price">
              <p>
                {{
                  (item.DonGia - (item.DonGia * item.KhuyenMai) / 100) *
                  item.SoLuong
                }}
              </p>
            </div>
            <div class="product-status">
              <router-link to="/process" @click="process(order)">{{
                displayStatus(order.TrangThai)
              }}</router-link>
              <a-button
                v-if="order.TrangThai === 'danggiao'"
                @click="confirmDelivery(order)"
              >
                Đã nhận được hàng
              </a-button>
              <a-button
                v-if="order.TrangThai === 'giaohangthanhcong'"
                @click="printInvoice(order.MaDon)"
              >
                In hóa đơn tại đây
              </a-button>
            </div>
          </div>
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
import { useRouter } from "vue-router";

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
      status: "choduyet",
    })
    .then((response) => {
      orders.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching orders:", error);
    });
};

const parseOrderItems = (order) => {
  const items = [];

  // Kiểm tra order.MaSach là mảng hoặc chuỗi
  let maSachArray = [];
  if (Array.isArray(order.MaSach)) {
    maSachArray = order.MaSach;
  } else if (typeof order.MaSach === "string") {
    maSachArray = order.MaSach.split(",");
  } else {
    console.error("Invalid format for order.MaSach:", order.MaSach);
    return items; // Hoặc xử lý lỗi tùy theo yêu cầu của bạn
  }

  // Lặp qua mảng maSachArray để tạo các đối tượng item
  for (let i = 0; i < maSachArray.length; i++) {
    const item = {
      MaSach: maSachArray[i].trim(), // Xóa khoảng trắng ở đầu và cuối chuỗi
      TenSach: order.TenSach[i].trim(),
      HinhAnh: order.HinhAnh[i].trim(),
      DonGia: parseFloat(order.DonGia[i]),
      SoLuong: parseInt(order.SoLuong[i]),
      TacGia: order.TacGia[i].trim(),
      NgonNgu: order.NgonNgu[i].trim(),
      DanhMuc: order.DanhMuc[i].trim(),
      NhaXuatBan: order.NhaXuatBan[i],
      KhuyenMai: parseFloat(order.KhuyenMai[i]),
    };

    items.push(item);
  }

  return items;
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
</script>

<style scoped>
@import "@/assets/styles/order.scss";

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
