<template>
  <div class="order-process">
    <a-progress :percent="progressPercent" status="active" />
    <div class="order-details">
      <h1>Mã đơn hàng: {{ orderId }}</h1>
      <a-steps direction="vertical" size="small">
        <a-step
          v-for="(event, index) in orderEvents"
          :key="index"
          :title="getEventTitle(event.event)"
          :description="formatTimestamp(event.timestamp)"
          :status="getStepStatus(index)"
          :icon="getStepIcon(event.event)"
        />
      </a-steps>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, h } from "vue";
import axios from "axios";
import { useRoute } from "vue-router";
import { message } from "ant-design-vue";
import {
  CheckCircleOutlined,
  CreditCardOutlined,
  CarOutlined,
  SmileOutlined,
} from "@ant-design/icons-vue";

const route = useRoute();
const orderId = route.params.id;
const orderEvents = ref([]);
const progressPercent = ref(0);

const stages = [
  { key: "choduyet", title: "Chờ duyệt", icon: CreditCardOutlined },
  { key: "daduyet", title: "Đã duyệt", icon: CheckCircleOutlined },
  { key: "danggiao", title: "Đang giao", icon: CarOutlined },
  {
    key: "giaohangthanhcong",
    title: "Giao hàng thành công",
    icon: SmileOutlined,
  },
];

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
  if (orderEvents.value.length === 0) {
    progressPercent.value = 0;
  } else {
    const currentStageIndex = stages.findIndex(
      (stage) =>
        stage.key === orderEvents.value[orderEvents.value.length - 1].event
    );
    progressPercent.value = ((currentStageIndex + 1) / stages.length) * 100;
  }
};

const formatTimestamp = (timestamp) => {
  const date = new Date(timestamp);
  return date.toLocaleString();
};

const getEventTitle = (eventKey) => {
  const stage = stages.find((stage) => stage.key === eventKey);
  return stage ? stage.title : eventKey;
};

const getStepIcon = (eventKey) => {
  const stage = stages.find((stage) => stage.key === eventKey);
  return stage ? h(stage.icon) : null;
};

const getStepStatus = (index) => {
  return index < orderEvents.value.length ? "finish" : "wait";
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

.ant-steps-item-finish .ant-steps-item-icon {
  background-color: #1890ff;
  border-color: #1890ff;
}

.ant-steps-item-wait .ant-steps-item-icon {
  background-color: #f0f0f0;
  border-color: #d9d9d9;
}

.ant-steps-item-finish .ant-steps-item-title,
.ant-steps-item-finish .ant-steps-item-description {
  color: rgba(0, 0, 0, 0.85);
}

.ant-steps-item-wait .ant-steps-item-title,
.ant-steps-item-wait .ant-steps-item-description {
  color: rgba(0, 0, 0, 0.45);
}
</style>
