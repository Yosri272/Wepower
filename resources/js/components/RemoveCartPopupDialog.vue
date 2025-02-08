<template>
    <div class="flex gap-3">
        <img :src="product?.thumbnail ? product?.thumbnail : product?.thumbnails[0]?.thumbnail" alt=""
            class="w-24 h-24 object-cover rounded-md">
        <div class="flex flex-col gap-1">
            <div class="text-slate-950 text-base font-medium leading-normal max-w-64 truncate">
                {{ product?.name }}
            </div>
            <div class="text-slate-600 text-sm font-normal leading-tight">has been remove to cart.</div>
            <button @click.stop="clicked" class="py-2 px-3 h-10 bg-primary rounded-lg text-white text-sm font-normal w-fit">
                Again Add to Cart
            </button>
        </div>
    </div>
</template>

<script setup>
import { useBaskerStore } from '../stores/BasketStore';
const basketStore = useBaskerStore();

const props = defineProps({
    product: Object
});

const clicked = () => {
    const formData = {
        product_id: props.product?.id,
        quantity: 1,
        color: null,
        size: null,
        unit: null
    }
    basketStore.addToCart(formData, props.product);
}

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
