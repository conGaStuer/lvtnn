<template>
  <div class="dashboard">
    <div class="card flex justify-content-center">
      <Chart
        v-if="pieChartData && barChartData"
        type="pie"
        :data="pieChartData"
        :options="chartOptions"
        class="w-full md:w-30rem"
      />
      <div v-if="pieChartData" class="chart-label">
        <p>
          {{ pieChartData.labels[0] }}: {{ pieChartData.datasets[0].data[0] }}
        </p>
        <p>
          {{ pieChartData.labels[1] }}: {{ pieChartData.datasets[0].data[1] }}
        </p>
        <p>
          {{ pieChartData.labels[2] }}: {{ pieChartData.datasets[0].data[2] }}
        </p>
      </div>
    </div>
    <div class="card flex justify-content-center">
      <Chart
        v-if="barChartData && barChartData.value"
        type="bar"
        :data="barChartData"
        :options="chartOptions"
        class="w-full md:w-30rem"
      />
      <div v-if="barChartData" class="chart-label">
        <p>
          {{ barChartData.labels[0] }}: {{ barChartData.datasets[0].data[0] }}
        </p>
        <p>
          {{ barChartData.labels[1] }}: {{ barChartData.datasets[0].data[1] }}
        </p>
        <p>
          {{ barChartData.labels[2] }}: {{ barChartData.datasets[0].data[2] }}
        </p>
      </div>
    </div>

    <div>
      <button style="cursor: pointer" @click="handleLogout">
        {{ currentUser ? "Đăng xuất" : "Đăng nhập" }}
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import Chart from "primevue/chart";
import axios from "axios";
import { useRouter } from "vue-router";

const pieChartData = ref(null);
const barChartData = ref(null);
const chartOptions = ref(null);
const currentUser = JSON.parse(localStorage.getItem("currentUser"));

const fetchChartData = async () => {
  try {
    const response = await axios.get(
      "http://localhost/LVTN/book-store/src/api/admin/chartdata.php"
    );
    const data = response.data;

    pieChartData.value = {
      labels: data.revenue.map((item) => `Tháng ${item.month}`),
      datasets: [
        {
          data: data.revenue.map((item) => item.total_revenue),
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

    barChartData.value = {
      labels: data.orders.map((item) => `Tháng ${item.month}`),
      datasets: [
        {
          label: "Đơn hàng",
          backgroundColor: "#42A5F5",
          data: data.orders.map((item) => item.orders),
        },
      ],
    };

    chartOptions.value = {
      plugins: {
        legend: {
          labels: {
            usePointStyle: true,
            color: "#495057",
          },
        },
      },
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
</script>

<style scoped>
.dashboard {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  grid-gap: 20px;
}

.card {
  position: relative;
  padding: 20px;
  border-radius: 10px;
  background-color: #fff;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.chart-label {
  margin-top: 20px;
  text-align: center;
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
