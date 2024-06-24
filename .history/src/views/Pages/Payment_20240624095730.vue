<template>
  <div class="payment-online">
    <h1>Thanh toán online</h1>
    <div v-if="paymentUrl">
      <p>Nhấn vào nút bên dưới để tiến hành thanh toán qua Zalo Pay.</p>
      <a :href="paymentUrl" target="_blank" class="pay-button"
        >Thanh toán Zalo Pay</a
      >
    </div>
    <div v-else>
      <p>Đang tạo yêu cầu thanh toán...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const paymentUrl = ref("");

onMounted(() => {
  createPaymentRequest();
});

const createPaymentRequest = () => {
  axios
    .post("http://localhost/LVTN/book-store/src/api/createZaloPayRequest.php", {
      amount: 100000, // Số tiền cần thanh toán (có thể thay đổi tùy theo giỏ hàng)
      description: "Thanh toán đơn hàng sách",
    })
    .then((response) => {
      paymentUrl.value = response.data.payment_url;
    })
    .catch((error) => {
      console.error("Error creating payment request:", error);
    });
};
</script>

<style scoped>
.payment-online {
  padding: 20px;
}

.pay-button {
  display: inline-block;
  padding: 10px 20px;
  background-color: #4caf50;
  color: white;
  text-decoration: none;
  border-radius: 5px;
  font-size: 16px;
}
</style>
