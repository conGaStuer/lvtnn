<template>
  <div class="dashboard">
    <div class="card flex justify-content-center">
      <Chart
        type="pie"
        :data="pieChartData"
        :options="chartOptions"
        class="w-full md:w-30rem"
      />
    </div>
    <Chart type="bar" :data="barChartData" :options="chartOptions" />

    <a-card title="Danh sách người dùng" class="k">
      <a-table :columns="userColumns" :data-source="userData" />
    </a-card>
    <div>
      <button style="cursor: pointer" @click="handleLogout">
        {{ currentUser ? "Đăng xuất" : "Đăng nhập" }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from "vue";
import Chart from "primevue/chart";
import axios from "axios";
import { useRouter } from "vue-router";

const pieChartData = ref(null);
const barChartData = ref(null);
const chartOptions = ref({
  plugins: {
    legend: {
      labels: {
        usePointStyle: true,
        color: "#495057",
      },
    },
  },
});
const currentUser = JSON.parse(localStorage.getItem("currentUser"));

const fetchChartData = async () => {
  try {
    const response = await axios.get(
      "http://localhost/LVTN/book-store/src/api/admin/chartdata.php"
    );
    const data = response.data;

    // Process revenue data for pie chart
    pieChartData.value = {
      labels: data.revenue.map((item) => `Tháng ${item.month}`),
      datasets: [
        {
          data: data.revenue.map((item) => parseFloat(item.total_revenue)),
          backgroundColor: [
            "#42A5F5",
            "#66BB6A",
            "#FFA726",
            "#26C6DA",
            "#7E57C2",
          ],
          hoverBackgroundColor: [
            "#64B5F6",
            "#81C784",
            "#FFB74D",
            "#4DD0E1",
            "#B39DDB",
          ],
        },
      ],
    };

    // Process orders data for bar chart
    barChartData.value = {
      labels: data.orders.map((item) => `Tháng ${item.month}`),
      datasets: [
        {
          label: "Đơn hàng",
          backgroundColor: "#42A5F5",
          data: data.orders.map((item) => parseInt(item.orders)),
        },
      ],
    };
  } catch (error) {
    console.error("Error fetching chart data:", error);
  }
};

onMounted(() => {
  fetchChartData();
});

const router = useRouter();
const handleLogout = async () => {
  try {
    const response = await axios.get(
      "http://localhost/LVTN/book-store/src/api/admin/logout.php"
    );
    if (response) {
      localStorage.removeItem("currentUser");
      router.push("/login-admin");
    } else {
      alert("Đã xảy ra lỗi khi đăng xuất");
    }
  } catch (error) {
    console.error("Lỗi:", error);
    alert("Đã xảy ra lỗi khi đăng xuất");
  }
};

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
button {
  width: 150px;
  height: 40px;
  border-radius: 5px;
  border: none;
  cursor: pointer;
  background-color: black;
  color: white;
  font-weight: bold;
}
</style>
