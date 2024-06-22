<template>
  <div class="order-process">
    <a-progress :percent="progressPercent" status="active" />
    <div class="order-details">
      <h1>Mã đơn hàng: {{ orderId }}</h1>
      <a-steps direction="vertical" size="small">
        <a-step
          v-for="(event, index) in orderEvents"
          :key="index"
          :title="event.event"
          :description="formatTimestamp(event.timestamp)"
        />
      </a-steps>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import { message } from "ant-design-vue";

const route = useRoute();
const orderId = route.params.id;
const orderEvents = ref([]);
const progressPercent = ref(0);

const fetchOrderDetails = async () => {
  try {
    const response = await axios.get(
      `http://localhost/LVTN/book-store/src/api/getDetail.php?orderId=${orderId}`
    );
    orderEvents.value = response.data;
    calculateProgress();
  } catch (error) {
    message.error("Lỗi khi lấy chi tiết đơn hàng.");
    console.error(error);
  }
};

const calculateProgress = () => {
  const stages = [
    "Đơn hàng đã đặt",
    "Đã xác nhận thông tin thanh toán",
    "Đã giao cho ĐVVC",
    "Đã nhận được hàng",
    "Đơn hàng đã hoàn thành",
  ];
  if (orderEvents.value.length === 0) {
    progressPercent.value = 0;
  } else {
    const currentStageIndex = stages.findIndex(
      (stage) => stage === orderEvents.value[orderEvents.value.length - 1].event
    );
    progressPercent.value = ((currentStageIndex + 1) / stages.length) * 100;
  }
};

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);
  return date.toLocaleString();
};

onMounted(() => {
  fetchOrderDetails();
});
</script>

<style scoped>
.order-process {
  padding: 20px;
}

.order-details {
  margin-top: 20px;
}

a-progress {
  margin-bottom: 20px;
}

a-steps {
  margin-top: 20px;
}
</style>
