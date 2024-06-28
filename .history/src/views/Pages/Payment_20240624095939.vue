<template>
  <div class="payment-method">
    <h1>Chọn phương thức thanh toán</h1>
    <div class="method-container">
      <button @click="payWithCOD">Thanh toán khi nhận hàng (COD)</button>
      <button @click="payWithOnline">Thanh toán online</button>
    </div>
  </div>
</template>

<script>
import { useRouter, useRoute } from "vue-router";
import axios from "axios";

export default {
  setup() {
    const router = useRouter();
    const route = useRoute();
    const orderData = JSON.parse(route.params.orderData);

    const payWithCOD = () => {
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/addOrder.php",
          orderData
        )
        .then((response) => {
          if (response.data.status === "success") {
            router.replace("/order");
          } else {
            alert(response.data.message);
          }
        })
        .catch((err) => console.log("Error", err));
    };

    const payWithOnline = () => {
      router.push({
        name: "Payment",
        params: { orderData: JSON.stringify(orderData) },
      });
    };

    return {
      payWithCOD,
      payWithOnline,
    };
  },
};
</script>

<style scoped>
.payment-method {
  text-align: center;
  padding: 20px;
}

.method-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

button {
  padding: 10px 20px;
  font-size: 16px;
}
</style>
