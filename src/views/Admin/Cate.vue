<template>
  <a-table
    :columns="columns"
    :data-source="groupedData"
    @resizeColumn="handleResizeColumn"
    :pagination="pagination"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'maDM'">
        <span>
          <smile-outlined />
          Mã Danh Mục
        </span>
      </template>
      <template v-if="column.key === 'tenDM'">
        <span> Tên Danh Mục </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'maDM'">
        <span>{{ record.MaDanhMuc }}</span>
      </template>
      <template v-else-if="column.key === 'tenDM'">
        <span>{{ record.DanhMuc }}</span>
      </template>
      <template v-else-if="column.key === 'books'">
        <ul>
          <li v-for="book in record.Books" :key="book.MaSach">
            Mã Sách: {{ book.MaSach }} - Tên Sách: {{ book.TenSach }}
          </li>
        </ul>
      </template>
      <template v-else-if="column.key === 'action'">
        <span>
          <a @click="showEditModal(record)">Sửa</a>
          <a-divider type="vertical" />
          <a @click="deleteCategory(record.MaDanhMuc)">Xóa</a>
        </span>
      </template>
    </template>
  </a-table>
  <AddCateForm
    :visible="isAddModalVisible"
    @update:visible="isAddModalVisible = $event"
    @book-added="fetchBooks"
  />
  <EditCateForm
    :visible="isEditModalVisible"
    :bookData="selectedBook"
    @update:visible="isEditModalVisible = $event"
    @book-updated="fetchBooks"
  />
  <div class="them" @click="showAddModal">
    <a>Thêm Danh Mục</a>
  </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import AddCateForm from "./AddForm/AddCateForm.vue";
import EditCateForm from "./EditForm/EditCateForm.vue";

const data = ref([]);
const groupedData = ref([]);
const isAddModalVisible = ref(false);

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getByFeat.php")
    .then((res) => {
      data.value = res.data;
      groupBooksByCategory();
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

const isEditModalVisible = ref(false);
const selectedBook = ref({});
const showEditModal = (record) => {
  selectedBook.value = { ...record };
  isEditModalVisible.value = true;
};
const deleteCategory = (maDM) => {
  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/deleteCate.php", {
      maDM: maDM,
    })
    .then((res) => {
      if (res.data.status === "success") {
        fetchBooks();
      } else {
        console.log("Delete failed: " + res.data.message);
      }
    })
    .catch((err) => {
      console.log(err);
    });
};

const columns = ref([
  {
    title: "Mã Danh Mục",
    dataIndex: "maDM",
    key: "maDM",
    width: 50,
  },
  {
    title: "Tên Danh Mục",
    dataIndex: "tenDM",
    key: "tenDM",
    width: 270,
  },
  {
    title: "Sách",
    key: "books",
    dataIndex: "books",
    width: 800,
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
  pageSize: 3,
  pageSizeOptions: ["5", "10", "20", "50"],
  showSizeChanger: true,
  showQuickJumper: true,
  showTotal: (total) => `Tổng cộng ${total} danh mục`,
});

function groupBooksByCategory() {
  const groups = {};

  data.value.forEach((book) => {
    const { MaDanhMuc, DanhMuc, MaSach, TenSach } = book;
    if (!groups[MaDanhMuc]) {
      groups[MaDanhMuc] = {
        MaDanhMuc,
        DanhMuc,
        Books: [],
      };
    }
    groups[MaDanhMuc].Books.push({ MaSach, TenSach });
  });

  groupedData.value = Object.values(groups);
}
</script>

<style scoped>
.ant-table-row {
  height: 125px;
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
