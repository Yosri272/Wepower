<template>
    <div class="flex gap-2 items-center">
        <CheckCircleIcon class="w-10 h-10 text-green-500" />
        <div class="flex flex-col gap-0">
            <div class="text-slate-950 text-lg font-bold">{{ title }}</div>
            <div class="text-slate-600 text-base font-normal leading-tight">
                {{ message }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { CheckCircleIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    title: String,
    message: String,
});

</script>

<style>
.vue-toastification-alert {
    background-color: #fff !important;
    padding: 12px !important;
}

.vue-toastification-alert .Vue-Toastification__close-button {
    color: black;
}
</style>
