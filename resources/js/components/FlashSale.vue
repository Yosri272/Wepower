<template>
    <div class="bg-primary-100">
        <div class="main-container py-12">

            <div class="flex justify-between items-center gap-4">
                <div class="text-slate-800 text-lg md:text-3xl font-bold leading-9">Flash Sale</div>

                <router-link to="#" class="flex items-center gap-1">
                    <div class="text-slate-600 text-base font-normal leading-normal">View All</div>
                    <ArrowRightIcon class="w-5 h-5 text-slate-600" />
                </router-link>
            </div>

            <div class="mt-6 grid grid-cols-1 xl:grid-cols-5 gap-6 lg:gap-8 items-start">

                <!-- Flash Sale Banner -->
                <div class="p-3 xl:p-6 rounded-xl xl:flex-col justify-between xl:justify-center items-center gap-4 flex" style="background: conic-gradient(from 180deg at 50% 50%, #8B5CF6 0deg, #C622FF 132.50098586082458deg, #5C87F6 271.2626552581787deg, #8B5CF6 360deg);">
                    <div class="w-[120px] xl:w-full">
                        <img :src="'/assets/images/flashSale.png'" loading="lazy" class="w-full"/>
                    </div>

                    <div class="text-center text-white sm:text-3xl font-bold sm:leading-9">
                        Deal of the Day
                    </div>

                    <div class="flex justify-center flex-col items-center gap-2 xl:gap-4">
                        <div class="text-center text-white text-lg font-normal leading-7 tracking-tight">Ending in</div>
                        <div class="flex justify-center items-center gap-2 text-white">
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                                <span class="text-primary text-lg font-medium">06</span>
                            </div>:
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                                <span class="text-primary text-lg font-medium">06</span>
                            </div>:
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white rounded-full justify-center items-center flex">
                                <span class="text-primary text-lg font-medium">06</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flash Sale Products -->
                <div class="xl:col-span-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-6">
                    <div v-for="product in products" :key="product.id" class="w-full">
                        <ProductCardHorizontal :product="product"/>
                    </div>
                </div>

            </div>

        </div>

    </div>
</template>

<script setup>
import { ArrowRightIcon } from '@heroicons/vue/24/outline';

import ProductCardHorizontal from './ProductCardHorizontal.vue';

const products = [
    {
        id: 1,
        name: 'QuantumCore Pro Gaming Motherboard',
        price: 15.00,
        discount_price: 0,
        discount_percentage: 0,
        total_reviews: 4,
        total_sold: 19,
        rating: 4.5,
        quantity: 0,
        thumbnail: '/assets/images/product1.png'
    },
    {
        id: 2,
        name: ' SwiftGlide Precision Mouse',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 5,
        total_reviews: 12,
        total_sold: 20,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product2.png'
    },
    {
        id: 3,
        name: ' TitanPro Gaming Laptop',
        price: 20.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product3.png'
    },
    {
        id: 4,
        name: 'QuantumCore Pro Gaming Motherboard',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 0,
        thumbnail: '/assets/images/product4.png'
    },
    {
        id: 5,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product5.png'
    },
    {
        id: 6,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 0,
        thumbnail: '/assets/images/product6.png'
    },
    {
        id: 7,
        name: 'QuantumCore Pro Gaming Motherboard',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product1.png'
    },
    {
        id: 8,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product1.png'
    },
    {
        id: 5,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product5.png'
    },
    {
        id: 6,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product6.png'
    },
    {
        id: 7,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product1.png'
    },
    {
        id: 8,
        name: 'Rainbow Sequin Dress',
        price: 15.00,
        discount_price: 10,
        discount_percentage: 50,
        total_reviews: 8,
        total_sold: 14,
        rating: 4.5,
        quantity: 321,
        thumbnail: '/assets/images/product1.png'
    }
]

</script>

