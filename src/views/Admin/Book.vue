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
          <a @click="handleDelete(record)">Xóa</a>
          <a-divider type="vertical" />
        </span>
        <span>
          <a @click="showEditModal(record)">Sửa</a>
          <a-divider type="vertical" />
        </span>
      </template>
    </template>
  </a-table>
  <AddBookForm
    :visible="isAddModalVisible"
    @update:visible="isAddModalVisible = $event"
    @book-added="fetchBooks"
  />
  <EditBookForm
    :visible="isEditModalVisible"
    :bookData="selectedBook"
    @update:visible="isEditModalVisible = $event"
    @book-updated="fetchBooks"
  />
  <div class="them" @click="showAddModal">
    <a>Thêm sách</a>
  </div>
</template>

<script setup>
import { message } from "ant-design-vue";
import axios from "axios";
import { onMounted, ref } from "vue";
import AddBookForm from "./AddForm/AddBookForm.vue"; // Adjust the path as necessary
import EditBookForm from "./EditForm/EditBookForm.vue"; // Adjust the path as necessary

const data = ref([]);
const isAddModalVisible = ref(false);
const isEditModalVisible = ref(false);
const selectedBook = ref({});

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getAllBooks.php")
    .then((res) => {
      data.value = res.data;
      console.log(data.value);
    })
    .catch((err) => {
      console.log(err);
    });
};

onMounted(() => {
  fetchBooks();
});
const handleDelete = (record) => {
  const maSach = record.MaSach;

  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/deleteBook.php", {
      MaSach: maSach,
    })
    .then((res) => {
      message.success("Xóa sách thành công");
      fetchBooks(); // Gọi lại hàm fetchBooks để cập nhật dữ liệu
    })
    .catch((err) => {
      message.error("Xóa thất bại");
      console.log(err);
    });
};
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
  pageSize: 4,
  pageSizeOptions: ["5", "10", "20", "50"],
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total) => `Tổng cộng ${total} sách`,
});

const showAddModal = () => {
  isAddModalVisible.value = true;
};
const showEditModal = (record) => {
  selectedBook.value = { ...record };
  isEditModalVisible.value = true;
};
</script>

<style scoped>
.ant-table-row {
  height: 135px;
}
.ant-table-cell {
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
