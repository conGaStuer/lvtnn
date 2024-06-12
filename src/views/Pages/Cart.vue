<template>
  <NavBar></NavBar>

  <div class="shopping-bag">
    <div class="title">
      <h1>Giỏ hàng</h1>
      <p>Có {{ cartlength }} trong giỏ hàng của bạn</p>
    </div>
    <div class="cart-container">
      <div class="product-list">
        <div class="info">
          <div>Hình Ảnh</div>
          <div>Thông tin</div>
          <div>Đơn giá</div>
          <div>Số Lượng</div>
          <div>Thành tiền</div>
        </div>
        <router-link
          :to="{ name: 'bookDetail', params: { id: item.MaSach } }"
          class="product-item"
          v-for="item in cartItems"
          :key="item.MaSach"
        >
          <img :src="item.HinhAnh" alt="product-image" />
          <div class="product-details">
            <p class="name">{{ item.TenSach }}</p>
            <h4>{{ item.DanhMuc }}</h4>
            <p>
              Tác giả : <span>{{ item.TacGia }}</span>
            </p>
            <p>
              Nhà xuất bản : <span>{{ item.NhaXuatBan }}</span>
            </p>
            <p>
              Ngôn Ngữ : <span>{{ item.NgonNgu }}</span>
            </p>
          </div>
          <div class="product-price">
            <p>{{ item.DonGia }}</p>
          </div>
          <div class="product-quantity">
            <button>-</button>
            <span>{{ item.SoLuong }}</span>
            <button>+</button>
          </div>
          <div class="total-price">
            <p>{{ item.DonGia * item.SoLuong }}</p>
          </div>
        </router-link>
      </div>
    </div>
  </div>
</template>
<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import NavBar from "@/views/UI_Components/NavBar.vue";
import { PrimeIcons } from "primevue/api";
import Footer from "@/views/UI_Components/Footer.vue";
export default {
  components: {
    NavBar,
    Footer,
  },
  setup() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser"));
    const userData = ref({ ...currentUser });
    const cartItems = ref([]);
    const userId = currentUser.maND;
    const cartlength = computed(() => {
      return cartItems.value.length;
    });
    onMounted(() => {
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getCart.php?userId=${userId}`
        )
        .then((res) => {
          if (res.data) {
            cartItems.value = res.data;
          } else {
            console.log("Khong co san pham");
          }
        })
        .catch((err) => {
          console.log("Error", err);
        });
    });
    return { currentUser, userData, cartItems, cartlength };
  },
};
</script>
<style scoped>
@import "@/assets/styles/cart.scss";
</style>
