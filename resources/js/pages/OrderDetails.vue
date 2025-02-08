<template>
    <div>
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2">
            <HomeIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/order-history" class="leading-normal hover:text-primary">
                Order History
            </router-link>
            <span class="leading-normal">/ Order Details</span>
        </div>

        <!-- Header -->
        <AuthPageheader title="Order Details" />

        <!-- Order details -->
        <div class="px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6">
            <OrderDetailsOrderStatus :order="order" />

            <div class="grid grid-cols-3 gap-4 md:gap-6 mt-4 p-3 md:p-4 xl:p-6 bg-white rounded-lg md:rounded-2xl">
                <!-- column 1 -->
                <div
                    class="col-span-3 lg:col-span-2 bg-white rounded-lg md:rounded-2xl border border-slate-100 p-3 md:p-4 xl:p-6">

                    <div class="text-slate-500 text-xs md:text-base font-normal leading-none">
                        Purchased from
                    </div>
                    <div class="flex items-center gap-3 mt-2">
                        <img class="w-9 h-9 md:w-12 md:h-12" :src="order.shop?.logo" />
                        <div class="flex flex-wrap gap-2 justify-between items-start grow">
                            <div>
                                <div class="text-slate-950 text-sm md:text-base font-normal leading-tight">
                                    {{ order.shop?.name }}
                                </div>
                                <div class="flex items-center gap-1">
                                    <StarIcon class="w-5 h-5 text-yellow-400" />
                                    <span class="text-slate-800 text-xs md:text-base font-medium">{{ order.shop?.rating
                                        }}</span>
                                </div>
                            </div>

                            <!-- <button
                                class="text-primary text-xs md:text-sm font-normal leading-tight px-2 py-1 rounded-lg border border-primary">
                                Rate the Seller
                            </button> -->
                        </div>
                    </div>

                    <div class="w-full h-[0px] border-t border-slate-200 my-3"></div>

                    <div class="text-slate-600 text-sm md:text-base font-normal leading-tight">
                        Products ({{ order.products?.length }})
                    </div>

                    <OrderProducts :order="order" />
                </div>

                <!-- column 2 -->
                <div class="col-span-3 lg:col-span-1">

                    <OrderDetailsSummery :order="order" @update:paymentSuccess="fetchOrderDetails"/>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from "vue";
import { HomeIcon } from "@heroicons/vue/24/outline";
import { StarIcon } from "@heroicons/vue/24/solid";
import AuthPageheader from "../components/AuthPageheader.vue";
import OrderDetailsOrderStatus from "../components/OrderDetailsOrderStatus.vue";
import OrderProducts from "../components/OrderProducts.vue";
import OrderDetailsSummery from "../components/OrderDetailsSummery.vue";
import { useRoute } from 'vue-router';

import { useAuth } from "../stores/AuthStore";
const authStore = useAuth();
const route = useRoute();

const order = ref({});

watch(() => authStore.orderCancel, () => {
    if (authStore.orderCancel == true) {
        fetchOrderDetails();
    }
    authStore.orderCancel = false;
});

onMounted(() => {
    fetchOrderDetails();
    window.scrollTo(0, 0, { behavior: 'smooth' });
});

const fetchOrderDetails = async () => {
    axios.get('/order-details', {
        params: { order_id: route.params.id },
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        order.value = response.data.data.order;
    });
};


</script>
