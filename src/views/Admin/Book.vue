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
          {{ record.name }}
        </a>
      </template>
      <template v-else-if="column.key === 'tag'">
        <span>
          <a-tag>
            {{ tag }}
          </a-tag>
        </span>
      </template>
      <template v-else-if="column.key === 'des'">
        <span style="color: black">
          {{ record.des }}
        </span>
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
    des: "Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm",
  },
  {
    key: "2",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    des: "Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm",
  },
  {
    key: "3",
    name: 1,
    bookName: "Tên Sách Thứ",
    address: "Sidney No. 1 Lake Park",
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    des: "Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm",
  },
  {
    key: "4",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
    des: "Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm Chi tiết sản phẩm",
  },
  {
    key: "5",
    name: 1,
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
  },
  {
    key: "6",
    name: "Joe Black",
    bookName: "Tên Sách Thứ",
    address: "Sidney No. 1 Lake Park",
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
  },
  {
    key: "7",
    name: "John Brown",
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
    image:
      "https://m.media-amazon.com/images/I/614sazJczJL._AC_UF1000,1000_QL80_DpWeblab_.jpg",
  },
  {
    key: "8",
    name: "Jim Green",
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
  },
  {
    key: "9",
    name: "Joe Black",
    bookName: "Tên Sách Thứ",
    address: "Sidney No. 1 Lake Park",
    price: "25000 đồng",
  },
  {
    key: "10",
    name: "John Brown",
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
  },
  {
    key: "11",
    name: "Jim Green",
    bookName: "Tên Sách Thứ",
    quantity: 10,
    price: "25000 đồng",
  },
  {
    key: "12",
    name: "Joe Black",
    bookName: "Tên Sách Thứ",
    address: 10,
    price: "25000 đồng",
  },
  {
    key: "13",
    name: "John Brown",
    bookName: 32,
    quantity: 10,
    price: "25000 đồng",
  },
  {
    key: "15",
    name: "Jim Green",
    bookName: 42,
    quantity: 10,
    price: "25000 đồng",
  },
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
    title: "Số Lượng",
    dataIndex: "quantity",
    key: "quantity",
    width: 50,
  },
  {
    title: "Đơn Giá",
    key: "price",
    dataIndex: "price",
    width: 120,
  },
  {
    title: "Chi tiết",
    key: "des",
    dataIndex: "des",
    width: 350,
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
