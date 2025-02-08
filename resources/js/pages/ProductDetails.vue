<template>
    <div class="main-container">

        <div class="grid grid-cols-1 xl:grid-cols-4">

            <div class="xl:col-span-3 col-span-1 lg:pr-6">

                <div class="flex items-center gap-2 overflow-hidden pt-4">
                    <router-link to="/" class="w-6 h-6">
                        <HomeIcon class="w-5 h-5 text-slate-600" />
                    </router-link>

                    <div class="grow w-full overflow-hidden">
                        <div class="space-x-1 text-slate-600 text-sm font-normal truncate">
                            <router-link to="/">Home</router-link>
                            <span>/</span>
                            <span>{{ product.name }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap lg:flex-nowrap gap-4 mt-6">
                    <div class="lg:w-[480px] w-full">

                        <div class="w-full">
                            <div class="bg-slate-50 rounded-xl border border-slate-100 px-6">
                                <swiper :spaceBetween="10" :thumbs="{ swiper: thumbsSwiper }" :modules="modules"
                                    class="product-details-slider">
                                    <swiper-slide v-for="thumbnail in product.thumbnails" :key="thumbnail.id"
                                        class="max-h-[448px] h-auto">
                                        <img :src="thumbnail.thumbnail" alt="" class="h-full w-full object-contain">
                                    </swiper-slide>
                                </swiper>
                            </div>
                            <div class="px-1 mt-2">
                                <swiper @swiper="setThumbsSwiper" :spaceBetween="10" :slidesPerView="4" :freeMode="true"
                                    :navigation="true" :watchSlidesProgress="true" :modules="modules"
                                    class="product-details-thumbnail">
                                    <swiper-slide v-for="thumbnail in product.thumbnails" :key="thumbnail.id">
                                        <img :src="thumbnail.thumbnail" alt="" class="h-full w-full object-cover">
                                    </swiper-slide>
                                </swiper>
                            </div>

                        </div>

                    </div>

                    <div class="">

                        <!-- Flash Sale  -->

                        <!-- <router-link to="#"
                            class="bg-slate-100 mb-3 sm:mb-6 rounded-[44px] flex  items-center justify-start gap-3 sm:gap-6 overflow-hidden">
                            <div class="px-4 sm:px-8 py-2 bg-gradient-to-l from-primary to-fuchsia-500 ">
                                <div class="text-white text-sm sm:text-base font-bold leading-normal">
                                    Flash Sale
                                </div>
                            </div>

                            <div class="text-center text-primary text-sm font-normal leading-tight">
                                Ending in
                            </div>

                            <vue-countdown :time="time" v-slot="{ hours, minutes, seconds }">
                                <div class="flex gap-1.5 text-primary text-sm font-medium leading-tight">
                                    <div>{{ hours }}</div>
                                    <span>:</span>
                                    <div>{{ minutes }}</div>
                                    <span>:</span>
                                    <div>{{ seconds }}</div>
                                </div>
                            </vue-countdown>
                        </router-link> -->

                        <!-- Brand -->
                        <span class="text-primary text-xs font-normal leading-none px-1.5 py-1 bg-primary-50 rounded">
                            {{ product.brand ?? 'Unknown Brand' }}
                        </span>

                        <!-- Title -->
                        <div class="mt-3 text-slate-950 text-2xl font-medium leading-normal">
                            {{ product.name }}
                        </div>

                        <!-- Short Desciption -->
                        <div class="mt-2 text-slate-700 text-base font-normal leading-normal">
                            {{ product.short_description }}
                        </div>

                        <!-- Rating  review, sold and share -->
                        <div class="py-5 flex flex-wrap justify-start items-center gap-4 border-b border-slate-200">
                            <!-- rating -->
                            <div class="flex items-center gap-2">
                                <div class="flex">
                                    <StarIcon class="w-6 h-6 text-amber-500" />
                                    <StarIcon v-for="i in 4" :key="i" class="w-6 h-6  2xl:block hidden"
                                        :class="i < product.rating ? 'text-amber-500' : 'text-gray-300'" />
                                </div>
                                <div class="text-slate-800 text-base font-bold">
                                    {{ product.rating }}
                                </div>
                                <!-- Review -->
                                <div class="text-slate-500 text-base font-normal">
                                    {{ product.total_reviews }} Review
                                </div>
                            </div>

                            <div class="w-[1px] h-4 bg-slate-200"></div>

                            <!-- Sold -->
                            <div class="text-slate-800 text-base font-normal leading-normal">
                                {{ product.total_sold }} Sold
                            </div>

                            <div class="w-[1px] h-4 bg-slate-200"></div>

                            <!-- Share -->
                            <button class="flex items-center gap-2 border-none">
                                <ShareIcon class="w-[18px] text-slate-600" />
                                <span class="text-slate-800 text-base font-normal leading-normal">
                                    Share
                                </span>
                            </button>

                            <div class="w-[1px] h-4 bg-slate-200"></div>

                            <!-- Heart Icon -->
                            <button class="border-none" @click="favoriteAddOrRemove">
                                <HeartIcon v-if="!product.is_favorite" class="w-6 h-6 text-slate-600" />
                                <HeartIconFill v-else class="w-6 h-6 text-red-500" />
                            </button>
                        </div>

                        <!-- Price part -->
                        <div class="flex items-center gap-3 py-4 border-b border-slate-200">
                            <!-- discount Price -->
                            <div class="text-primary text-3xl font-bold leading-9">
                                {{ masterStore.showCurrency(product.discount_price > 0 ? product.discount_price: product.price) }}
                            </div>

                            <!-- Price -->
                            <div v-if="product.discount_price > 0"
                                class="text-slate-400 text-2xl font-normal line-through leading-loose">
                                {{ masterStore.showCurrency(product.price) }}
                            </div>

                            <!-- discount -->
                            <div v-if="product.discount_percentage"
                                class="px-2 py-1 bg-red-500 rounded-2xl text-white text-base font-medium">
                                {{ product.discount_percentage }}% OFF
                            </div>
                        </div>

                        <!-- Size -->
                        <div v-if="product.sizes?.length > 0" class="flex items-center gap-3 py-4">
                            <div class="w-[40px] md:w-[88px] text-slate-600 text-base font-normal leading-normal">
                                Size
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <div v-for="size in product.sizes" :key="size.id" class="relative">
                                    <input type="radio" name="size" :id="'size-' + size.name" class="peer hidden"
                                        :value="size.name" v-model="formData.size">
                                    <label :for="'size-' + size.name"
                                        class="w-11 h-9 flex justify-center items-center border-2 border-slate-200 rounded-md cursor-pointer peer-checked:border-primary peer-checked:bg-primary-100">
                                        {{ size.name }}
                                    </label>
                                </div>
                                <div v-if="!product.sizes" class="text-slate-500 text-base font-normal">
                                    N/A
                                </div>

                            </div>
                        </div>

                        <!-- Color -->
                        <div v-if="product.colors?.length > 0" class="flex items-center gap-3 py-4">
                            <div class="w-[40px] md:w-[88px] text-slate-600 text-base font-normal leading-normal">
                                Color
                            </div>

                            <div class="flex flex-wrap items-center gap-3">
                                <div v-for="color in product.colors" :key="color.id" class="relative">
                                    <input type="radio" name="color" :id="'color-' + color.name" class="peer hidden"
                                        :value="color.name" v-model="formData.color" />
                                    <label :for="'color-' + color.name"
                                        class="px-2 py-1 flex justify-center items-center border-2 border-slate-200 rounded-md cursor-pointer peer-checked:border-primary peer-checked:bg-primary-100">
                                        {{ color.name }}
                                    </label>
                                </div>

                                <div v-if="!product.colors" class="text-slate-500 text-base font-normal">
                                    N/A
                                </div>
                            </div>

                        </div>

                        <div class="flex flex-wrap gap-4">
                            <!-- Quantity Increase Or Decrease -->
                            <div v-if="cartProduct"
                                class="p-2 rounded-[10px] border border-slate-100 inline-flex gap-4">
                                <button class="bg-slate-200 p-2 rounded" @click="decrementQty">
                                    <MinusIcon class="w-6 h-6 text-slate-800" />
                                </button>

                                <div
                                    class="w-6 flex items-center justify-center text-center text-slate-950 text-base font-medium leading-normal">
                                    {{ cartProduct.quantity }}
                                </div>

                                <button class="bg-slate-100 p-2 rounded" @click="incrementQty">
                                    <PlusIcon class="w-6 h-6 text-slate-800" />
                                </button>
                            </div>

                            <!-- Add to Cart -->
                            <button v-if="!cartProduct"
                                class="grow max-w-56 justify-center items-center text-primary flex gap-2  px-6 py-4 rounded-[10px] border border-primary"
                                @click="addToCart">
                                <img :src="'/assets/icons/bag-active.svg'" loading="lazy" class="w-5 h-5">
                                <div class="text-base font-medium leading-normal">
                                    Add to Cart
                                </div>
                            </button>

                            <!-- Buy Now -->
                            <button
                                class="grow text-white bg-primary px-6 py-4 rounded-[10px] border border-primary max-w-[50%]"
                                @click="buyNow">
                                <span class="text-base font-medium leading-normal">
                                    Buy Now
                                </span>
                            </button>
                        </div>

                    </div>
                </div>

                <div class="block xl:hidden w-full pt-6 border-slate-200">
                    <ProductDetailsRightSide :product="product" :popularProducts="popularProducts" />
                </div>

                <div class="flex items-center gap-8 flex-wrap border-b mt-3 mb-4 xl:my-6">

                    <button class="py-3 transition text-base font-medium leading-normal border-b"
                        :class="aboutProduct ? 'text-primary border-primary' : 'text-slate-600 border-transparent'"
                        @click="aboutProduct = true; review = false">
                        About Product
                    </button>

                    <button class="py-3 transition text-base font-medium leading-normal border-b"
                        :class="review ? 'text-primary border-primary' : 'text-slate-600 border-transparent'"
                        @click="showReview()">
                        Reviews
                    </button>
                </div>

                <!-- About Product -->
                <div v-if="aboutProduct" class="">
                    <div v-html="product.description"></div>
                </div>

                <!-- Reviews -->
                <div v-if="review" class="">
                    <div class="text-slate-950 text-lg lg:text-2xl font-medium leading-loose mb-4">
                        Rating and Review
                    </div>

                    <!-- Review Rating percentage -->
                    <div class="max-w-2xl">
                        <ReviewRatings :reviewRatings="avarageRatings.percentages"
                            :avarageRating="avarageRatings?.rating" :totalReview="avarageRatings.total_review" />
                    </div>

                    <!-- Reviews -->
                    <div class="border-t border-slate-200 mt-6">
                        <div class="mt-4 lg:mt-6 text-slate-950 text-lg lg:text-2xl font-medium leading-loose">
                            Reviews
                        </div>

                        <div class="space-y-6 mt-6">
                            <Review v-for="review in reviews" :key="review.id" :review="review" />
                        </div>

                        <!-- paginations -->
                        <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                            <div class="text-slate-800 text-base font-normal leading-normal">
                                Showing {{ perPage * (currentPage - 1) + 1 }} to {{ perPage * (currentPage - 1) +
                                    reviews.length }} of {{ totalReviews }} results
                            </div>
                            <div>
                                <vue-awesome-paginate :total-items="totalReviews" :items-per-page="perPage"
                                    type="button" :max-pages-shown="3" v-model="currentPage"
                                    :on-click="onClickHandler" />
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <!-- Right side -->
            <div
                class="hidden xl:block col-span-1 w-full pt-6 h-full xl:pt-16 xl:pl-8 xl:border-l border-slate-200 xl:pb-6">
                <ProductDetailsRightSide :product="product" :popularProducts="popularProducts" />
            </div>

        </div>

        <!-- Similar Products -->
        <div class="mt-4 xl:mt-6 text-slate-800 text-lg md:text-2xl lg:text-3xl font-bold leading-9">
            Similar Products
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-6 gap-6 items-start my-6">
            <div v-for="product in relatedProducts" :key="product.id">
                <ProductCard :product="product" />
            </div>
        </div>


    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { useMaster } from '../stores/MasterStore';
import ProductDetailsRightSide from '../components/ProductDetailsRightSide.vue';
import ToastSuccessMessage from '../components/ToastSuccessMessage.vue';

import { HomeIcon, ShareIcon, HeartIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/outline';
import { StarIcon, HeartIcon as HeartIconFill } from '@heroicons/vue/24/solid';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { FreeMode, Navigation, Thumbs } from 'swiper/modules';

import ReviewRatings from '../components/ReviewRatings.vue';
import Review from '../components/Review.vue';
import ProductCardHorizontal from '../components/ProductCardHorizontal.vue';
import ProductCard from '../components/ProductCard.vue';
import { useBaskerStore } from '../stores/BasketStore';
import { useAuth } from '../stores/AuthStore';

import VueCountdown from '@chenfengyuan/vue-countdown';
const time = ref(7 * 60 * 60 * 1000);
// const time = ref(30 * 24 * 60 * 60 * 1000);
// const end = ref(new Date().getTime() + time.value);

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/navigation';
import 'swiper/css/thumbs';

import { useToast } from 'vue-toastification';
const toast = useToast();

const thumbsSwiper = ref(null);

const modules = [FreeMode, Navigation, Thumbs];

const setThumbsSwiper = (swiper) => {
    thumbsSwiper.value = swiper;
};

const route = useRoute();
const router = useRouter();
const masterStore = useMaster();
const basketStore = useBaskerStore();
const authStore = useAuth();

const formData = ref({
    product_id: route.params.id,
    size: null,
    color: null,
    unit: null,
});

const product = ref({});
const relatedProducts = ref([]);
const popularProducts = ref([]);

const aboutProduct = ref(true);
const review = ref(false);

const cartProduct = ref(null);

onMounted(() => {
    fetchProductDetails();
    window.scrollTo(0, 0);
    findProductInCart(route.params.id);
});

const buyNow = () => {
    if (authStore.token === null) {
        return authStore.loginModal = true;
    }
    basketStore.buyNowProduct = product.value;
    basketStore.buyNowProduct.size = formData.value.size;
    basketStore.buyNowProduct.color = formData.value.color;
    router.push({ name: 'buynow' })
};

watch(route, () => {
    fetchProductDetails();
    aboutProduct.value = true;
    review.value = false;
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
    formData.value.product_id = route.params.id;
    findProductInCart(route.params.id);
});

const findProductInCart = (productId) => {
    let foundProduct = null;
    basketStore.products.forEach((item) => {
        item.products.find((product) => {
            if (product.id == productId) {
                return foundProduct = product;
            }
        })
    });
    cartProduct.value = foundProduct;
    if (foundProduct) {
        formData.value.size = foundProduct.size;
        formData.value.color = foundProduct.color;
        formData.value.unit = foundProduct.unit;
    }
}

const addToCart = () => {
    basketStore.addToCart(formData.value, product.value)
    setTimeout(() => {
        findProductInCart(route.params.id);
    }, 500);
}

const decrementQty = () => {
    basketStore.decrementQuantity(product.value);
    setTimeout(() => {
        findProductInCart(route.params.id);
    }, 500);
}

const incrementQty = () => {
    basketStore.incrementQuantity(product.value);
    setTimeout(() => {
        findProductInCart(route.params.id);
    }, 500);
}

const favoriteAddOrRemove = () => {
    if (authStore.token === null) {
        return authStore.loginModal = true;
    }
    axios.post('/favorite-add-or-remove', {
        product_id: product.value.id
    }, {
        headers: {
            Authorization: authStore.token
        }
    }).then(() => {
        product.value.is_favorite = !product.value.is_favorite
        if (product.value.is_favorite === false) {
            const content = {
                component: ToastSuccessMessage,
                props: {
                    title: 'Product removed from favorite',
                    message: 'Product removed from favorite successfully',
                },
            };
            toast(content, {
                type: "default",
                hideProgressBar: true,
                icon: false,
                position: "top-right",
                toastClassName: "vue-toastification-alert",
                timeout: 3000
            });
        } else {
            const content = {
                component: ToastSuccessMessage,
                props: {
                    title: 'Product added to favorite',
                    message: 'Product added to favorite successfully',
                },
            };
            toast(content, {
                type: "default",
                hideProgressBar: true,
                icon: false,
                position: "top-right",
                toastClassName: "vue-toastification-alert",
                timeout: 3000
            });
        }
        authStore.fetchFavoriteProducts();
    }).catch((error) => {
        console.log(error);
    });
}

const showReview = () => {
    aboutProduct.value = false;
    review.value = true;
    fetchReviews();
}

const fetchProductDetails = async () => {
    axios.get('/product-details', {
        params: { product_id: route.params.id },
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        product.value = response.data.data.product;
        relatedProducts.value = response.data.data.related_products;
        popularProducts.value = response.data.data.popular_products;

        if (product.value.colors.length > 0) {
            formData.value.color = product.value.colors[0].name;
        } else {
            formData.value.color = null
        }
        if (product.value.sizes.length > 0) {
            formData.value.size = product.value.sizes[0].name;
        } else {
            formData.value.size = null
        }
        findProductInCart(route.params.id);
    })
};

const avarageRatings = ref({});

const totalReviews = ref(0);
const reviews = ref([]);

const currentPage = ref(1);
const perPage = ref(6);

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchReviews();
};

const fetchReviews = async () => {
    axios.get('/reviews', { params: { product_id: route.params.id, page: currentPage.value, per_page: perPage.value } }).then((response) => {
        totalReviews.value = response.data.data.total;
        reviews.value = response.data.data.reviews;
        avarageRatings.value = response.data.data.average_rating_percentage;
    })
};

</script>

<style>
.product-details-slider .swiper-slide {
    height: auto !important;
}

.product-details-thumbnail .swiper-slide {
    @apply h-20 md:h-[120px] lg:h-[100px];
}

.product-details-thumbnail .swiper-button-prev,
.product-details-thumbnail .swiper-button-next {
    @apply bg-white w-6 h-6 rounded-full shadow border border-slate-200 text-slate-600 -translate-y-1/2 mt-0;
}

.product-details-thumbnail .swiper-button-prev::after,
.product-details-thumbnail .swiper-button-next::after {
    @apply text-base;
}

.product-details-thumbnail .swiper-button-next {
    right: 0px;
}

.product-details-thumbnail .swiper-button-prev {
    left: 0px;
}

.product-details-thumbnail .swiper-slide {
    @apply border border-slate-100 rounded-lg transition overflow-hidden;
}

.product-details-thumbnail .swiper-slide-thumb-active {
    @apply border border-primary;
}
</style>
