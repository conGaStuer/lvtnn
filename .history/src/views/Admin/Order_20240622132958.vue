<template>
  <div class="order-container">
    <h1>Danh sách đơn hàng</h1>
    <a-table
      :columns="columns"
      :data-source="orders"
      @resizeColumn="handleResizeColumn"
      :pagination="pagination"
      class="table"
    >
      <template #headerCell="{ column }">
        <span>{{ column.title }}</span>
      </template>
      <template #bodyCell="{ column, record }">
        <span v-if="column.key === 'MaDon'">{{ record.MaDon }}</span>

        <span v-if="column.key === 'MaSach'">{{ record.MaSach }}</span>
        <span v-else-if="column.key === 'TenSach'">{{ record.TenSach }}</span>
        <span v-else-if="column.key === 'HinhAnh'">
          <img
            :src="record.HinhAnh"
            alt="product-image"
            style="max-width: 100px; max-height: 100px"
          />
        </span>
        <span v-else-if="column.key === 'TacGia'">{{ record.TacGia }}</span>
        <span v-else-if="column.key === 'NgonNgu'">{{ record.NgonNgu }}</span>
        <span v-else-if="column.key === 'DanhMuc'">{{ record.DanhMuc }}</span>
        <span v-else-if="column.key === 'SoLuong'">{{ record.SoLuong }}</span>
        <span v-else-if="column.key === 'DonGia'">{{ record.DonGia }}</span>
        <span v-else-if="column.key === 'KhuyenMai'">{{
          record.KhuyenMai
        }}</span>
        <span v-else-if="column.key === 'TrangThai'">{{
          displayStatus(record.TrangThai)
        }}</span>
        <span v-else-if="column.key === 'MaND'">{{ record.MaND }}</span>
        <span v-else-if="column.key === 'action'">
          <a-button
            v-if="record.TrangThai === 'choduyet'"
            @click="approveOrder(record)"
          >
            Duyệt đơn hàng
          </a-button>
          <a-button
            v-if="record.TrangThai === 'daduyet'"
            @click="shipOrder(record)"
          >
            Giao đơn hàng
          </a-button>
          <a-button
            @click="cancelOrder(record)"
            :disabled="isCancelDisabled(record)"
          >
            Hủy đơn hàng
          </a-button>
        </span>
      </template>
    </a-table>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { message } from "ant-design-vue";

const orders = ref([]);
const columns = ref([
  { title: "Mã Đơn", dataIndex: "MaDon", key: "MaDon" },

  { title: "Mã Sách", dataIndex: "MaSach", key: "MaSach" },
  { title: "Tên Sách", dataIndex: "TenSach", key: "TenSach" },
  { title: "Hình Ảnh", dataIndex: "HinhAnh", key: "HinhAnh" },
  { title: "Tác Giả", dataIndex: "TacGia", key: "TacGia" },
  { title: "Ngôn Ngữ", dataIndex: "NgonNgu", key: "NgonNgu" },
  { title: "Danh Mục", dataIndex: "DanhMuc", key: "DanhMuc" },
  { title: "Số Lượng", dataIndex: "SoLuong", key: "SoLuong" },
  { title: "Đơn Giá", dataIndex: "DonGia", key: "DonGia" },
  { title: "Khuyến Mãi", dataIndex: "KhuyenMai", key: "KhuyenMai" },
  { title: "Trạng Thái", dataIndex: "TrangThai", key: "TrangThai" },
  { title: "Mã Người Dùng", dataIndex: "MaND", key: "MaND" },
  { title: "Thao Tác", dataIndex: "action", key: "action" },
]);

const pagination = ref({
  pageSize: 3,
  showSizeChanger: true,
  pageSizeOptions: ["10", "20", "50", "100"],
});

onMounted(() => {
  fetchOrders();
});

const fetchOrders = () => {
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));
  const userId = currentUser.maND;

  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/getOrders.php", {
      userId: userId,
      status: "choduyet", // You can change this status as needed
    })
    .then((response) => {
      orders.value = response.data;
    })
    .catch((error) => {
      console.error("Error fetching orders:", error);
    });
};

const approveOrder = (order) => {
  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: "daduyet",
      }
    )
    .then(() => {
      message.success("Đã duyệt đơn hàng thành công");
      order.TrangThai = "daduyet"; // Update local order status
    })
    .catch((error) => {
      console.error("Error approving order:", error);
      message.error("Đã có lỗi xảy ra khi duyệt đơn hàng");
    });
};

const shipOrder = (order) => {
  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: "danggiao",
      }
    )
    .then(() => {
      message.success("Đã giao đơn hàng thành công");
      order.TrangThai = "danggiao"; // Update local order status
    })
    .catch((error) => {
      console.error("Error shipping order:", error);
      message.error("Đã có lỗi xảy ra khi giao đơn hàng");
    });
};

const cancelOrder = (order) => {
  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: "huydon",
      }
    )
    .then(() => {
      message.success("Đã hủy đơn hàng thành công");
      order.TrangThai = "huydon"; // Update local order status
    })
    .catch((error) => {
      console.error("Error cancelling order:", error);
      message.error("Đã có lỗi xảy ra khi hủy đơn hàng");
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

function handleResizeColumn(w, col) {
  col.width = w;
}

const isCancelDisabled = (record) => {
  return (
    record.TrangThai === "danggiao" || record.TrangThai === "giaohangthanhcong"
  );
};
</script>

<style scoped>
.order-container {
  padding: 20px;
}
</style>
