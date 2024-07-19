<template>
  <a-modal
    :open="visible"
    title="Sửa Khuyến Mãi"
    @cancel="handleCancel"
    @ok="updateBook"
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
    bookData: Object,
  },
  emits: ["update:visible", "book-updated"],
  setup(props, { emit }) {
    const book = ref({
      MaKhuyenMai: "",
      TenKhuyenMai: "",
    });

    watch(
      () => props.visible,
      (newVal) => {
        if (newVal) {
          book.value = { ...props.bookData };
        }
      }
    );
    const validationError = ref("");

    const validateDiscount = () => {
      if (!/^\d+$/.test(book.value.LuongKM)) {
        validationError.value = "Lượng khuyến mãi chỉ được chứa số";
      } else {
        validationError.value = "";
      }
    };

    const updateBook = () => {
      if (
        validationError.value ||
        !book.value.LuongKM ||
        book.value.LuongKM = 0
      ) {
        message.error("Vui lòng nhập đúng lượng khuyến mãi");
        return;
      }
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/updateDiscount.php",
          book.value
        )
        .then((res) => {
          if (res.data === "Cap Nhat Thanh Cong") {
            message.success("Cập nhật Khuyến Mãi thành công");
            emit("update:visible", false);
            emit("book-updated");
          } else {
            message.error("Cập nhật Khuyến Mãi không thành công");
          }
        })
        .catch((err) => {
          message.error("Có lỗi khi cập nhật Khuyến Mãi: " + err);
        });
    };

    const handleCancel = () => {
      emit("update:visible", false);
    };

    return {
      book,
      updateBook,
      handleCancel,
      validateDiscount,
      validationError,
    };
  },
};
</script>
