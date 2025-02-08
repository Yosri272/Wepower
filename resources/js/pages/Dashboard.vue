<template>
    <div class="flex flex-col h-full">
        <!-- Header -->
        <AuthPageheader title="Dashboard" />

        <div
            class="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6 px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6 pb-0 h-full">

            <div class="col-span-1 xl:col-span-2 flex flex-col gap-4">

                <!-- Orders Status -->
                <div class="flex flex-wrap md:flex-nowrap gap-4">
                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-lime-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg md:text-2xl font-medium leading-normal tracking-tight">
                                {{ statusOrders.pending + statusOrders.confirm + statusOrders.processing + statusOrders.on_the_way }}
                            </div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                On Going Order
                            </div>
                        </div>
                        <div>
                            <img :src="'/assets/icons/truck-time.svg'" alt="" class="w-6 h-6">
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-primary-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                                {{ basketStore.total }}
                            </div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                Products in Cart
                            </div>
                        </div>
                        <div>
                            <img :src="'/assets/icons/bag-active.svg'" alt="" class="w-6 h-6">
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-red-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">{{
                                AuthStore.favoriteProducts }}</div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                Products in Wishlist
                            </div>
                        </div>
                        <div>
                            <HeartIcon class="w-6 h-6 text-red-500" />
                        </div>
                    </div>

                    <div class="flex grow justify-between bg-white p-3 rounded-lg border border-blue-300">
                        <div class="flex flex-col gap-2">
                            <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">{{ statusOrders.all }}</div>
                            <div class="text-slate-500 text-xs font-normal leading-none">
                                Products Ordered
                            </div>
                        </div>
                        <div>
                            <img :src="'/assets/icons/box-tick.svg'" alt="" class="w-6 h-6">
                        </div>
                    </div>
                </div>

                <div class="grow">
                    <DashboardMyCart />
                </div>

            </div>

            <div class="col-span-1 xl:col-span-1 flex flex-col gap-4">
                <div>
                    <DashboardDefaultShippingAddress />
                </div>

                <div class="grow">
                    <DashboardRecenlyViewVue />
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthPageheader from '../components/AuthPageheader.vue';
import { HeartIcon } from '@heroicons/vue/24/outline';
import DashboardDefaultShippingAddress from '../components/DashboardDefaultShippingAddress.vue';
import DashboardRecenlyViewVue from '../components/DashboardRecenlyView.vue';
import DashboardMyCart from '../components/DashboardMyCart.vue';

import { useBaskerStore } from '../stores/BasketStore';
import { useAuth } from '../stores/AuthStore';

const basketStore = useBaskerStore();
const AuthStore = useAuth();

onMounted(() => {
    fetchOrders();
});

const statusOrders = ref({
    all: 0,
    pending: 0,
    confirm: 0,
    processing: 0,
    on_the_way: 0,
    delivered: 0,
    cancelled: 0
});

const fetchOrders = async () => {
    axios.get('/orders', {
        headers: {
            Authorization: AuthStore.token,
        }
    }).then((response) => {
        statusOrders.value = response.data.data.status_wise_orders;
    })
};


</script>
