<template>
  <div class="route">
    <div class="router">
      <p>SHOP</p>
      <div>
        <router-link to="/"> <i class="pi pi-home"></i></router-link>
        <span> <i class="pi pi-angle-right"></i> </span>
        <router-link :to="{ name: routeName }"
          ><span>{{ routeName }}</span></router-link
        >
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";

const routeName = ref("");
const route = useRoute();

const updateRouteDetails = () => {
  routeName.value = route.name; // Assuming route name is available
};

onMounted(() => {
  updateRouteDetails();
});

// Watch for route changes
watch(route, () => {
  updateRouteDetails();
});
</script>

<style lang="scss" scoped>
.route {
  width: 100%;
  height: 80px;
  background-color: #f0fbf7;
  display: flex;
  align-items: center;
  justify-content: center;
  .router {
    width: 80%;
    height: 90%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    a {
      text-decoration: none;
    }
    p {
      font-size: 20px;
      font-weight: bold;
      color: #303947;
    }
    div {
      font-size: 12px;
      width: 70px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
  }
}
</style>
