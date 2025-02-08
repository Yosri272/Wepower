<template>
    <div class="main-container py-12">

        <!-- Header -->
        <div class="flex justify-between items-center gap-4">
            <div class=" text-slate-800 text-lg md:text-3xl font-bold leading-9">Popular Products</div>

            <router-link to="/most-popular" class="flex items-center gap-1">
                <div class="text-slate-600 text-base font-normal leading-normal">View All</div>
                <ArrowRightIcon class="w-5 h-5 text-slate-600" />
            </router-link>
        </div>

        <!-- Products -->
        <div class="mt-8 grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-3 md:gap-6 items-start">

            <div v-for="product in products" :key="product.id" class="w-full">
                <ProductCard :product="product"/>
            </div>

        </div>

    </div>
</template>

<script setup>
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
import ProductCard from './ProductCard.vue';

const props = defineProps({
    products: Array
})

</script>
