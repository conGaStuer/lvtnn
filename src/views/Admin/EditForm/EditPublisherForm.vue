<template>
  <a-modal
    :open="visible"
    title="Sửa Nhà Xuất Bản"
    @cancel="handleCancel"
    @ok="updateBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Tên Nhà Xuất Bản">
        <a-input v-model:value="book.TenNhaXuatBan" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script>
import { ref, watch } from "vue";
import { message } from "ant-design-vue";
import axios from "axios";

export default {
  props: {
    visible: Boolean,
    bookData: Object,
  },
  emits: ["update:visible", "book-updated"],
  setup(props, { emit }) {
    const book = ref({
      MaNhaXuatBan: "",
      TenNhaXuatBan: "",
    });

    watch(
      () => props.visible,
      (newVal) => {
        if (newVal) {
          book.value = { ...props.bookData };
        }
      }
    );

    const updateBook = () => {
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/updatePublisher.php",
          book.value
        )
        .then((res) => {
          if (res.data === "Cap Nhat Thanh Cong") {
            message.success("Cập nhật Nhà Xuất Bản thành công");
            emit("update:visible", false);
            emit("book-updated");
          } else {
            message.error("Cập nhật Nhà Xuất Bản không thành công");
          }
        })
        .catch((err) => {
          message.error("Có lỗi khi cập nhật Nhà Xuất Bản: " + err);
        });
    };

    const handleCancel = () => {
      emit("update:visible", false);
    };

    return {
      book,
      updateBook,
      handleCancel,
    };
  },
};
</script>
