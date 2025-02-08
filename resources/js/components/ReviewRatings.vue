<template>
    <div class="flex flex-col sm:flex-row gap-4 lg:gap-12 items-center mt-3">
        <div class="flex flex-col gap-2.5 items-center sm:items-start">
            <div class="text-slate-950 text-2xl font-bold">
                {{ props.avarageRating?.toFixed(1) }}
            </div>
            <div class="flex">
                <StarIcon v-for="i in 5" :key="i" class="w-6 h-6" :class="i <= props.avarageRating ? 'text-amber-500' : 'text-slate-300'"/>
            </div>
            <div class="text-slate-600 text-base font-normal leading-normal">
                {{ props.totalReview }} reviews
            </div>
        </div>

        <div class="hidden w-px h-[88px] bg-slate-200"></div>

        <div class="flex items-center flex-col w-full">
            <!-- 5 stars -->
            <div v-for="(rating, index) in props.reviewRatings" :key="rating.id"
                class="h-6 w-full justify-start items-center gap-2 inline-flex">

                <span class="text-slate-900 text-base font-medium">
                    {{ index }}
                </span>
                <div class="h-2 bg-slate-200 rounded w-full">
                    <div class="h-full bg-amber-500 rounded" :style="{ width: rating + '%' }">
                    </div>
                </div>
                <div class="pl-4 w-[80px] text-slate-300 text-base font-normal">
                    {{ rating }}%
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { StarIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    reviewRatings: Object,
    avarageRating: Number,
    totalReview: Number
});


</script>

<style lang="scss" scoped></style>
