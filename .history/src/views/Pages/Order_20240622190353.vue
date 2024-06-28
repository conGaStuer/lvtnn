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
          <p>Tổng tiền: {{ order.TongTien }}</p>
        </div>
        <div class="order-items">
          <div
            class="order-item"
            v-for="(item, index) in parseOrderItems(order)"
            :key="index"
          >
            <img :src="item.HinhAnh" alt="product-image" />
            <div class="product-details">
              <div class="info">
                <div>Hình Ảnh</div>
                <div>Thông tin</div>
                <div>Đơn giá</div>
                <div>Số Lượng</div>
                <div>Thành tiền</div>
                <div>Trạng thái</div>
              </div>
              <div class="product-info">
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
                    In hóa đơn
                  </a-button>
                </div>
              </div>
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
  const maSachArray = order.MaSach ? order.MaSach.split(",") : [];
  const tenSachArray = order.TenSach ? order.TenSach.split(",") : [];
  const hinhAnhArray = order.HinhAnh ? order.HinhAnh.split(",") : [];
  const donGiaArray = order.DonGia ? order.DonGia.split(",") : [];
  const soLuongArray = order.SoLuong ? order.SoLuong.split(",") : [];
  const tacGiaArray = order.TacGia ? order.TacGia.split(",") : [];
  const ngonNguArray = order.NgonNgu ? order.NgonNgu.split(",") : [];
  const danhMucArray = order.DanhMuc ? order.DanhMuc.split(",") : [];

  return maSachArray.map((maSach, index) => {
    return {
      MaSach: maSach,
      TenSach: tenSachArray[index] || "",
      HinhAnh: hinhAnhArray[index] || "",
      DonGia: donGiaArray[index] || 0,
      SoLuong: soLuongArray[index] || 0,
      TacGia: tacGiaArray[index] || "",
      NgonNgu: ngonNguArray[index] || "",
      DanhMuc: danhMucArray[index] || "",
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
  display: flex;
  flex-direction: column;
  width: 100%;
}

.product-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border: 1px solid #eaeaea;
  padding: 10px 20px;
  margin-bottom: 30px;
  border-radius: 10px;
  box-shadow: rgba(149, 157, 165, 0.4) 0px 8px 54px;

  h4 {
    font-size: 20px;
    font-weight: bold;
  }

  .name {
    font-size: 16px;
  }

  p {
    margin: 5px 0;
    font-size: 14px;
  }

  span {
    font-weight: bold;
  }

  .product-price,
  .product-quantity,
  .total-price {
    text-align: center;
    font-size: 16px;
    font-weight: bold;
  }

  .product-status {
    display: flex;
    flex-direction: column;
    align-items: center;

    a {
      text-decoration: none;
      color: #f28b82;
    }

    button {
      background-color: #f28b82;
      color: #fff;
      border-radius: 10px;
      border: none;
      font-weight: bold;
      font-size: 12px;
      cursor: pointer;
      margin-top: 10px;
      padding: 5px 10px;

      &:hover {
        color: black;
      }
    }
  }
}

.orders {
  width: 85%;
  margin: 50px auto;
  font-family: "Noto Sans";

  .title {
    margin-bottom: 40px;

    h1 {
      font-size: 24px;
      margin: 0;
    }

    p {
      font-size: 16px;
      margin: 0;
    }
  }
}
</style>
