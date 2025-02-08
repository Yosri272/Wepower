<template>
    <div>
        <!-- Header -->
        <AuthPageheader :title="'Wishlist (' + authStore.favoriteProducts + ')'" />

        <div class="p-3 md:p-4 xl:p-6">

            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 items-start">
                <ProductCard v-for="product in products" :key="product.id" :product="product" />
            </div>

            <div v-if="products.length == 0"
                class="p-3 md:p-4 xl:p-6 bg-white rounded-lg md:rounded-2xl border border-slate-100 italic">
                Wishlist is empty
            </div>

        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import AuthPageheader from "../components/AuthPageheader.vue";
import ProductCard from "../components/ProductCard.vue";

import { useAuth } from "../stores/AuthStore";

import { useRoute } from 'vue-router';
const route = useRoute();

const authStore = useAuth();

const products = ref([]);


watch(() => authStore.favoriteProducts, () => {
    if (route.name === 'wishlist') {
        fetchProducts();
    }
});

onMounted(() => {
    fetchProducts();
});

const fetchProducts = async () => {
    axios.get('/favorite-products', {
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        products.value = response.data.data.products;
    });
};

</script>
