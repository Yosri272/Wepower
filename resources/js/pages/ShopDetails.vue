<template>
    <div class="">
        <div class="h-56 md:h-64 w-full">
            <img :src="shop?.banner" loading="lazy" class="w-full h-full object-cover" />
        </div>

        <div class="main-container relative py-10 pt-24">

            <!-- Shop Details -->
            <div class="-top-32 sm:-top-28 lg:-top-[108px] absolute left-0 right-0 main-container">
                <div class="w-full p-6 bg-white rounded-2xl shadow flex-col gap-2 sm:gap-6 flex">

                    <div class="flex justify-between w-full gap-3 sm:gap-6 flex-wrap sm:flex-nowrap">
                        <div class="flex grow gap-6">
                            <div class="w-16 h-16 md:w-[88px] md:h-[88px] rounded-full overflow-hidden shrink-0">
                                <img :src="shop?.logo" loading="lazy" class="w-full h-full object-cover" />
                            </div>

                            <div class="flex flex-col items-start gap-2">

                                <div class="flex justify-start items-center w-full gap-2">
                                    <!-- Shop Name -->
                                    <div class="text-slate-950 text-lg font-bold leading-normal tracking-tight">
                                        {{ shop?.name }}
                                    </div>
                                    <!-- Status -->
                                    <div
                                        class="px-1 py-0.5 bg-lime-600 rounded-[10px] text-white text-xs font-normal leading-none">
                                        {{ shop?.shop_status }}
                                    </div>
                                </div>

                                <div class="flex justify-start items-center gap-4">
                                    <!-- Items -->
                                    <div class="text-slate-500 text-base font-normal leading-normal">
                                        {{ shop?.total_products }}+ Items
                                    </div>

                                    <div class="h-3 w-[0px] border text-slate-300"></div>

                                    <!-- Categorise -->
                                    <div class="text-slate-500 text-base font-normal leading-normal text-center">
                                        {{ shop?.total_categories }}+ Categorise
                                    </div>
                                </div>

                                <!-- short description -->
                                <div class="text-slate-500 text-sm font-normal leading-tight">
                                    <div v-html="shop?.description"></div>
                                </div>

                            </div>
                        </div>

                        <!-- Rating -->
                        <div
                            class="sm:border-l border-slate-200 w-full sm:w-[156px] md:w-[184px] flex flex-row-reverse sm:flex-col justify-center sm:justify-start items-center gap-2">
                            <div class="flex gap-1">
                                <div class="text-slate-800 text-base font-bold leading-normal">
                                    {{ shop?.rating?.toFixed(1) }}
                                </div>
                                <div class="text-slate-500 text-base font-normal leading-normal">
                                    ({{ shop?.total_reviews }})
                                </div>
                            </div>
                            <div class="flex">
                                <StarIcon v-for="i in 5" :key="i" class="w-4 h-4"
                                    :class="i <= shop?.rating ? 'text-amber-500' : 'text-gray-300'" />
                            </div>
                        </div>
                    </div>

                    <!-- Buttons and search -->
                    <div class="flex justify-between items-center w-full">
                        <div>
                            <Button class="px-4 py-3 rounded-[10px] text-base font-normal leading-normal"
                                :class="productTab ? 'text-white bg-primary' : 'text-slate-600'"
                                @click="productTab = true; reviewTab = false; categoryTab = false">
                                All Products
                            </Button>

                            <Button class="px-4 py-3 rounded-[10px] text-base font-normal leading-normal"
                                :class="categoryTab ? 'text-white bg-primary' : 'text-slate-600'"
                                @click="showCategoryTab()">
                                Categories
                            </Button>

                            <Button class="px-4 py-3 rounded-[10px] text-base font-normal leading-normal"
                                :class="reviewTab ? 'text-white bg-primary' : 'text-slate-600'" @click="showReviewTab">
                                Reviews
                            </Button>
                        </div>
                        <!-- Search input -->
                        <div class="lg:w-[448px] relative hidden sm:block">
                            <input type="text" placeholder="Search in store"
                                class="p-3 pr-10 bg-slate-100 rounded-lg border border-slate-200 w-full outline-none focus:border-primary transition duration-300"
                                v-model="search" />
                            <MagnifyingGlassIcon
                                class="w-6 h-6 absolute top-1/2 right-3 -translate-y-1/2 text-slate-400" />
                        </div>

                    </div>
                </div>
            </div>

            <!-- banners -->
            <div class="pt-10">
                <swiper :breakpoints="breakpoints" :spaceBetween="30" :freeMode="true" :modules="modules">
                    <swiper-slide v-for="banner in shop?.banners" :key="banner.id">
                        <img :src="banner.thumbnail" alt="banner" loading="lazy" class="aspect-[6/2] rounded-xl">
                    </swiper-slide>
                </swiper>
            </div>

            <!-- Products , Categories and Reviews -->
            <div class="mt-12">

                <!-- Products -->
                <div v-if="productTab" class="w-full">
                    <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6 items-start">
                        <div v-for="product in products" :key="product.id" class="w-full">
                            <ProductCard :product="product" />
                        </div>
                        <div v-if="products.length == 0" class="text-slate-950 text-xl font-medium leading-7">
                            No Products Found
                        </div>
                    </div>

                    <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                        <div class="text-slate-800 text-base font-normal leading-normal">
                            Showing {{ (perPage * (currentPage - 1) + 1) }} to {{ (perPage * (currentPage - 1) + products.length) }} of {{ totalProducts }} results
                        </div>
                        <div>
                            <vue-awesome-paginate :total-items="totalProducts" :items-per-page="perPage" type="button"
                                :max-pages-shown="5" v-model="currentPage" :on-click="onClickHandler" />
                        </div>
                    </div>

                </div>

                <!-- Categories -->
                <div v-if="categoryTab" class="w-full">
                    <div
                        class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-7 gap-6 items-start">

                        <div v-for="category in categories" :key="category.id" class="w-full">
                            <router-link :to="`/shops/${shop.id}/categories/${category.slug}`" class="w-full">
                                <CategoryCard :category="category" />
                            </router-link>
                        </div>

                    </div>
                </div>

                <!-- Reviews -->
                <div v-if="reviewTab" class="w-full">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                        <!-- Rating and Review -->
                        <div>
                            <div class="text-slate-950 text-xl font-medium leading-7">
                                Rating and Review
                            </div>
                            <ReviewRatings :reviewRatings="avarageRatings?.percentages"
                                :avarageRating="avarageRatings?.rating" :totalReview="totalReviews" />
                        </div>

                        <!-- Reviews -->
                        <div>
                            <div class="text-slate-950 text-xl font-medium leading-7">Reviews</div>

                            <div class="mt-4">

                                <div v-for="review in reviews" :key="review.id" class="mb-4">
                                    <Review :review="review" />
                                </div>

                                <!-- paginations -->
                                <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                                    <div class="text-slate-800 text-base font-normal leading-normal">
                                        Showing {{ (reviewPerPage * (reviewPage - 1) + 1) }} to {{ (reviewPerPage *
                (reviewPage - 1) + reviews.length) }} of {{ totalReviews }} results
                                    </div>
                                    <div>
                                        <vue-awesome-paginate :total-items="totalReviews"
                                            :items-per-page="reviewPerPage" type="button" :max-pages-shown="3"
                                            v-model="reviewPage" :on-click="reviewPagination" />
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { StarIcon, MagnifyingGlassIcon } from '@heroicons/vue/24/solid';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode } from 'swiper/modules';
import ProductCard from '../components/ProductCard.vue';
import CategoryCard from '../components/CategoryCard.vue';
import ReviewRatings from '../components/ReviewRatings.vue';
import Review from '../components/Review.vue';

