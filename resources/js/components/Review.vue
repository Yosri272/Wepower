<template>
    <div class="flex gap-4">
        <!-- profile -->
        <div class="w-12 shrink-0">
            <img :src="props.review?.customer_profile" class="w-full object-cover">
        </div>
        <div class="grow flex flex-col gap-4 border-b border-slate-100 pb-4">
            <!-- name and rating -->
            <div class="flex justify-between items-center gap-3">
                <div class="flex flex-col gap-1">
                    <!-- name -->
                    <div class="text-slate-950 text-sm font-medium leading-tight">
                        {{ props.review?.customer_name }}
                    </div>
                    <!-- rating -->
                    <div class="flex items-center">
                        <StarIcon v-for="i in 5" :key="i" class="w-5 h-5" :class="i <= props.review?.rating ? 'text-amber-500' : 'text-slate-300'" />

                        <div class="text-gray-900 text-sm font-bold leading-tight ml-1">
                            {{ props.review?.rating?.toFixed(1) }}
                        </div>
                    </div>
                </div>
                <!-- review date -->
                <div class="text-right text-slate-400 text-xs font-normal leading-none">
                    {{ props.review?.created_at }}
                </div>
            </div>

            <!-- review message -->
            <div class="text-slate-700 text-sm font-normal leading-tight">
                {{ props.review?.description }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { StarIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    review: Object
});

</script>
