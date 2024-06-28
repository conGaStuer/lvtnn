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
        <span v-if="column.key === 'MaSach'">{{ record.Sach[0].MaSach }}</span>
        <span v-else-if="column.key === 'TenSach'">{{
          record.Sach[0].TenSach
        }}</span>
        <span v-else-if="column.key === 'HinhAnh'">
          <img
            :src="record.Sach[0].HinhAnh"
            alt="product-image"
            style="max-width: 100px; max-height: 100px"
          />
        </span>
        <span v-else-if="column.key === 'TacGia'">{{
          record.Sach[0].TacGia
        }}</span>
        <span v-else-if="column.key === 'NgonNgu'">{{
          record.Sach[0].NgonNgu
        }}</span>
        <span v-else-if="column.key === 'DanhMuc'">{{
          record.Sach[0].DanhMuc
        }}</span>
        <span v-else-if="column.key === 'SoLuong'">{{
          record.Sach[0].SoLuong
        }}</span>
        <span v-else-if="column.key === 'DonGia'">{{
          record.Sach[0].DonGia
        }}</span>
        <span v-else-if="column.key === 'KhuyenMai'">{{
          record.Sach[0].KhuyenMai
        }}</span>
        <span v-else-if="column.key === 'TrangThai'">{{
          displayStatus(record.TrangThai)
        }}</span>
        <span v-else-if="column.key === 'MaND'">{{ record.MaND }}</span>
        <span v-else-if="column.key === 'action'">
          <a-button
            v-if="canShowButton(record, 'approve')"
            @click="handleOrderAction(record, 'approve')"
          >
            Duyệt đơn hàng
          </a-button>
          <a-button
            v-if="canShowButton(record, 'ship')"
            @click="handleOrderAction(record, 'ship')"
          >
            Giao đơn hàng
          </a-button>
          <a-button
            v-if="canShowButton(record, 'cancel')"
            @click="handleOrderAction(record, 'cancel')"
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
  { title: "Mã Sách", dataIndex: "Sach[0].MaSach", key: "MaSach" },
  { title: "Tên Sách", dataIndex: "Sach[0].TenSach", key: "TenSach" },
  { title: "Hình Ảnh", dataIndex: "Sach[0].HinhAnh", key: "HinhAnh" },
  { title: "Tác Giả", dataIndex: "Sach[0].TacGia", key: "TacGia" },
  { title: "Ngôn Ngữ", dataIndex: "Sach[0].NgonNgu", key: "NgonNgu" },
  { title: "Danh Mục", dataIndex: "Sach[0].DanhMuc", key: "DanhMuc" },
  { title: "Số Lượng", dataIndex: "Sach[0].SoLuong", key: "SoLuong" },
  { title: "Đơn Giá", dataIndex: "Sach[0].DonGia", key: "DonGia" },
  { title: "Khuyến Mãi", dataIndex: "Sach[0].KhuyenMai", key: "KhuyenMai" },
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
      orders.value = response.data.map((order) => ({
        ...order,
        Sach: [
          {
            MaSach: order.MaSach,
            TenSach: order.TenSach,
            HinhAnh: order.HinhAnh,
            TacGia: order.TacGia,
            NgonNgu: order.NgonNgu,
            DanhMuc: order.DanhMuc,
            SoLuong: order.SoLuong,
            DonGia: order.DonGia,
            KhuyenMai: order.KhuyenMai,
          },
        ],
      }));
    })
    .catch((error) => {
      console.error("Error fetching orders:", error);
    });
};

const handleOrderAction = (order, action) => {
  let status;
  switch (action) {
    case "approve":
      status = "daduyet";
      break;
    case "ship":
      status = "danggiao";
      break;
    case "cancel":
      status = "huydon";
      break;
    default:
      return;
  }

  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: status,
      }
    )
    .then(() => {
      switch (action) {
        case "approve":
          message.success("Đã duyệt đơn hàng thành công");
          order.TrangThai = "daduyet";
          break;
        case "ship":
          message.success("Đã giao đơn hàng thành công");
          order.TrangThai = "danggiao";
          break;
        case "cancel":
          message.success("Đã hủy đơn hàng thành công");
          order.TrangThai = "huydon";
          break;
      }
    })
    .catch((error) => {
      console.error(`Error ${action} order:`, error);
      message.error(`Đã có lỗi xảy ra khi ${action} đơn hàng`);
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

const isCancelDisabled = (order) => {
  return (
    order.TrangThai === "danggiao" || order.TrangThai === "giaohangthanhcong"
  );
};

const canShowButton = (order, action) => {
  switch (action) {
    case "approve":
      return order.TrangThai === "choduyet";
    case "ship":
      return order.TrangThai === "daduyet";
    case "cancel":
      return order.TrangThai === "choduyet" || order.TrangThai === "daduyet";
    default:
      return false;
  }
};

function handleResizeColumn(w, col) {
  col.width = w;
}
</script>

<style scoped>
.order-container {
  padding: 20px;
}
</style>
