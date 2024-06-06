<template>
  <a-table
    :columns="columns"
    :data-source="data"
    @resizeColumn="handleResizeColumn"
    :pagination="pagination"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'maKM'">
        <span>
          <smile-outlined />
          Mã Khuyến Mãi
        </span>
      </template>
      <template v-if="column.key === 'tenSach'">
        <span> Tên Sách </span>
      </template>
      <template v-if="column.key === 'maSach'">
        <span> Mã Sách Áp dụng </span>
      </template>
      <template v-if="column.key === 'luongKM'">
        <span> Lượng Khuyến Mãi </span>
      </template>
      <template v-if="column.key === 'action'">
        <span> Thao tác </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'maKM'">
        <a>
          {{ record.MaKhuyenMai }}
        </a>
      </template>
      <template v-if="column.key === 'tenSach'">
        <span v-for="(sach, index) in record.TenSach.split(', ')" :key="index">
          * {{ sach }}
          <br />
        </span>
      </template>
      <template v-if="column.key === 'maSach'">
        {{ record.MaSach }}
      </template>
      <template v-if="column.key === 'luongKM'">
        {{ record.LuongKhuyenMai }}%
      </template>
      <template v-if="column.key === 'action'">
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

const data = ref([]);

onMounted(() => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getDiscount.php")
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
    title: "Mã Khuyến Mãi",
    dataIndex: "maKM",
    key: "maKM",
    width: 50,
  },
  {
    title: "Tên Sách",
    dataIndex: "tenSach",
    key: "tenSach",
    width: 300,
  },
  {
    title: "Mã Sách",
    dataIndex: "maSach",
    key: "maSach",
    width: 150,
  },
  {
    title: "Lượng Khuyến Mãi",
    dataIndex: "luongKM",
    key: "luongKM",
    width: 150,
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
  pageSize: 2,
  pageSizeOptions: ["5", "10", "20", "50"],
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total) => `Tổng cộng ${total} khuyến mãi`,
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
