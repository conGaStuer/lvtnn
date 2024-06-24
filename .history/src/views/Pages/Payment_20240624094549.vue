<template>
  <div class="payment">
    <h1>Processing your payment...</h1>
  </div>
</template>

<script>
import { onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";

export default {
  setup() {
    const route = useRoute();

    onMounted(() => {
      const { amount, description } = route.query;
      axios
        .post(
          "http://localhost/LVTN/book-store/src/api/createZaloPayRequest.php",
          { amount, description }
        )
        .then((response) => {
          if (response.data.return_code === 1) {
            window.location.href = response.data.order_url;
          } else {
            alert(response.data.return_message);
          }
        })
        .catch((error) =>
          console.error("Error creating ZaloPay order:", error)
        );
    });
  },
};
</script>

<style scoped>
.payment {
  text-align: center;
  margin-top: 50px;
}
</style>
