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
        {{ record.KhuyenMai }}%
      </template>
      <template v-if="column.key === 'action'">
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
  <AddDiscountForm
    :visible="isAddModalVisible"
    @update:visible="isAddModalVisible = $event"
    @book-added="fetchBooks"
  />

  <div class="them" @click="showAddModal">
    <a>Thêm Khuyến Mãi</a>
  </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import AddDiscountForm from "./AddForm/AddDiscountForm.vue";

const data = ref([]);

const isAddModalVisible = ref(false);

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getByDisc.php")
    .then((res) => {
      data.value = res.data;
      groupBooksByCategory();

      console.log(data.value);
    })
    .catch((err) => {
      console.log(err);
    });
};

onMounted(() => {
  fetchBooks();
});

const showAddModal = () => {
  isAddModalVisible.value = true;
};
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
  pageSize: 9,
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
.them {
  position: relative;
  top: 295px;
  width: 160px;
  height: 40px;
  background-color: #001529;
  border-radius: 5px;
  color: white;
  cursor: pointer;
  font-size: 15px;
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>
