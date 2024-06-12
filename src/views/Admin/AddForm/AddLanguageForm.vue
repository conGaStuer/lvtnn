<template>
  <a-modal
    :open="visible"
    title="Thêm Ngôn Ngữ"
    @cancel="handleCancel"
    @ok="addBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Tên Ngôn Ngữ">
        <a-input v-model:value="book.TenNgonNgu" />
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
      TenNgonNgu: "",
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
        TenNgonNgu: "",
      };
    };

    const addBook = () => {
      const bookData = {
        TenNgonNgu: book.value.TenNgonNgu,
      };

      console.log("Sending book data:", bookData);
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/addLanguage.php",
          bookData
        )
        .then((res) => {
          if (res.data === "Them Thanh Cong") {
            message.success("Thêm Ngôn ngữ thành công");
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
