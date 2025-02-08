<template>
    <div class="p-4 lg:p-6 bg-white rounded-xl flex flex-col">
        <div class="flex gap-1 justify-between items-center">
            <div class="text-sm sm:text-base font-normal shrink-0">
                <span class="text-slate-500">
                    Order ID
                </span>
                <span class="text-blue-500">
                    {{ order.order_code }}
                </span>
            </div>

            <!-- Order Status -->
            <div
                class="text-xs sm:text-sm font-normal px-2 py-1 rounded-[10px] inline-block  text-ellipsis overflow-hidden" :class="order.order_status">
                {{ order.order_status }}
            </div>
        </div>
        <div class="flex items-center gap-1 sm:gap-3 mt-1 sm:mt-0 flex-wrap">
            <div class="inline-flex gap-2 text-sm md:text-base leading-tight text-ellipsis">
                <span class="text-slate-500 shrink-0">Placed on</span>
                <span class="text-slate-950 whitespace-nowrap text-ellipsis overflow-hidden">{{ order.placed_at }}</span>
            </div>

            <div class="w-2 h-2 bg-slate-300 rounded-full hidden sm:block"></div>

            <div class="flex gap-2">
                <div class="text-slate-500 text-sm md:text-base font-normal leading-normal">
                    Esd. delivery
                </div>
                <div class="text-slate-950 text-sm md:text-base font-normal leading-normal">
                    10 Jan, 2023
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    order: Object
});

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
