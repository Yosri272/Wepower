<template>
    <div class="w-full rounded-xl overflow-hidden border border-slate-100 hover:border-primary transition-all duration-300">
        <div class="h-28 w-full relative">
            <img class="w-full h-full object-cover" :src="props.shop?.banner" alt="banner" loading="lazy" />

            <!--==== Status ====-->
            <div class="px-1 py-0.5 bg-lime-600 rounded-[10px] absolute top-2 right-2">
                <div class="text-white text-xs font-normal leading-none">
                    {{ props.shop?.shop_status }}
                </div>
            </div>
        </div>
        <div class="w-full px-4 pt-14 pb-4 relative transition duration-300">

            <!--==== Shop Logo ====-->
            <div class="w-[88px] h-[88px] absolute bg-slate-300 rounded-full left-1/2 -translate-x-1/2 -top-11 shadow-lg">
                <img :src="props.shop?.logo" alt="logo" loading="lazy" class="w-full h-full object-cover rounded-full" />
            </div>

            <!--==== Shop Name ====-->
            <div class="text-slate-950 text-center text-lg font-bold leading-normal truncate overflow-hidden">
                {{ props.shop?.name }}
            </div>

            <div class="flex justify-center items-center gap-4 w-full mt-3">
                <div class="text-slate-500 text-base font-normal leading-normal text-center">
                    {{ props.shop?.total_products }}+ Items
                </div>

                <div class="h-3 w-[0px] border text-slate-300"></div>

                <div class="text-slate-500 text-base font-normal leading-normal text-center">
                    {{ props.shop?.total_categories }}+ Categorise
                </div>
            </div>

            <div class="flex items-center justify-center gap-2 mt-2">
                <div class="flex">
                    <StarIcon v-for="i in 5" :key="i" class="w-4 h-4" :class="i <= props.shop?.rating ? 'text-amber-500' : 'text-slate-300'"/>
                </div>
                <div class="flex gap-1">
                    <div class="text-slate-800 text-base font-bold leading-normal">
                       {{ props.shop?.rating?.toFixed(1) }}
                    </div>
                    <div class="text-slate-500 text-base font-normal leading-normal">
                        ({{ props.shop?.total_reviews }})
                    </div>
                </div>
            </div>

            <!-- Visit Store -->
            <div class="w-full flex justify-center mt-3">
                <router-link :to="`/shops/${props.shop?.id}`" class="w-[156px] flex items-center justify-center px-4 py-3 transition duration-300 rounded-[10px] border border-primary text-primary hover:bg-primary hover:text-white font-medium">
                    <div class="text-base font-normal leading-tight">Visit Store</div>
                    <ArrowRightIcon class="w-5 h-5 ml-0.5" />
                </router-link>
            </div>

        </div>
    </div>
</template>

<script setup>
import { StarIcon, ArrowRightIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    shop: Object
});

</script>

