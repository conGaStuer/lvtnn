<template>
  <a-modal
    :open="visible"
    title="Thêm Khuyến Mãi"
    @cancel="handleCancel"
    @ok="addBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Lượng Khuyến Mãi">
        <a-input v-model:value="book.LuongKM" @input="validateDiscount" />
        <div v-if="validationError" style="color: red">
          {{ validationError }}
        </div>
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
  },
  emits: ["update:visible", "book-added"],
  setup(props, { emit }) {
    const book = ref({
      LuongKM: "",
    });
    const validationError = ref("");

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
        LuongKM: "",
      };
      validationError.value = "";
    };

    const validateDiscount = () => {
      if (!/^\d+$/.test(book.value.LuongKM)) {
        validationError.value = "Lượng khuyến mãi chỉ được chứa số";
      } else {
        validationError.value = "";
      }
    };

    const addBook = () => {
      if (
        validationError.value ||
        !book.value.LuongKM ||
        book.value.LuongKM <= 0
      ) {
        message.error("Vui lòng nhập đúng lượng khuyến mãi");
        return;
      }

      const bookData = {
        LuongKM: book.value.LuongKM,
      };

      console.log("Sending book data:", bookData);
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/addDiscount.php",
          bookData
        )
        .then((res) => {
          if (res.data === "Them Thanh Cong") {
            message.success("Thêm Khuyến Mãi thành công");
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
      validateDiscount,
      validationError,
    };
  },
};
</script>
