<template>
  <div class="space-y-4 mt-3 transition duration-300">
    <div
      v-for="shopProduct in basketStore.checkoutProducts"
      :key="shopProduct.shop_id"
      class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100"
    >
      <!-- Shop Name -->
      <div class="text-slate-950 text-base font-medium leading-normal">
        {{ shopProduct.shop_name }}
      </div>

      <div class="space-y-2 divide-y divide-slate-200">
        <!-- item 1-->
        <div
          v-for="product in shopProduct.products"
          :key="product.id"
          class="flex gap-4 justify-start w-full items-center pt-1"
        >
          <div class="w-[72px] h-[95px]">
            <img
              :src="product.thumbnail"
              class="w-full h-full object-contain"
            />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <!-- Brand -->
            <div class="text-primary text-xs font-normal leading-none">
              {{ product.brand }}
            </div>
            <!-- Product Name -->
            <div class="text-slate-950 text-base font-normal leading-normal">
              {{ product.name }}
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
                {{ product.quantity }} X
                {{
                  master.showCurrency(
                    product.discount_price > 0
                      ? product.discount_price
                      : product.price
                  )
                }}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuth } from "../stores/AuthStore";
import { useMaster } from "../stores/MasterStore";
import { useBaskerStore } from "../stores/BasketStore";

const AuthStore = useAuth();
const master = useMaster();
const basketStore = useBaskerStore();
</script>

<style lang="scss" scoped></style>
