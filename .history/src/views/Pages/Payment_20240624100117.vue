<template>
  <div class="payment">
    <h1>Thanh toán online</h1>
    <div v-if="qrCodeUrl">
      <h2>Quét mã QR để thanh toán</h2>
      <img :src="qrCodeUrl" alt="QR Code" />
    </div>
    <div v-else>
      <p>Đang tạo mã QR, vui lòng đợi...</p>
    </div>
  </div>
</template>

<script>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";

export default {
  setup() {
    const qrCodeUrl = ref(null);
    const route = useRoute();
    const orderData = JSON.parse(route.params.orderData);

    const createZaloPayOrder = async () => {
      try {
        const response = await axios.post(
          "http://localhost/LVTN/book-store/src/api/createZaloPayRequest.php",
          orderData
        );
        qrCodeUrl.value = response.data.orderurl;
      } catch (error) {
        console.error("Error creating ZaloPay order:", error);
      }
    };

    onMounted(createZaloPayOrder);

    return {
      qrCodeUrl,
    };
  },
};
</script>

<style scoped>
.payment {
  text-align: center;
  padding: 20px;
}

img {
  width: 300px;
  height: 300px;
}
</style>
