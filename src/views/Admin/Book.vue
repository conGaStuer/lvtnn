<template>
  <a-table
    :columns="columns"
    :data-source="data"
    @resizeColumn="handleResizeColumn"
    :pagination="pagination"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'name'">
        <span>
          <smile-outlined />
          Mã Sách
        </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'name'">
        <a>
          {{ record.MaSach }}
        </a>
      </template>
      <template v-else-if="column.key === 'tenSach'">
        <span>
          {{ record.TenSach }}
        </span>
      </template>
      <template v-else-if="column.key === 'soLuong'">
        <span>
          {{ record.SoLuong }}
        </span>
      </template>
      <template v-else-if="column.key === 'donGia'">
        <span>
          {{ record.DonGia }}
        </span>
      </template>
      <template v-else-if="column.key === 'chiTiet'">
        <span style="color: black">
          {{ record.ChiTiet }}
        </span>
      </template>
      <template v-else-if="column.key === 'hinhAnh'">
        <img
          :src="record.HinhAnh"
          style="max-width: 90px; max-height: 95px"
          alt="Image"
        />
      </template>
      <template v-else-if="column.key === 'action'">
        <span>
          <a>Thêm</a>
          <a-divider type="vertical" />
        </span>
        <span>
          <a>Xóa</a>
          <a-divider type="vertical" />
        </span>
        <span>
          <a>Sửa</a>
          <a-divider type="vertical" />
        </span>
      </template>
    </template>
  </a-table>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";

const data = ref();
onMounted(() => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getAllBooks.php")
    .then((res) => {
      data.value = res.data;
      console.log(data.value);
    })
    .catch((err) => {
      console.log(err);
    });
});
const columns = ref([
  {
    dataIndex: "name",
    key: "name",
    width: 50,
  },
  {
    title: "Tên Sách",
    dataIndex: "tenSach",
    key: "tenSach",
    width: 270,
  },
  {
    title: "Số Lượng",
    dataIndex: "soLuong",
    key: "soLuong",
    width: 50,
  },
  {
    title: "Đơn Giá",
    key: "donGia",
    dataIndex: "donGia",
    width: 80,
  },
  {
    title: "Chi tiết",
    key: "chiTiet",
    dataIndex: "chiTiet",
    width: 460,
  },
  {
    title: "Hình Ảnh",
    key: "hinhAnh",
    dataIndex: "hinhAnh",
  },
  {
    title: "Thao tác",
    key: "action",
    width: 160,
  },
]);
function handleResizeColumn(w, col) {
  col.width = w;
}
const pagination = ref({
  pageSize: 4, // Number of items per page
  pageSizeOptions: ["5", "10", "20", "50"], // Optional: Allow users to change page size
  showSizeChanger: true, // Optional: Display the page size changer
  showQuickJumper: true, // Optional: Display quick jumper
  showTotal: (total) => `Tổng cộng ${total} sách`, // Optional: Display total number of items
});
</script>
<style scoped>
.ant-table-row {
  height: 135px;
}
.ant-table-cell {
  text-align: center;
}
</style>
