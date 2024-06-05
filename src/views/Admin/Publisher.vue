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
      <template v-else-if="column.key === 'bookName'">
        <span> Tên Sách </span>
      </template>
      <template v-else-if="column.key === 'publisher'">
        <span> Nhà Xuất Bản </span>
      </template>
      <template v-else-if="column.key === 'image'">
        <span> Hình Ảnh </span>
      </template>
      <template v-else-if="column.key === 'action'">
        <span> Thao tác </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'name'">
        <a>
          {{ record.name }}
        </a>
      </template>
      <template v-else-if="column.key === 'bookName'">
        {{ record.bookName }}
      </template>
      <template v-else-if="column.key === 'publisher'">
        {{ record.publisher }}
      </template>
      <template v-else-if="column.key === 'image'">
        <img
          :src="record.image"
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
import { ref } from "vue";

// Dữ liệu mẫu
const data = [
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    publisher: "Nhà Xuất Bản 1",
  },
  // Các dòng dữ liệu khác
];

// Cấu trúc cột của bảng
const columns = ref([
  {
    dataIndex: "name",
    key: "name",
    width: 50,
  },
  {
    title: "Tên Sách",
    dataIndex: "bookName",
    key: "bookName",
    width: 300,
  },
  {
    title: "Nhà Xuất Bản",
    dataIndex: "publisher",
    key: "publisher",
    width: 150,
  },
  {
    title: "Hình Ảnh",
    key: "image",
    dataIndex: "image",
  },
  {
    title: "Thao tác",
    key: "action",
    width: 160,
  },
]);

// Xử lý khi thay đổi kích thước cột
function handleResizeColumn(w, col) {
  col.width = w;
}

// Cấu hình phân trang
const pagination = ref({
  pageSize: 4,
  pageSizeOptions: ["5", "10", "20", "50"],
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total) => `Tổng cộng ${total} sách`,
});
</script>

<style scoped>
.ant-table-row {
  height: 135px;
}
.ant-table {
  text-align: center;
}
</style>
