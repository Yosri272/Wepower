<template>
    <div class="main-container py-12 bg-slate-100">

        <!-- Header -->
        <div class="flex justify-between items-center gap-4">
            <div class="text-slate-800 text-lg md:text-3xl font-bold leading-9">Top Rated Shops</div>

            <router-link to="/shops" class="flex items-center gap-1">
                <div class="text-slate-600 text-base font-normal leading-normal">View All</div>
                <ArrowRightIcon class="w-5 h-5 text-slate-600" />
            </router-link>
        </div>


        <!-- Shops -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-start">

            <div v-for="shop in shops" :key="shop.id" class="w-full">
                <ShopCardTop :shop="shop"/>
            </div>

        </div>


    </div>
</template>

<script setup>
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
import ShopCardTop from './ShopCardTop.vue';

const { shops } = defineProps(['shops']);

</script>
