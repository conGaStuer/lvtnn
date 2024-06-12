<template>
  <a-modal
    :open="visible"
    title="Thêm Sách"
    @cancel="handleCancel"
    @ok="addBook"
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
        <a-select v-model:value="book.NhaXuatBan" style="width: 100%">
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
        <a-select v-model:value="book.KhuyenMai" style="width: 100%">
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
          v-model:value="book.DanhMuc"
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
          v-model:value="book.TacGia"
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
        <a-select v-model:value="book.NgonNgu" style="width: 100%">
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
      TenSach: "",
      SoLuong: 0,
      DonGia: 0,
      ChiTiet: "",
      HinhAnh: "",
      NhaXuatBan: null,
      KhuyenMai: null,
      DanhMuc: null,
      TacGia: null,
      NgonNgu: null,
    });

    const resetForm = () => {
      book.value = {
        TenSach: "",
        SoLuong: 0,
        DonGia: 0,
        ChiTiet: "",
        HinhAnh: "",
        NhaXuatBan: null,
        KhuyenMai: null,
        DanhMuc: null,
        TacGia: null,
        NgonNgu: null,
      };
    };

    const loadPublishers = () => {
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
    };

    const loadPromotions = () => {
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
    };
    const loadCategories = () => {
      axios
        .get(
          "http://localhost/LVTN/book-store/src/api/admin/get_categories.php"
        )
        .then((res) => {
          categories.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách khuyến mãi: " + err);
        });
    };
    const loadAuthors = () => {
      axios
        .get("http://localhost/LVTN/book-store/src/api/admin/get_author.php")
        .then((res) => {
          authors.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách khuyến mãi: " + err);
        });
    };
    const loadLanguages = () => {
      axios
        .get("http://localhost/LVTN/book-store/src/api/admin/get_languages.php")
        .then((res) => {
          languages.value = res.data;
        })
        .catch((err) => {
          message.error("Có lỗi khi tải danh sách khuyến mãi: " + err);
        });
    };
    const publishers = ref([]);
    const promotions = ref([]);
    const categories = ref([]);
    const languages = ref([]);
    const authors = ref([]);
    watch(
      () => props.visible,
      (newVal) => {
        if (newVal) {
          resetForm();
          loadPublishers();
          loadPromotions();
          loadCategories();
          loadAuthors();
          loadLanguages();
        }
      }
    );
    const addBook = () => {
      const bookData = {
        TenSach: book.value.TenSach,
        SoLuong: book.value.SoLuong,
        DonGia: book.value.DonGia,
        ChiTiet: book.value.ChiTiet,
        HinhAnh: book.value.HinhAnh,
        NhaXuatBan: book.value.NhaXuatBan,
        KhuyenMai: book.value.KhuyenMai,
        DanhMuc: book.value.DanhMuc,
        TacGia: book.value.TacGia,
        NgonNgu: book.value.NgonNgu,
      };

      console.log("Sending book data:", bookData);
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/admin/addBook.php",
          JSON.stringify(bookData), // Convert to JSON string
          {
            headers: {
              "Content-Type": "application/json", // Set Content-Type header to application/json
            },
          }
        )
        .then((res) => {
          if (res.data === "Them Thanh Cong") {
            message.success("Thêm sách thành công");
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
      publishers,
      promotions,
      addBook,
      handleCancel,
      categories,
      languages,
      authors,
    };
  },
};
</script>
