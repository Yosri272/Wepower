<template>
    <div
        class="p-2 md:px-4 md:py-3 bg-white rounded-lg border border-slate-100 flex gap-2 lg:gap-4 flex-col lg:flex-row items-center justify-between">

        <div class="w-full lg:w-[50%] flex flex-col overflow-hidden">
            <div class="flex gap-1 justify-between">
                <div class="text-sm sm:text-base font-normal shrink-0">
                    <span class="text-slate-500">
                        Order ID
                    </span>
                    <span class="text-blue-500">
                        {{ props.order?.order_code }}
                    </span>
                </div>

                <!-- Order Status -->
                <div
                    class="text-xs sm:text-sm font-normal px-1.5 py-0.5  rounded-[10px] inline-block lg:hidden text-ellipsis overflow-hidden" :class="props.order?.order_status">
                    {{ props.order?.order_status }}
                </div>
            </div>

            <div class="inline-flex gap-2 text-xs sm:text-sm leading-tight text-ellipsis">
                <span class="text-slate-500 shrink-0">Placed on</span>
                <span class="text-slate-950 whitespace-nowrap text-ellipsis overflow-hidden">
                    {{ props.order?.placed_at }}
                </span>
            </div>
        </div>

        <div class="grow flex w-full gap-2 items-center justify-between">
            <div class="text-sm sm:text-base font-normal xl:w-20">
                <span class="text-slate-500">
                    QTY:
                </span>
                <span class="text-slate-950">{{ props.order?.quantity }}</span>
            </div>

            <div class="text-sm sm:text-base font-normal xl:w-36">
                <span class="text-slate-500">
                    Amount:
                </span>
                <span class="text-slate-950">
                    {{ master.showCurrency(props.order?.amount) }}
                </span>
            </div>

            <div class="hidden lg:block xl:w-28">
                <div class="text-sm font-normal px-1.5 py-0.5 rounded-[10px] inline-block" :class="props.order?.order_status">
                    {{ props.order?.order_status }}
                </div>
            </div>

            <div class="flex justify-end">
                <router-link :to="'/order-history/'+props.order?.id"
                    class="text-slate-500 text-sm sm:text-base font-normal leading-normal">
                    View Details
                </router-link>
            </div>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    order: Object
});

import { useMaster } from "../stores/MasterStore";

const master = useMaster();

</script>

<style scoped>
.Pending{
    @apply bg-yellow-500 text-white;
}
.Confirm{
    @apply bg-blue-500 text-white;
}
.Processing, .On, .Pickup {
    @apply bg-primary text-white;
}
.Delivered {
    @apply bg-green-500 text-white;
}
.Cancelled {
    @apply bg-red-500 text-white;
}
</style>
