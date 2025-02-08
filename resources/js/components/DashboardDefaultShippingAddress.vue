<template>
    <div class="bg-white rounded-xl p-6">

        <div class="pb-3 text-slate-950 text-lg font-medium leading-normal tracking-tight">
            Default Shipping Address
        </div>

        <div class="border-t border-slate-200">
            <!-- Address added -->
            <div v-if="basketStore.address" class="mt-4 flex flex-col gap-2">
                <div class="flex gap-1.5 items-center">
                    <div class="text-slate-950 text-base font-medium leading-normal">{{ basketStore.address.name }}
                    </div>
                    <div
                        class="flex items-center px-1.5 py-[3px] bg-slate-100 rounded-md text-slate-950 text-xs font-normal leading-none">
                        {{ basketStore.address.address_type }}
                    </div>
                </div>

                <div class="text-slate-700 text-base font-normal leading-normal">
                    {{ basketStore.address.phone }}
                </div>

                <div class="text-slate-700 text-base font-normal leading-normal">
                    {{ basketStore.address.flat_no + ', ' + basketStore.address.address_line + ', ' +
                basketStore.address.address_line2 }}, {{ basketStore.address.area + '-' +
                    basketStore.address.post_code }}
                </div>
            </div>

            <!-- No address added yet -->
            <div v-else class="px-3 py-4 rounded-lg border border-dashed border-primary-300 mt-4 text-center">
                <div class="text-slate-400 text-lg font-normal leading-7 tracking-tight">
                    No address added yet.
                </div>
                <div class="text-center mt-3 text-primary text-sm font-normal leading-tight cursor-pointer" @click="authStore.showAddressModal = true">
                    Click to add new address
                </div>
            </div>

        </div>

        <!-- Add Address Dialog Modal -->
        <AddressFormModal />
    </div>
</template>

<script setup>
import { useAuth } from '../stores/AuthStore';
import { useBaskerStore } from '../stores/BasketStore';
import AddressFormModal from './AddressFormModal.vue';

const authStore = useAuth();
const basketStore = useBaskerStore();

</script>

<style lang="scss" scoped></style>
