<template>
  <a-modal
    :open="visible"
    title="Sửa Khuyến Mãi"
    @cancel="handleCancel"
    @ok="updateBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Lượng Khuyến Mãi">
        <a-input v-model:value="book.TenKhuyenMai" />
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

    const updateBook = () => {
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
    };
  },
};
</script>
