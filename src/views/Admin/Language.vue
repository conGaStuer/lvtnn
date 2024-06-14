<template>
  <a-table
    :columns="columns"
    :data-source="groupedData"
    @resizeColumn="handleResizeColumn"
    :pagination="pagination"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'maNN'">
        <span>
          <smile-outlined />
          Mã Ngôn Ngữ
        </span>
      </template>
      <template v-if="column.key === 'tenNN'">
        <span> Tên Ngôn Ngữ </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'maNN'">
        <span>
          {{ record.MaNgonNgu }}
        </span>
      </template>
      <template v-else-if="column.key === 'tenNN'">
        <span>
          {{ record.NgonNgu }}
        </span>
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
          <a @click="deleteCategory(record.MaNgonNgu)">Xóa</a>
        </span>
      </template>
    </template>
  </a-table>
  <AddLanguageForm
    :visible="isAddModalVisible"
    @update:visible="isAddModalVisible = $event"
    @book-added="fetchBooks"
  />
  <EditLanguageForm
    :visible="isEditModalVisible"
    :bookData="selectedBook"
    @update:visible="isEditModalVisible = $event"
    @book-updated="fetchBooks"
  />
  <div class="them" @click="showAddModal">
    <a>Thêm Ngôn Ngữ</a>
  </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import AddLanguageForm from "./AddForm/AddLanguageForm.vue";
import EditLanguageForm from "./EditForm/EditLanguageForm.vue";

const data = ref([]);
const groupedData = ref([]);
const isAddModalVisible = ref(false);
const selectedBook = ref({});

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getByLans.php")
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
const isEditModalVisible = ref(false);

const showAddModal = () => {
  isAddModalVisible.value = true;
};
const showEditModal = (record) => {
  selectedBook.value = { ...record };
  isEditModalVisible.value = true;
};

const deleteCategory = (maNN) => {
  axios
    .post("http://localhost/LVTN/book-store/src/api/admin/deleteLanguage.php", {
      maNN: maNN,
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
    title: "Mã Ngôn Ngữ",
    dataIndex: "maNN",
    key: "maNN",
    width: 50,
  },
  {
    title: "Tên Ngôn Ngữ",
    dataIndex: "tenNN",
    key: "tenNN",
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
  pageSize: 2, // Number of items per page
  pageSizeOptions: ["5", "10", "20", "50"], // Optional: Allow users to change page size
  showSizeChanger: true, // Optional: Display the page size changer
  showQuickJumper: true, // Optional: Display quick jumper
  showTotal: (total) => `Tổng cộng ${total} ngôn ngữ`, // Optional: Display total number of items
});

function groupBooksByCategory() {
  const groups = {};

  data.value.forEach((book) => {
    const { MaNgonNgu, NgonNgu, MaSach, TenSach } = book;
    if (!groups[MaNgonNgu]) {
      groups[MaNgonNgu] = {
        MaNgonNgu,
        NgonNgu,
        Books: [],
      };
    }
    groups[MaNgonNgu].Books.push({ MaSach, TenSach });
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
