<template>
    <div class="bg-white rounded-xl p-6 h-full">
        <div class="pb-3 text-slate-950 text-lg font-medium leading-normal tracking-tight">
            Recently Viewed
        </div>

        <div v-if="products.length > 0"
            class="md:max-h-[calc(100vh-560px)] min-h-72 flex flex-col gap-2 border-t border-slate-200 overflow-y-auto">
            <div v-for="product in products" :key="product.id" class="mt-3">
                <ProductCardHorizontal :product="product" />
            </div>
        </div>

        <div v-else class="h-full flex justify-center items-center border-t border-slate-200">
            <div class="text-center text-slate-400 text-lg font-normal leading-7 tracking-tight">No products have been
                viewed recently.</div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import ProductCardHorizontal from "./ProductCardHorizontal.vue";
import { useAuth } from "../stores/AuthStore";
const AuthStore = useAuth();

const products = ref([]);

onMounted(() => {
    fetchViewProducts();
});

const fetchViewProducts = () => {
    axios.get('/recently-views', {
        headers: {
            Authorization: AuthStore.token
        }
    }).then((response) => {
        products.value = response.data.data.products;
    }).catch((error) => {
        console.log(error);
    });
}

</script>
<style scoped>
/* width */
::-webkit-scrollbar {
    width: 6px;
    border-radius: 4px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 4px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #94A3B8;
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
