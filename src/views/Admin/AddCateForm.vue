<template>
  <a-modal
    :open="visible"
    title="Thêm Danh Mục"
    @cancel="handleCancel"
    @ok="addBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Tên Danh Mục">
        <a-input v-model:value="book.TenDanhMuc" />
      </a-form-item>
    </a-form>
  </a-modal>
</template>

<script>
import { ref, watch, onMounted } from "vue";
import { message } from "ant-design-vue";
import axios from "axios";

export default {
  props: {
    visible: Boolean,
  },
  emits: ["update:visible", "book-added"],
  setup(props, { emit }) {
    const book = ref({
      TenDanhMuc: "",
    });

    watch(
      () => props.visible,
      (newVal) => {
        if (newVal) {
          resetForm();
        }
      }
    );

    const resetForm = () => {
      book.value = {
        TenDanhMuc: "",
      };
    };

    const addBook = () => {
      const bookData = {
        TenDanhMuc: book.value.TenDanhMuc,
      };

      console.log("Sending book data:", bookData);
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/addCate.php",
          bookData
        )
        .then((res) => {
          if (res.data === "Them Thanh Cong") {
            message.success("Thêm Danh mục thành công");
            emit("update:visible", false);
            emit("book-added"); // Emit the book-added event
            resetForm();
          } else {
            message.error("Thêm sách không thành công");
          }
        })
        .catch((err) => {
          message.error("Có lỗi khi thêm sách: " + err);
        });
    };

    const handleCancel = () => {
      emit("update:visible", false);
    };

    return {
      book,

      addBook,
      handleCancel,
    };
  },
};
</script>
