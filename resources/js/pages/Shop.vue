<template>
    <div class="main-container py-14">

        <div class="text-slate-800 text-3xl font-bold leading-9">All Shops</div>

        <!-- Shops -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-start">

            <div v-for="shop in shops" :key="shop.id" class="w-full">
                <ShopCard :shop="shop" />
            </div>

        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
            <div class="text-slate-800 text-base font-normal leading-normal">
                Showing {{ perPage * (currentPage - 1) + 1 }} to {{ perPage * (currentPage - 1) + shops.length }} of {{
                totalShops }} results
            </div>
            <div>
                <vue-awesome-paginate :total-items="totalShops" :items-per-page="perPage" type="button"
                    :max-pages-shown="5" v-model="currentPage" :on-click="onClickHandler" />
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ShopCard from '../components/ShopCard.vue';

const currentPage = ref(1);
const perPage = ref(12);
const totalShops = ref(0);

const shops = ref([]);

onMounted(() => {
    fetchShops();
    window.scrollTo(0, 0);
});

const onClickHandler = async (page) => {
    currentPage.value = page;
    fetchShops();
};

const fetchShops = async () => {
    axios.get('/shops', { params: { page: currentPage.value, per_page: perPage.value } }).then((response) => {
        totalShops.value = response.data.data.total;
        shops.value = response.data.data.shops;
    })
};

</script>
