<template>
    <div>
        <HeroBanner :banners="banner" :ads="ads" />
        <AboutSupport />
        <Categories :categories="categories" />
        <!-- <FlashSale /> -->
        <PopularProducts :products="popularProducts" />
        <div v-if="master.getMultiVendor">
            <TopRatedShops :shops="topRatedShops" />
        </div>
        <JustForYou :justForYou="justForYou" />
        <RecentlyViews :products="recentlyViewProducts" />
    </div>
</template>

<script setup>
import { useMaster } from "../stores/MasterStore";
import { useBaskerStore } from "../stores/BasketStore";
import { onMounted, ref } from "vue";
import HeroBanner from "../components/HeroBanner.vue";
import AboutSupport from "../components/AboutSupport.vue";
import Categories from "../components/Categories.vue";
import FlashSale from "../components/FlashSale.vue";
import PopularProducts from "../components/PopularProducts.vue";
import TopRatedShops from "../components/TopRatedShops.vue";
import JustForYou from "../components/JustForYou.vue";
import RecentlyViews from "../components/RecentlyViews.vue";

import { useAuth } from "../stores/AuthStore";
import axios from "axios";

const master = useMaster();
const baskerStore = useBaskerStore();

const authStore = useAuth();

onMounted(() => {
    getData();
    master.fetchData();
    baskerStore.fetchCart();
    fetchViewProducts();
    master.basketCanvas = false;
    authStore.loginModal = false;
    authStore.registerModal = false;
    authStore.showAddressModal = false;
    authStore.showChangeAddressModal = false;
});

const banner = ref([]);
const categories = ref([]);
const flashSale = ref([]);
const popularProducts = ref([]);
const topRatedShops = ref([]);
const justForYou = ref([]);
const recentlyViewProducts = ref([]);
const ads = ref([]);

const getData = () => {
    axios.get('/home?page=1&per_page=12', {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        ads.value = response.data.data.ads;
        banner.value = response.data.data.banners;
        categories.value = response.data.data.categories;
        justForYou.value = response.data.data.just_for_you;
        popularProducts.value = response.data.data.popular_products;
        topRatedShops.value = response.data.data.shops.slice(0, 4);
    }).catch(() => {});

    // fetch categories
    axios.get('/categories').then((response) => {
        master.categories = response.data.data.categories;
    }).catch(() => {});
}

const fetchViewProducts = () => {
    if (authStore.token) {
        axios.get('/recently-views', {
            headers: {
                Authorization: authStore.token
            }
        }).then((response) => {
            recentlyViewProducts.value = response.data.data.products;
        }).catch(() => {});
    }
}

</script>

<style scoped></style>