import 'swiper/css';
import 'swiper/css/free-mode';

const route = useRoute();

const modules = [FreeMode];

const productTab = ref(true);
const categoryTab = ref(false);
const reviewTab = ref(false);

const currentPage = ref(1);
const perPage = ref(12);

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchProducts();
};

const reviewPerPage = ref(5);
const reviewPage = ref(1);

const reviewPagination = (page) => {
    reviewPage.value = page;
    fetchReviews();
};

const shop = ref({});

const categories = ref([]);

const products = ref([]);
const totalProducts = ref(0);

const avarageRatings = ref({});
const totalReviews = ref(0);
const reviews = ref([]);

const search = ref('');
const searchProducts = () => {
    currentPage.value = 1;
    productTab.value = true;
    categoryTab.value = false;
    reviewTab.value = false;
    fetchProducts();
};

watch(search, () => {
    searchProducts();
});

onMounted(() => {
    fetchDetails();
    window.scrollTo(0, 0);
    fetchProducts();
});

const showCategoryTab = () => {
    productTab.value = false;
    categoryTab.value = true;
    reviewTab.value = false;
    fetchCategories();
};

const showReviewTab = () => {
    productTab.value = false;
    categoryTab.value = false;
    reviewTab.value = true;
    fetchReviews();
};

const fetchDetails = async () => {
    axios.get('/shops/' + route.params.id).then((response) => {
        shop.value = response.data.data.shop;
    })
};

const fetchProducts = async () => {
    axios.get('/products', { params: { shop_id: route.params.id, page: currentPage.value, per_page: perPage.value, search: search.value } }).then((response) => {
        totalProducts.value = response.data.data.total;
        products.value = response.data.data.products;
    })
};

const fetchCategories = async () => {
    if (categories.value.length === 0) {
        axios.get('/shop-categories', { params: { shop_id: route.params.id } }).then((response) => {
            categories.value = response.data.data.categories;
        });
    }
};

const fetchReviews = async () => {
    axios.get('/reviews', { params: { shop_id: route.params.id, page: reviewPage.value, per_page: reviewPerPage.value } }).then((response) => {
        totalReviews.value = response.data.data.total;
        reviews.value = response.data.data.reviews;
        avarageRatings.value = response.data.data.average_rating_percentage;
    })
};

const breakpoints = {
    320: {
        slidesPerView: 1,
        spaceBetween: 10
    },
    768: {
        slidesPerView: 2,
        spaceBetween: 10
    },
    1024: {
        slidesPerView: 2,
        spaceBetween: 30
    },

    1280: {
        slidesPerView: 3,
        spaceBetween: 30
    }
};

</script>
