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
        <div v-if="cartItems.length">
          <div
            class="product-item"
            v-for="item in cartItems"
            :key="item.MaSach"
          >
            <img :src="item.HinhAnh" alt="product-image" />
            <router-link
              :to="{ name: 'bookDetail', params: { id: item.MaSach } }"
              class="product-details"
            >
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
            </router-link>
            <div class="product-price">
              <p>{{ item.DonGia }}</p>
            </div>
            <div class="product-quantity">
              <button @click="decrementQuantity(item)">-</button>
              <span>{{ item.SoLuong }}</span>
              <button @click="incrementQuantity(item)">+</button>
            </div>
            <div class="total-price">
              <p>{{ item.DonGia * item.SoLuong }}</p>
            </div>
          </div>
        </div>
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
    const incrementQuantity = (item) => {
      updateQuantity(item, Number(item.SoLuong) + 1);
    };

    const decrementQuantity = (item) => {
      if (item.SoLuong > 1) {
        updateQuantity(item, item.SoLuong - 1);
      }
    };

    const updateQuantity = (item, newQuantity) => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/editQuantity.php", {
          maSach: item.MaSach,
          soLuong: newQuantity,
        })
        .then((response) => {
          if (response.data.message === "Quantity updated successfully.") {
            item.SoLuong = newQuantity;
          }
        })
        .catch((error) => {
          console.error("Error updating quantity:", error);
        });
    };
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
    return {
      currentUser,
      userData,
      cartItems,
      cartlength,
      incrementQuantity,
      decrementQuantity,
    };
  },
};
</script>
<style scoped>
@import "@/assets/styles/cart.scss";
</style>
