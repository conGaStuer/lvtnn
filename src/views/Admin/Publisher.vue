<template>
  <a-table
    :columns="columns"
    :data-source="groupedData"
    @resizeColumn="handleResizeColumn"
    :pagination="pagination"
    class="table"
  >
    <template #headerCell="{ column }">
      <template v-if="column.key === 'maNXB'">
        <span>
          <smile-outlined />
          Mã Nhà Xuất Bản
        </span>
      </template>
      <template v-if="column.key === 'tenNXB'">
        <span> Tên Nhà Xuất Bản </span>
      </template>
    </template>

    <template #bodyCell="{ column, record }">
      <template v-if="column.key === 'maNXB'">
        <span>
          {{ record.MaNhaXuatBan }}
        </span>
      </template>
      <template v-else-if="column.key === 'tenNXB'">
        <span>
          {{ record.NhaXuatBan }}
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
  <AddPublisherForm
    :visible="isAddModalVisible"
    @update:visible="isAddModalVisible = $event"
    @book-added="fetchBooks"
  />

  <div class="them" @click="showAddModal">
    <a>Thêm Nhà Xuất Bản</a>
  </div>
</template>

<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import AddPublisherForm from "./AddPublisherForm.vue";

const data = ref([]);
const groupedData = ref([]);
const isAddModalVisible = ref(false);

const fetchBooks = () => {
  axios
    .get("http://localhost/LVTN/book-store/src/api/admin/getByPubs.php")
    .then((res) => {
      data.value = res.data;
      groupBooksByPublisher();

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
    title: "Mã Nhà Xuất Bản",
    dataIndex: "maNXB",
    key: "maNXB",
    width: 50,
  },
  {
    title: "Tên Nhà Xuất Bản",
    dataIndex: "tenNXB",
    key: "tenNXB",
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
  showTotal: (total) => `Tổng cộng ${total} nhà xuất bản`, // Optional: Display total number of items
});

function groupBooksByPublisher() {
  const groups = {};

  data.value.forEach((book) => {
    const { MaNhaXuatBan, NhaXuatBan, MaSach, TenSach } = book;
    if (!groups[MaNhaXuatBan]) {
      groups[MaNhaXuatBan] = {
        MaNhaXuatBan,
        NhaXuatBan,
        Books: [],
      };
    }
    groups[MaNhaXuatBan].Books.push({ MaSach, TenSach });
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
