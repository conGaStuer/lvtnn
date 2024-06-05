<template>
  <a-table
    :columns="columns"
    :data-source="data"
    @resizeColumn="handleResizeColumn"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'name'">
        <span>
          <smile-outlined />
          Tên Người Dùng
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
      <template v-else-if="column.key === 'action'">
        <span>
          <a>Xóa</a>
          <a-divider type="vertical" />
          <a class="ant-dropdown-link"> Thao tác <down-outlined /> </a>
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
    name: "John Brown",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1402@gmail.com",
  },
  {
    key: "2",
    name: "Jim Green",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1403@gmail.com",
  },
  {
    key: "3",
    name: "Joe Black",
    age: "0956464646",
    address: "Sidney No. 1 Lake Park",
    tags: "xuankhuong1404@gmail.com",
  },
  {
    key: "4",
    name: "John Brown",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1405@gmail.com",
  },
  {
    key: "5",
    name: "Jim Green",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1406@gmail.com",
  },
  {
    key: "6",
    name: "Joe Black",
    age: "0956464646",
    address: "Sidney No. 1 Lake Park",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "7",
    name: "John Brown",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "8",
    name: "Jim Green",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "9",
    name: "Joe Black",
    age: "0956464646",
    address: "Sidney No. 1 Lake Park",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "10",
    name: "John Brown",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "11",
    name: "Jim Green",
    age: "0956464646",
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "12",
    name: "Joe Black",
    age: "0956464646",
    address: "Sidney No. 1 Lake Park",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "13",
    name: "John Brown",
    age: 32,
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
  {
    key: "15",
    name: "Jim Green",
    age: 42,
    address: "Phú Nhuận , Hồ Chí Minh",
    tags: "xuankhuong1407@gmail.com",
  },
];
const columns = ref([
  {
    dataIndex: "name",
    key: "name",
    resizable: true,
    width: 150,
  },
  {
    title: "Số điện thoại",
    dataIndex: "age",
    key: "age",
  },
  {
    title: "Địa chỉ",
    dataIndex: "address",
    key: "address",
  },
  {
    title: "Email",
    key: "tags",
    dataIndex: "tags",
  },
  {
    title: "Sửa",
    key: "action",
  },
]);
function handleResizeColumn(w, col) {
  col.width = w;
}
</script>
<style>
.table {
  width: 100%;
  height: 50%;
  overflow: hiiden;
}
</style>
