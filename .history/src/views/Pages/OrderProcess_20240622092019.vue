<template>
  <div class="order-process">
    <div class="progress-container">
      <a-progress :percent="progressPercent" status="active" />
      <div class="icon-steps">
        <div
          v-for="(stage, index) in stages"
          :key="stage.key"
          class="icon-step"
          :style="{ left: `${index * 25}%` }"
        >
          <div class="icon-circle" :class="{ active: isActive(index) }">
            <component :is="stage.icon" />
          </div>
          <p>{{ stage.title }}</p>
        </div>
      </div>
    </div>
    <div class="order-details" v-if="orderEvents.length">
      <h1>Mã đơn hàng: {{ orderId }}</h1>
      <a-steps direction="vertical" size="small">
        <a-step
          v-for="(event, index) in orderEvents"
          :key="index"
          :title="getEventTitle(event.event)"
          :description="formatTimestamp(event.timestamp)"
          :status="getStepStatus(index)"
        />
      </a-steps>
    </div>
    <div v-else>
      <p>Không có chi tiết đơn hàng</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
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
        stage.key === orderEvents.value[orderEvents.value.length - 1]?.event
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

const isActive = (index) => {
  const lastEvent = orderEvents.value[orderEvents.value.length - 1];
  if (!lastEvent) return false;
  return index <= stages.findIndex((stage) => stage.key === lastEvent.event);
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
  position: relative;
}
.ant-progress-inner {
  position: relative;
  top: 20px;
}
.progress-container {
  position: relative;
  margin-bottom: 50px;
}

.icon-steps {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  display: flex;
  justify-content: space-between;
  pointer-events: none;
}

.icon-step {
  position: absolute;
  text-align: center;
}

.icon-circle {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  background-color: #f0f0f0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.icon-circle.active {
  background-color: #1890ff;
}

.icon-step p {
  margin-top: 8px;
  font-size: 12px;
}

.order-details {
  margin-top: 40px;
}

a-progress {
  margin-bottom: 40px;
  position: relative;
  top: 20px;
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
