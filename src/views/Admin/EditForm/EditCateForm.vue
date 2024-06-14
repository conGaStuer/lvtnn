<template>
  <a-modal
    :open="visible"
    title="Sửa Danh Mục"
    @cancel="handleCancel"
    @ok="updateBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Tên Danh Mục">
        <a-input v-model:value="book.TenDanhMuc" />
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
      MaDanhMuc: "",
      TenDanhMuc: "",
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
          "http://localhost/LVTN/book-store/src/api/admin/updateCate.php",
          book.value
        )
        .then((res) => {
          if (res.data === "Cap Nhat Thanh Cong") {
            message.success("Cập nhật danh mục thành công");
            emit("update:visible", false);
            emit("book-updated");
          } else {
            message.error("Cập nhật danh mục không thành công");
          }
        })
        .catch((err) => {
          message.error("Có lỗi khi cập nhật danh mục: " + err);
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
