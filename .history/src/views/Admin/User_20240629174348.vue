<template>
  <a-table
    :columns="columns"
    :data-source="data"
    @resizeColumn="handleResizeColumn"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'tenKH'">
        <span>
          <smile-outlined />
          Tên Người Dùng
        </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'tenKH'">
        <a>
          {{ record.tenKH }}
        </a>
      </template>
      <template v-else-if="column.key === 'sdt'">
        <span>
          {{ record.sdt }}
        </span>
      </template>
      <template v-else-if="column.key === 'ngayLapTaiKhoan'">
        <span>
          {{ record.ngayLapTaiKhoan }}
        </span>
      </template>
    </template>
  </a-table>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
// {
//     key: "1",
//     name: "John Brown",
//     age: "0956464646",
//     address: "Phú Nhuận , Hồ Chí Minh",
//     tags: "xuankhuong1402@gmail.com",
//   },
const data = ref();

onMounted(() => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getAllUser.php")
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
    dataIndex: "tenKH",
    key: "tenKH",
    resizable: true,
    width: 150,
  },
  {
    title: "Số điện thoại",
    dataIndex: "sdt",
    key: "sdt",
  },
  {
    title: "Địa chỉ",
    dataIndex: "diachi",
    key: "diachi",
  },
  {
    title: "Email",
    key: "email",
    dataIndex: "email",
  },
  {
    title: "Ngày lập tài khoản",
    key: "ngayLapTaiKhoan",
    dataIndex: "ngayLapTaiKhoan",
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
