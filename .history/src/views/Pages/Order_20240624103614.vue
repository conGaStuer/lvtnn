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
  const itemsMap = new Map(); // Use a map to store unique items based on MaSach

  // Iterate through each item in the order
  for (let i = 0; i < order.length; i++) {
    const currentOrder = order[i];
    const maSachArray = currentOrder.MaSach.split(",");
    const tenSachArray = currentOrder.TenSach.split(",");
    const hinhAnhArray = currentOrder.HinhAnh.split(",");
    const donGiaArray = currentOrder.DonGia.split(",");
    const soLuongArray = currentOrder.SoLuong.split(",");
    const tacGiaArray = currentOrder.TacGia.split(",");
    const ngonNguArray = currentOrder.NgonNgu.split(",");
    const danhMucArray = currentOrder.DanhMuc.split(",");
    const khuyenMaiArray = currentOrder.KhuyenMai.split(",");

    for (let j = 0; j < maSachArray.length; j++) {
      const maSach = maSachArray[j];

      // Check if item already exists in map, if not, add it
      if (!itemsMap.has(maSach)) {
        itemsMap.set(maSach, {
          MaSach: maSach,
          TenSach: tenSachArray[j],
          HinhAnh: hinhAnhArray[j],
          DonGia: parseFloat(donGiaArray[j]),
          SoLuong: parseInt(soLuongArray[j]),
          TacGia: tacGiaArray[j],
          NgonNgu: ngonNguArray[j],
          DanhMuc: danhMucArray[j],
          KhuyenMai: parseFloat(khuyenMaiArray[j]),
        });
      }
    }
  }

  // Convert map values to array (if necessary) and return
  return Array.from(itemsMap.values());
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
