<template>
  <a-button type="primary" @click="showAddUserModal"
    >Thêm tài khoản nhân viên</a-button
  >
  <!-- Modal for adding a new user -->
  <a-modal v-model:visible="modalVisible">
    <template #title> Thêm tài khoản nhân viên mới</template>
    <form @submit.prevent="handleAddUser">
      <!-- Input fields for user details -->
      <a-form-item label="Tên nhân viên" required>
        <a-input v-model="newUser.tenKH" />
      </a-form-item>
      <a-form-item label="Tài khoản" required>
        <a-input v-model="newUser.taikhoan" />
      </a-form-item>
      <a-form-item label="Mật khẩu" required>
        <a-input v-model="newUser.matkhau" />
      </a-form-item>
      <!-- Add more fields as needed -->

      <a-form-item>
        <a-button type="primary" html-type="submit">Thêm</a-button>
      </a-form-item>
    </form>
  </a-modal>

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
      <template v-else-if="column.key === 'tongSoDonHang'">
        <span>
          {{ record.tongSoDonHang }}
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
const modalVisible = ref(false);
function showAddUserModal() {
  modalVisible.value = true;
}
const newUser = ref({
  tenKH: "",
  taikhoan: "",
  matkhau: "",
});

async function handleAddUser() {
  try {
    const response = await axios.post(
      "http://localhost/LVTN/book-store/src/api/admin/addUser.php",
      newUser.value
    );
    data.value.push(response.data); // Add the new user to the local data array
    modalVisible.value = false; // Hide the modal after successful addition
    newUser.value = {}; // Clear the newUser object for next use
  } catch (error) {
    console.error("Error adding user:", error);
    // Handle error state or feedback to the user
  }
}

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
  {
    title: "Tổng số đơn hàng",
    key: "tongSoDonHang",
    dataIndex: "tongSoDonHang",
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
