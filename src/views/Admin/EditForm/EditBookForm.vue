<template>
  <a-modal
    :open="visible"
    title="Sửa Sách"
    @cancel="handleCancel"
    @ok="updateBook"
  >
    <a-form layout="vertical">
      <a-form-item label="Tên Sách">
        <a-input
          v-model:value="book.TenSach"
          @input="console.log('TenSach:', book.TenSach)"
        />
      </a-form-item>
      <a-form-item label="Số Lượng">
        <a-input-number v-model:value="book.SoLuong" style="width: 100%" />
      </a-form-item>
      <a-form-item label="Đơn Giá">
        <a-input-number v-model:value="book.DonGia" style="width: 100%" />
      </a-form-item>
      <a-form-item label="Chi Tiết">
        <a-input v-model:value="book.ChiTiet" />
      </a-form-item>
      <a-form-item label="Hình Ảnh">
        <a-input v-model:value="book.HinhAnh" />
      </a-form-item>
      <a-form-item label="Nhà Xuất Bản">
        <a-select v-model:value="book.MaNhaXuatBan" style="width: 100%">
          <a-select-option
            v-for="publisher in publishers"
            :key="publisher.maNXB"
            :value="publisher.maNXB"
          >
            {{ publisher.tenNXB }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item label="Khuyến Mãi">
        <a-select v-model:value="book.MaKhuyenMai" style="width: 100%">
          <a-select-option
            v-for="promotion in promotions"
            :key="promotion.maKM"
            :value="promotion.maKM"
          >
            {{ promotion.luongKM }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item label="Danh Mục">
        <a-select
          v-model:value="book.MaDanhMuc"
          style="width: 100%"
          mode="multiple"
        >
          <a-select-option
            v-for="category in categories"
            :key="category.maDM"
            :value="category.maDM"
          >
            {{ category.tenDM }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item label="Tác Giả">
        <a-select
          v-model:value="book.MaTacGia"
          style="width: 100%"
          mode="multiple"
        >
          <a-select-option
            v-for="author in authors"
            :key="author.maTG"
            :value="author.maTG"
          >
            {{ author.tenTG }}
          </a-select-option>
        </a-select>
      </a-form-item>
      <a-form-item label="Ngôn Ngữ">
        <a-select
          v-model:value="book.MaNgonNgu"
          @input="console.log(book.NgonNgu)"
          style="width: 100%"
        >
          <a-select-option
            v-for="language in languages"
            :key="language.maNN"
            :value="language.maNN"
          >
            {{ language.tenNN }}
          </a-select-option>
        </a-select>
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
    const book = ref({ ...props.bookData });

    const resetForm = () => {
      book.value = { ...props.bookData };
    };

    const publishers = ref([]);
    const promotions = ref([]);
    const categories = ref([]);
    const languages = ref([]);
    const authors = ref([]);

    const loadOptions = () => {
      axios
        .get(
          "http://localhost/LVTN/book-store/src/api/admin/get_publishers.php"
        )
        .then((res) => {
          publishers.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách nhà xuất bản: " + err);
        });

      axios
        .get(
          "http://localhost/LVTN/book-store/src/api/admin/get_promotions.php"
        )
        .then((res) => {
          promotions.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách khuyến mãi: " + err);
        });

      axios
        .get(
          "http://localhost/LVTN/book-store/src/api/admin/get_categories.php"
        )
        .then((res) => {
          categories.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách danh mục: " + err);
        });

      axios
        .get("http://localhost/LVTN/book-store/src/api/admin/get_author.php")
        .then((res) => {
          authors.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách tác giả: " + err);
        });

      axios
        .get("http://localhost/LVTN/book-store/src/api/admin/get_languages.php")
        .then((res) => {
          languages.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách ngôn ngữ: " + err);
        });
    };

    watch(
      () => props.visible,
      (newVal) => {
        if (newVal) {
          resetForm();
          loadOptions();
        }
      }
    );

    watch(
      () => props.bookData,
      (newVal) => {
        book.value = { ...newVal };
      }
    );

    const updateBook = () => {
      const bookData = {
        MaSach: book.value.MaSach,
        TenSach: book.value.TenSach,
        SoLuong: book.value.SoLuong,
        DonGia: book.value.DonGia,
        ChiTiet: book.value.ChiTiet,
        HinhAnh: book.value.HinhAnh,
        NhaXuatBan: book.value.MaNhaXuatBan,
        KhuyenMai: book.value.MaKhuyenMai,
        DanhMuc: book.value.MaDanhMuc,
        TacGia: book.value.MaTacGia,
        NgonNgu: book.value.MaNgonNgu,
      };

      console.log("Sending book data:", bookData);
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/updateBook.php",
          JSON.stringify(bookData),
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((res) => {
          if (res.data === "Cap Nhat Thanh Cong") {
            message.success("Cập nhật sách thành công");
            emit("update:visible", false);
            emit("book-updated");
            resetForm();
          } else {
            message.error("Cập nhật sách không thành công");
          }
        })
        .catch((err) => {
          message.error("Có lỗi khi cập nhật sách: " + err);
        });
    };

    const handleCancel = () => {
      emit("update:visible", false);
    };

    return {
      book,
      publishers,
      promotions,
      updateBook,
      handleCancel,
      categories,
      languages,
      authors,
    };
  },
};
</script>
