<template>
    <div class="main-container mt-6 mb-12">

        <div class="flex justify-between items-center gap-4 flex-wrap">
            <div class="text-slate-950 text-lg  md:text-3xl font-bold leading-9">Categories</div>

            <div class="flex justify-center items-center gap-4">
                <button class="w-11 h-11 rounded-xl justify-center items-center gap-2 flex" @click="swiperPrevSlide">
                    <ChevronLeftIcon class="w-6 h-6 text-slate-600" />
                </button>
                <router-link to="/categories" class="text-slate-600 text-base font-normal leading-normal">
                    View All
                </router-link>
                <button class="w-11 h-11 rounded-xl justify-center items-center gap-2 flex" @click="swiperNextSlide">
                    <ChevronRightIcon class="w-6 h-6 text-slate-600" />
                </button>
            </div>
        </div>

        <div class="mt-8">
            <swiper :breakpoints="breakpoints" :loop="false"  ref="swiperRef" @swiper="onSwiper" :modules="[Navigation]">
                <swiper-slide v-for="category in categories" :key="category.id">
                    <CategoryCard :category="category" />
                </swiper-slide>

            </swiper>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation } from 'swiper/modules';
import CategoryCard from './CategoryCard.vue';

import 'swiper/css';

const props = defineProps({
    categories: Array
});

const swiperInstance = ref()

function onSwiper(swiper) {
    swiperInstance.value = swiper
}

const swiperNextSlide = () => {
    swiperInstance.value.slideNext()
};
const swiperPrevSlide = () => {
    swiperInstance.value.slidePrev()
};

const breakpoints = {
    320: {
        slidesPerView: 2,
        spaceBetween: 10
    },
    768: {
        slidesPerView: 4,
        spaceBetween: 10
    },
    1024: {
        slidesPerView: 6,
        spaceBetween: 30
    },

    1280: {
        slidesPerView: 8,
        spaceBetween: 30
    }
};

</script>

<style lang="scss" scoped></style>
