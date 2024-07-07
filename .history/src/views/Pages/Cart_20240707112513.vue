<template>
  <NavBar></NavBar>
  <div v-if="popUp" class="popUp">
    <p>
      Vui lòng cập nhật đầy đủ tên, số điện thoại, email, địa chỉ trong phần hồ
      sơ để đơn hàng được duyệt
    </p>
  </div>
  <div class="shopping-bag">
    <div class="title">
      <h1>Giỏ hàng</h1>
      <p>Có {{ cartlength }} sản phẩm trong giỏ hàng của bạn</p>
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
            <input
              type="checkbox"
              v-model="selectedItems"
              :value="item.MaSach"
              class="checkbox"
            />
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
            <i class="pi pi-times" @click="deleteItem(item)"></i>
          </div>
          <button class="place1" @click="placeOrder('COD')">
            Đặt hàng (COD)
          </button>
          <button class="place2" @click="placeOrder('ZaloPay')">
            Đặt hàng (ZaloPay)
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from "vue";
import axios from "axios";
import NavBar from "@/views/UI_Components/NavBar.vue";
import Footer from "@/views/UI_Components/Footer.vue";
import { useRouter } from "vue-router";

export default {
  components: {
    NavBar,
    Footer,
  },
  setup() {
    const currentUser = JSON.parse(localStorage.getItem("currentUser")) || {};
    const userId = currentUser.maND;
    const cartItems = ref([]);
    const selectedItems = ref([]);
    const router = useRouter();
    const cartlength = computed(() => cartItems.value.length);

    const incrementQuantity = (item) =>
      updateQuantity(item, Number(item.SoLuong) + 1);
    const decrementQuantity = (item) => {
      if (item.SoLuong > 1) updateQuantity(item, item.SoLuong - 1);
    };

    const deleteItem = (item) => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/deleteCart.php", {
          maSach: item.MaSach,
          soLuong: item.SoLuong,
          maND: currentUser.maND,
        })
        .then((response) => {
          if (response.data) {
            const index = cartItems.value.findIndex(
              (cartItem) => cartItem.MaSach === item.MaSach
            );
            if (index !== -1) cartItems.value.splice(index, 1);
          }
        })
        .catch((error) => console.error("Error deleting item:", error));
    };

    const updateQuantity = (item, newQuantity) => {
      axios
        .post("http://localhost/LVTN/book-store/src/api/checkStock1.php", {
          maSach: item.MaSach,
          soLuong: newQuantity,
        })
        .then((res) => {
          if (res.data.status === "success") {
            axios
              .post(
                "http://localhost/LVTN/book-store/src/api/editQuantity.php",
                {
                  maSach: item.MaSach,
                  soLuong: newQuantity,
                }
              )
              .then((response) => {
                if (
                  response.data.message === "Quantity updated successfully."
                ) {
                  item.SoLuong = newQuantity;
                }
              })
              .catch((error) =>
                console.error("Error updating quantity:", error)
              );
          } else {
            alert(res.data.message);
          }
        })
        .catch((error) => console.error("Error checking stock:", error));
    };

    const fetchCart = () => {
      axios
        .get(
          `http://localhost/LVTN/book-store/src/api/getCart.php?userId=${userId}`
        )
        .then((res) => {
          if (res.data) cartItems.value = res.data;
          else console.log("Không có sản phẩm");
        })
        .catch((err) => console.log("Error", err));
    };

    const placeOrder = (paymentMethod) => {
      if (selectedItems.value.length === 0) {
        alert("Vui lòng chọn ít nhất 1 sản phẩm để đặt hàng!!");
        return;
      }
      const selectedCartItems = cartItems.value.filter((item) =>
        selectedItems.value.includes(item.MaSach)
      );
      const orderData = {
        userId: userId,
        items: selectedCartItems,
        paymentMethod: paymentMethod,
      };

      if (paymentMethod === "COD") {
        if (currentUser.diachi != "") {
          axios
            .post(
              "http://localhost/LVTN/book-store/src/api/addOrder.php",
              orderData
            )
            .then((response) => {
              if (response.data.status === "success") {
                fetchCart(); // Cập nhật lại giỏ hàng sau khi đặt hàng
                selectedItems.value = []; // Xóa lựa chọn các sản phẩm đã đặt
                router.replace("/order");
              } else {
                alert(response.data.message);
              }
            })
            .catch((err) => console.log("Error", err));
        } else {
          alert("Vui lòng cập nhật địa chỉ và email trong hồ sơ để đặt hàng");
        }
      } else if (paymentMethod === "ZaloPay") {
        axios
          .post(
            "http://localhost/LVTN/book-store/src/api/createZaloPayOrder.php",
            orderData
          )
          .then((response) => {
            if (response.data.status === "success") {
              window.location.href = response.data.payment_url;
            } else {
              alert(response.data.message);
            }
          })
          .catch((err) => console.log("Error", err));
      }
    };
    const popUp = ref(true);

    onMounted(() => {
      fetchCart();
      setTimeout(() => {
        popUp.value = false;
      }, 3000);
    });

    return {
      currentUser,
      cartItems,
      cartlength,
      incrementQuantity,
      decrementQuantity,
      deleteItem,
      selectedItems,
      placeOrder,
      popUp,
    };
  },
};
</script>

<style scoped>
@import "@/assets/styles/cart.scss";
.popUp {
  margin: 100px auto;
  width: 500px;
  height: 200px;
  position: absolute;
  left: 300px;
  z-index: 1000000;
  background-color: aquamarine;
}
</style>
