<template>
  <div class="payment-method">
    <h1>Thanh toán online</h1>
    <div v-if="qrCodeUrl">
      <p>Đang tạo mã QR, vui lòng đợi...</p>
      <img :src="qrCodeUrl" alt="QR Code" />
    </div>
    <div v-else>
      <button @click="generateQRCode">Tạo mã QR</button>
    </div>
  </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
  setup() {
    const qrCodeUrl = ref(null);

    const generateQRCode = () => {
      const currentUser = JSON.parse(localStorage.getItem("currentUser"));
      const userId = currentUser.maND;
      const selectedCartItems = cartItems.value.filter((item) =>
        selectedItems.value.includes(item.MaSach)
      );
      const orderData = {
        userId: userId,
        items: selectedCartItems,
      };

      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/createZaloPayRequest.php",
          orderData
        )
        .then((response) => {
          if (response.data && response.data.qrCodeUrl) {
            qrCodeUrl.value = response.data.qrCodeUrl;
          } else {
            alert("Không thể tạo mã QR. Vui lòng thử lại sau.");
          }
        })
        .catch((error) => {
          console.error("Error creating ZaloPay QR Code:", error);
          alert("Đã xảy ra lỗi. Vui lòng thử lại sau.");
        });
    };

    return {
      qrCodeUrl,
      generateQRCode,
    };
  },
};
</script>

<style scoped>
.payment-method {
  text-align: center;
  margin-top: 20px;
}
.payment-method button {
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}
.payment-method img {
  max-width: 300px;
}
</style>
