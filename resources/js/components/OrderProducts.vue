<template>
  <div class="space-y-2 divide-y divide-slate-200">
    <!-- item 1-->
    <div
      v-for="product in order.products"
      :key="product.id"
      class="flex gap-4 justify-start w-full items-center pt-1"
    >
      <div class="w-[72px] h-[95px]">
        <img :src="product.thumbnail" class="w-full h-full object-contain" />
      </div>
      <div class="flex flex-col gap-1 w-full relative">
        <!-- Brand -->
        <div class="text-primary text-xs font-normal leading-none">
          {{ product.brand }}
        </div>

        <div class="flex justify-between items-center">
          <!-- Product Name -->
          <div class="text-slate-950 text-base font-normal leading-normal">
            {{ product.name }}
          </div>
        </div>
        <div class="flex flex-wrap justify-between items-center gap-3">
          <!-- Size and color -->
          <div class="flex items-center gap-1">
            <div
              class="min-w-8 text-center px-2 py-1 bg-slate-100 rounded text-slate-800 text-xs font-normal"
            >
              {{ product.size }}
            </div>
            <div
              class="px-2 py-1 bg-slate-100 rounded text-slate-800 text-xs font-normal"
            >
              {{ product.color }}
            </div>
          </div>
          <!-- quantity and price -->
          <div class="text-slate-800 text-base font-normal leading-normal">
            {{ product.order_qty }} X
            {{
              master.showCurrency(
                product.discount_price > 0
                  ? product.discount_price
                  : product.price
              )
            }}
          </div>
        </div>

        <div v-show="order.order_status === 'Delivered'" class="mt-1 sm:mt-0 sm:absolute right-0" :class="order.brand ? 'top-2' : 'top-0'">
            <button v-if="!product.rating" class="px-2 py-1 w-full sm:w-auto bg-amber-500 rounded-md md:rounded-lg text-white text-sm font-normal">
                Review
            </button>

            <div v-else class="flex items-center gap-0">
                <StarIcon class="w-5 h-5 text-amber-500"/>
                <StarIcon class="w-5 h-5 text-amber-500"/>
                <StarIcon class="w-5 h-5 text-amber-500"/>
                <StarIcon class="w-5 h-5 text-amber-500"/>
                <StarIcon class="w-5 h-5 text-slate-200"/>
            </div>
        </div>


      </div>
    </div>
  </div>
</template>

<script setup>
import { useMaster } from "../stores/MasterStore";
import { StarIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
  order: Object,
  default: () => {},
});

const master = useMaster();

</script>
