<template>
    <div class="w-full rounded-xl overflow-hidden">
        <router-link :to="`/shops/${props.shop?.id}`">
            <div class="h-[84px] w-full relative">
                <img class="w-full h-full object-cover" :src="props.shop?.banner" alt="banner" loading="lazy" />

                <!--==== Status ====-->
                <div class="px-1 py-0.5 bg-lime-600 rounded-[10px] absolute top-2 right-2">
                    <div class="text-white text-xs font-normal leading-none">
                        {{ props.shop?.shop_status }}
                    </div>
                </div>
            </div>
            <div class="w-full px-4 pt-11 pb-4 relative bg-primary hover:bg-primary-600 transition duration-300">

                <!--==== Shop Logo ====-->
                <div class="w-16 h-16 absolute bg-slate-300 rounded-full -top-8">
                    <img :src="props.shop?.logo" alt="logo" loading="lazy"
                        class="w-full h-full object-cover rounded-full" />
                </div>

                <!--==== Shop Name ====-->
                <div class="text-white text-base font-bold leading-normal truncate overflow-hidden">
                    {{ props.shop?.name }}
                </div>

                <div class="flex justify-start items-center gap-4 w-full mt-3">
                    <!-- products -->
                    <div class="text-primary-200 text-sm font-normal leading-tight text-center">
                        {{ props.shop?.total_products }}+ Items
                    </div>

                    <div class="h-3 w-[0px] border border-primary-400"></div>

                    <!-- Categorise -->
                    <div class="text-primary-200 text-sm font-normal leading-tight text-center">
                        {{ props.shop?.total_categories }}+ Categorise</div>

                    <div class="h-3 w-[0px] border border-primary-400"></div>

                    <!-- Rating -->
                    <div class="flex items-center gap-1">
                        <StarIcon class="w-4 h-4 text-yellow-400" />
                        <div class="text-white text-sm font-bold">
                            {{ props.shop?.rating?.toFixed(1) }}
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex justify-start items-center gap-0.5">
                    <div class="text-white text-sm font-normal leading-tight">Visit Store</div>
                    <ArrowRightIcon class="w-4 h-4 text-white" />
                </div>

            </div>
        </router-link>
    </div>
</template>

<script setup>
import { StarIcon, ArrowRightIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    shop: Object
});

</script>
