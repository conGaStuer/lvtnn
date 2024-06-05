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
      <template v-else-if="column.key === 'categoryName'">
        <span> Tên Danh Mục </span>
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
      <template v-else-if="column.key === 'categoryName'">
        {{ record.categoryName }}
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
const data = [
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },
  {
    key: "1",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    categoryName: "Tên Danh Mục 1",
  },

  // Các dòng dữ liệu khác
];
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
    title: "Tên Danh Mục",
    dataIndex: "categoryName",
    key: "categoryName",
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
function handleResizeColumn(w, col) {
  col.width = w;
}
const pagination = ref({
  pageSize: 4, // Số lượng mục trên mỗi trang
  pageSizeOptions: ["5", "10", "20", "50"], // Tùy chọn: Cho phép người dùng thay đổi số lượng mục trên mỗi trang
  showSizeChanger: true, // Tùy chọn: Hiển thị công cụ thay đổi số lượng mục trên mỗi trang
  showQuickJumper: true, // Tùy chọn: Hiển thị công cụ nhảy nhanh
  showTotal: (total) => `Tổng cộng ${total} sách`, // Tùy chọn: Hiển thị tổng số mục
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
