import './bootstrap';

import { createApp } from 'vue';

import App from './App.vue';

import router from "./router";

import VueAwesomePaginate from "vue-awesome-paginate";
import "vue-awesome-paginate/dist/style.css";

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";


import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp(App);

app.use(pinia);
app.use(router);
app.use(VueAwesomePaginate);
app.use(Toast);
app.mount('#app');
