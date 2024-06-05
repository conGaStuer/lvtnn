import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import PrimeVue from "primevue/config";
import "primevue/resources/themes/saga-blue/theme.css"; // Choose a theme
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import Antd from "ant-design-vue";

//in main.js
import "primevue/resources/themes/aura-light-green/theme.css";
import { Menu } from "ant-design-vue";
createApp(App).use(router).use(PrimeVue).use(Antd).mount("#app");
