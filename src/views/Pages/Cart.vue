<template>
  <NavBar></NavBar>

  <div class="shopping-bag">
    <div class="title">
      <h1>Shopping Bag</h1>
      <p>2 items in your bag.</p>
    </div>
    <div class="cart-container">
      <div class="product-list">
        <div
          class="product-item"
          v-for="(item, index) in products"
          :key="index"
        >
          <img :src="item.img" alt="product-image" />
          <div class="product-details">
            <p>HOANG DÃ, ĐỘNG VẬT</p>
            <h4>Động vật hoang dã</h4>
            <p>Tác giả : <span>Phan Tấn Trung</span></p>
            <p>Nhà xuất bản : <span>SBTC</span></p>
          </div>
          <div class="product-price">
            <p>20000 đồng</p>
          </div>
          <div class="product-quantity">
            <button @click="decrementQty(index)">-</button>
            <span>{{ item.quantity }}</span>
            <button @click="incrementQty(index)">+</button>
          </div>
          <div class="total-price">
            <p>80000 đồng</p>
          </div>
        </div>
      </div>
      <div class="summary">
        <div class="calculated-shipping">
          <h4>Calculated Shipping</h4>
          <select v-model="country">
            <option value="Country">Country</option>
          </select>
          <input type="text" placeholder="State / City" v-model="city" />
          <input type="text" placeholder="ZIP Code" v-model="zip" />
          <button @click="updateShipping">Update</button>
        </div>
        <div class="coupon-code">
          <h4>Coupon Code</h4>
          <input type="text" placeholder="Coupon Code" v-model="coupon" />
          <button @click="applyCoupon">Apply</button>
        </div>
        <div class="cart-total">
          <p>Cart Subtotal: \${{ cartSubtotal.toFixed(2) }}</p>
          <p>Design by Fluttertop: Free</p>
          <p>Discount: \${{ discount.toFixed(2) }}</p>
          <h3>Cart Total: \${{ cartTotal.toFixed(2) }}</h3>
          <button @click="applyTotal">Apply</button>
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
    const country = ref("");
    const city = ref("");
    const zip = ref("");
    const coupon = ref("");

    const products = ref([
      {
        img: require("@/assets/images/cate2.jpg"),
        color: "Blue",
        size: 42,
        price: 20.5,
        quantity: 2,
      },

      {
        img: require("@/assets/images/cate3.jpg"),
        color: "Blue",
        size: 42,
        price: 30.5,
        quantity: 1,
      },
    ]);

    const decrementQty = (index) => {
      if (products.value[index].quantity > 1) {
        products.value[index].quantity--;
      }
    };

    const incrementQty = (index) => {
      products.value[index].quantity++;
    };

    const cartSubtotal = computed(() => {
      return products.value.reduce(
        (sum, item) => sum + item.price * item.quantity,
        0
      );
    });

    const discount = ref(4.0);

    const cartTotal = computed(() => {
      return cartSubtotal.value - discount.value;
    });

    const updateShipping = () => {
      console.log(
        "Update shipping details:",
        country.value,
        city.value,
        zip.value
      );
    };

    const applyCoupon = () => {
      console.log("Apply coupon:", coupon.value);
    };

    const applyTotal = () => {
      console.log("Cart total applied:", cartTotal.value);
    };
    return {
      country,
      city,
      zip,
      coupon,
      products,
      decrementQty,
      incrementQty,
      cartSubtotal,
      discount,
      cartTotal,

      updateShipping,
      applyCoupon,
      applyTotal,
    };
  },
};
</script>
<style scoped>
@import "@/assets/styles/cart.scss";
</style>
