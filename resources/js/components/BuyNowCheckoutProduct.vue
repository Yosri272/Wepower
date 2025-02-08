<template>
  <div class="space-y-4 mt-3 transition duration-300">
    <div class="px-4 py-3 bg-slate-50 rounded-xl border border-slate-100"
    >
      <!-- Shop Name -->
      <div class="text-slate-950 text-base font-medium leading-normal">
        {{ basketStore.buyNowProduct?.shop?.name }}
      </div>

      <div class="space-y-2 divide-y divide-slate-200">
        <!-- item 1-->
        <div class="flex gap-4 justify-start w-full items-center pt-1"
        >
          <div class="w-[72px] h-[95px]">
            <img
              :src="basketStore.buyNowProduct?.thumbnail ? basketStore.buyNowProduct?.thumbnail : basketStore.buyNowProduct?.thumbnails[0]?.thumbnail"
              class="w-full h-full object-contain"
            />
          </div>
          <div class="flex flex-col gap-1 w-full">
            <!-- Brand -->
            <div class="text-primary text-xs font-normal leading-none">
              {{ basketStore.buyNowProduct?.brand }}
            </div>
            <!-- basketStore.buyNowProduct? Name -->
            <div class="text-slate-950 text-base font-normal leading-normal">
              {{ basketStore.buyNowProduct?.name }}
            </div>
            <div class="flex flex-wrap justify-between items-center gap-3">
              <!-- Size and color -->
              <div class="flex items-center gap-1">
                <div
                  class="min-w-8 text-center px-2 py-1 bg-slate-100 rounded text-slate-800 text-xs font-normal"
                >
                  {{ basketStore.buyNowProduct?.size }}
                </div>
                <div
                  class="px-2 py-1 bg-slate-100 rounded text-slate-800 text-xs font-normal"
                >
                  {{ basketStore.buyNowProduct?.color }}
                </div>
              </div>
              <!-- quantity and price -->
              <div class="text-slate-800 text-base font-normal leading-normal">
                1 X {{
                  master.showCurrency(
                    basketStore.buyNowProduct?.discount_price > 0
                      ? basketStore.buyNowProduct?.discount_price
                      : basketStore.buyNowProduct?.price
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
