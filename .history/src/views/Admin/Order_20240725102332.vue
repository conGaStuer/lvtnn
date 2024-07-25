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
      <template #bodyCell="{ column, record }">
        <span v-if="column.key === 'MaDon'">{{ record.MaDon }}</span>
        <span v-else-if="column.key === 'TenSach'">
          <ul>
            <li v-for="(tenSach, index) in record.TenSach" :key="index">
              {{ tenSach }}
            </li>
          </ul>
        </span>
        <span v-else-if="column.key === 'HinhAnh'">
          <div v-for="(hinhAnh, index) in record.HinhAnh" :key="index">
            <img
              :src="hinhAnh"
              alt="product-image"
              style="max-width: 100px; max-height: 100px"
            />
          </div>
        </span>
        <span v-else-if="column.key === 'SoLuong'">
          <ul>
            <li v-for="(soLuong, index) in record.SoLuong" :key="index">
              {{ soLuong }}
            </li>
          </ul>
        </span>
        <span v-else-if="column.key === 'DonGia'">
          <ul>
            <li v-for="(donGia, index) in record.DonGia" :key="index">
              {{ donGia }}
            </li>
          </ul>
        </span>
        <span v-else-if="column.key === 'DiaChi'">{{ record.DiaChi }}</span>
        <span v-else-if="column.key === 'TrangThai'">{{
          displayStatus(record.TrangThai)
        }}</span>
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
            v-if="record.TrangThai === 'danggiao'"
            @click="deliveredOrder(record)"
          >
            Đã thanh toán
          </a-button>
          <a-button
            @click="cancelOrder(record)"
            :disabled="isCancelDisabled(record)"
          >
            Hủy đơn hàng
          </a-button>
        </span>
        <span v-else-if="column.key === 'viewDetails'">
          <a-button @click="showOrderDetails(record)"> Xem Chi Tiết </a-button>
        </span>
      </template>
    </a-table>

    <a-modal
      v-model:visible="detailsVisible"
      title="Chi Tiết Đơn Hàng"
      @cancel="handleCancel"
      footer=""
    >
      <div v-if="selectedOrder">
        <h2>Thông tin người dùng</h2>
        <p><strong>Tên Khách Hàng:</strong> {{ selectedOrder.TenKH }}</p>
        <p><strong>Số điện thoại:</strong> {{ selectedOrder.SoDienThoai }}</p>
        <p><strong>Địa Chỉ:</strong> {{ selectedOrder.DiaChi }}</p>
        <p><strong>Email:</strong> {{ selectedOrder.Email }}</p>

        <h2>Thông tin đơn hàng</h2>
        <p><strong>Mã Đơn:</strong> {{ selectedOrder.MaDon }}</p>
        <ul>
          <li v-for="(tenSach, index) in selectedOrder.TenSach" :key="index">
            <p><strong>Tên Sách:</strong> {{ tenSach }}</p>
            <p><strong>Số Lượng:</strong> {{ selectedOrder.SoLuong[index] }}</p>
            <p><strong>Đơn Giá:</strong> {{ selectedOrder.DonGia[index] }}</p>
          </li>
        </ul>
        <p><strong>Ngày đặt:</strong> {{ selectedOrder.NgayDat }}</p>
        <p>
          <strong>Trạng Thái:</strong>
          {{ displayStatus(selectedOrder.TrangThai) }}
        </p>
      </div>
    </a-modal>
  </div>
</template>

<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { message } from "ant-design-vue";

const orders = ref([]);
const columns = ref([
  { title: "Mã Đơn", dataIndex: "MaDon", key: "MaDon" },
  { title: "Tên Sách", dataIndex: "TenSach", key: "TenSach" },
  { title: "Hình Ảnh", dataIndex: "HinhAnh", key: "HinhAnh" },
  { title: "Số Lượng", dataIndex: "SoLuong", key: "SoLuong" },
  { title: "Ngày Đặt", dataIndex: "NgayDat", key: "NgayDat" },
  { title: "Đơn Giá", dataIndex: "DonGia", key: "DonGia" },
  { title: "Địa Chỉ", dataIndex: "DiaChi", key: "DiaChi" },
  { title: "Trạng Thái", dataIndex: "TrangThai", key: "TrangThai" },
  { title: "Thao Tác", dataIndex: "action", key: "action" },
  { title: "Xem Chi Tiết", dataIndex: "viewDetails", key: "viewDetails" },
]);

const pagination = ref({
  pageSize: 3,
  showSizeChanger: true,
  pageSizeOptions: ["10", "20", "50", "100"],
});

const detailsVisible = ref(false);
const selectedOrder = ref(null);

onMounted(() => {
  fetchOrders();
});

const fetchOrders = () => {
  const currentUser = JSON.parse(localStorage.getItem("currentUser"));
  const userId = currentUser.maND;

  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/getOrders.php", {
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
      order.TrangThai = "daduyet";
    })
    .catch((error) => {
      console.error("Error updating order status:", error);
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
      message.success("Đã chuyển trạng thái đơn hàng thành 'Đang giao'");
      order.TrangThai = "danggiao";
    })
    .catch((error) => {
      console.error("Error updating order status:", error);
    });
};

const deliveredOrder = (order) => {
  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: "giaohangthanhcong",
      }
    )
    .then(() => {
      message.success("Đơn hàng đã được đánh dấu là 'Giao hàng thành công'");
      order.TrangThai = "giaohangthanhcong";
    })
    .catch((error) => {
      console.error("Error updating order status:", error);
    });
};

const cancelOrder = (order) => {
  axios
    .post(
      "http://localhost/LVTN/book-store/src/api/admin/updateOrderStatus.php",
      {
        maDon: order.MaDon,
        status: "dahuy",
      }
    )
    .then(() => {
      message.success("Đã hủy đơn hàng thành công");
      order.TrangThai = "dahuy";
    })
    .catch((error) => {
      console.error("Error updating order status:", error);
    });
};

const isCancelDisabled = (order) => {
  return order.TrangThai === "giaohangthanhcong" || order.TrangThai === "dahuy";
};

const showOrderDetails = (order) => {
  selectedOrder.value = order;
  detailsVisible.value = true;
};

const handleCancel = () => {
  detailsVisible.value = false;
};

const displayStatus = (status) => {
  const statusMapping = {
    choduyet: "Chờ Duyệt",
    daduyet: "Đã Duyệt",
    danggiao: "Đang Giao",
    giaohangthanhcong: "Giao Hàng Thành Công",
    dahuy: "Đã Hủy",
  };

  return statusMapping[status] || status;
};
</script>

<style scoped>
.order-container {
  max-width: 1200px;
  margin: 0 auto;
}
</style>
