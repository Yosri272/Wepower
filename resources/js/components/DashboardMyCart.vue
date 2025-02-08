<template>
    <div class="w-full flex flex-col h-full bg-white px-6 pt-6 pb-3 rounded-xl">

        <div class="flex justify-between items-center gap-2">
            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">My Cart</div>
            <div class="text-primary-600 text-lg font-medium leading-normal tracking-tight">{{ basketStore.total > 0 ?
                basketStore.total : 'No' }} item{{ basketStore.total > 0 ? 's' : '' }}</div>
        </div>

        <div v-if="basketStore.products.length > 0" class="border-t border-slate-200 mt-4 pt-4">
            <div class="max-h-80 md:max-h-[calc(100vh-520px)] min-h-72 overflow-y-auto">
                <Basket />
            </div>
            <div v-if="basketStore.total > 0" class="flex gap-3 md:gap-6 mt-4 flex-wrap">
                <div class="flex items-center gap-2 grow">
                    <div class="text-center text-slate-500 text-base font-normal leading-normal">
                        Cart Amount
                    </div>
                    <div class="text-slate-900 text-base md:text-lg font-bold leading-normal tracking-tight">
                        {{ master.showCurrency(basketStore.total_amount) }}
                    </div>
                </div>
                <button
                    class="py-2 sm:py-4 inline-flex gap-2 items-center justify-center px-3 sm:px-6 text-white bg-primary grow rounded-xl"
                    @click="processToCheckout">
                    <div class="text-base font-medium">
                        Proceed to Checkout
                    </div>
                    <ArrowRightIcon class="w-5 h-5 hidden sm:block" />
                </button>
            </div>
        </div>

        <div v-else class="border-t border-slate-200 mt-5 flex justify-center items-center flex-col grow">
            <img :src="'/assets/icons/empty-cart.png'" alt="empty-cart" class="w-[108px] h-[108px]" loading="lazy" />
            <div class="text-slate-400 text-2xl font-medium mt-2">There is no product in your cart.</div>
        </div>

    </div>
</template>

<script setup>
import { ArrowRightIcon } from '@heroicons/vue/24/outline';

import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useBaskerStore } from '../stores/BasketStore';
import { useMaster } from '../stores/MasterStore';
import Basket from './Basket.vue';

const router = new useRouter();

const basketStore = useBaskerStore();
const toast = useToast();

const master = useMaster();

const processToCheckout = () => {
    if (basketStore.selectedShopIds.length === 0) {
        toast.error('Please select at least one shop', {
            position: "bottom-left",
        });
        return;
    }
    router.push('/checkout')
}
</script>

<style scoped>
/* width */
::-webkit-scrollbar {
    width: 6px;
    border-radius: 4px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 4px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #94A3B8;
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
