<template>
  <div class="dashboard">
    <div class="card flex justify-content-center">
      <Chart
        type="pie"
        :data="chartData"
        :options="chartOptions"
        class="w-full md:w-30rem"
      />
    </div>
    <Chart type="bar" :data="chartData" :options="chartOptions" />

    <Chart
      type="bar"
      :data="chartData"
      :options="chartOptions"
      class="h-30rem"
    />
    <Chart
      type="line"
      :data="chartData"
      :options="chartOptions"
      class="h-30rem"
    />
    <Chart
      type="bar"
      :data="chartData"
      :options="chartOptions"
      class="h-30rem"
    />

    <a-card title="Danh sách người dùng" class="k">
      <a-table :columns="userColumns" :data-source="userData" />
    </a-card>
    <div>
      <a style="cursor: pointer" @click="handleLogout">{{
        currentUser ? "Đăng xuất" : "Đăng nhập"
      }}</a>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from "vue";
import Chart from "primevue/chart";

const chartData = ref();
const chartOptions = ref();

onMounted(() => {
  chartData.value = setChartData();
  chartOptions.value = setChartOptions();
});

const setChartData = () => {
  const documentStyle = getComputedStyle(document.body);

  return {
    labels: ["A", "B", "C"],
    datasets: [
      {
        data: [540, 325, 702],
        backgroundColor: [
          documentStyle.getPropertyValue("--cyan-500"),
          documentStyle.getPropertyValue("--orange-500"),
          documentStyle.getPropertyValue("--gray-500"),
        ],
        hoverBackgroundColor: [
          documentStyle.getPropertyValue("--cyan-400"),
          documentStyle.getPropertyValue("--orange-400"),
          documentStyle.getPropertyValue("--gray-400"),
        ],
      },
    ],
  };
};

const setChartOptions = () => {
  const documentStyle = getComputedStyle(document.documentElement);
  const textColor = documentStyle.getPropertyValue("--text-color");

  return {
    plugins: {
      legend: {
        labels: {
          usePointStyle: true,
          color: textColor,
        },
      },
    },
  };
};

const revenueChartOptions = reactive({
  title: {
    text: "Biểu đồ doanh thu",
  },
  xAxis: {
    categories: [
      "Tháng 1",
      "Tháng 2",
      "Tháng 3",
      "Tháng 4",
      "Tháng 5",
      "Tháng 6",
    ],
  },
  yAxis: {
    title: {
      text: "Doanh thu (đơn vị)",
    },
  },
  series: [
    {
      name: "Doanh thu",
      data: [1000, 1500, 2000, 1800, 2500, 3000],
    },
  ],
  // Các tùy chọn khác cho biểu đồ doanh thu
});

const ordersChartOptions = reactive({
  title: {
    text: "Biểu đồ đơn hàng",
  },
  xAxis: {
    categories: [
      "Tháng 1",
      "Tháng 2",
      "Tháng 3",
      "Tháng 4",
      "Tháng 5",
      "Tháng 6",
    ],
  },
  yAxis: {
    title: {
      text: "Số lượng đơn hàng",
    },
  },
  series: [
    {
      name: "Đơn hàng",
      data: [50, 60, 70, 65, 80, 90],
    },
  ],
  // Các tùy chọn khác cho biểu đồ đơn hàng
});

const userColumns = reactive([
  { title: "Tên", dataIndex: "name", key: "name" },
  { title: "Tuổi", dataIndex: "age", key: "age" },
  { title: "Email", dataIndex: "email", key: "email" },
]);

const userData = reactive([
  { key: "1", name: "Người dùng 1", age: 30, email: "user1@example.com" },
  { key: "2", name: "Người dùng 2", age: 25, email: "user2@example.com" },
  { key: "3", name: "Người dùng 3", age: 40, email: "user3@example.com" },
]);
</script>

<style>
.dashboard {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  grid-gap: 20px;
}
.k {
  position: relative;
  top: -150px;
}
</style>
