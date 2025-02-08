<template>
    <div>
        <div class="mt-6 sm:mt-8 flex justify-between items-center gap-2">
            <div class="text-slate-950 text-xl font-medium leading-7">
                Shipping Address
            </div>

            <button v-if="authStore.addresses.length > 0" class="text-slate-950 text-base font-normal leading-normal"
                @click="authStore.showChangeAddressModal = true">
                Change
            </button>
        </div>

        <!-- Shipping Address form -->
        <Transition leave-active-class="transition ease-in duration-300"
            enter-active-class="transition ease-out duration-300" enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100" leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <div v-if="authStore.addresses.length == 0">
                <address-form />
            </div>
        </Transition>

        <!-- Selected Address -->
        <div v-if="authStore.addresses.length > 0"
            class="mt-4 p-4 flex gap-6 rounded-lg border border-slate-200 w-full">
            <div
                class="flex w-[60px] sm:w-[88px] bg-slate-50 rounded-lg flex-col gap-2 justify-center items-center shrink-0">
                <MapPinIcon class="w-6 h-6 text-primary-600" />
                <div class="px-1.5 py-[3px] bg-slate-800 rounded-md text-white text-xs font-medium uppercase">
                    {{ basketStore.address?.address_type }}
                </div>
            </div>
            <div class="overflow-hidden">
                <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                    {{ basketStore.address?.name }}
                </div>
                <div class="text-slate-500 text-base font-normal leading-normal">
                    {{ basketStore.address?.phone }}
                </div>
                <div class="text-slate-500 text-base font-normal leading-normal truncate">
                    {{ basketStore.address?.flat_no + ', ' + basketStore.address?.address_line + ', ' +
                basketStore.address?.address_line2 }}, {{ basketStore.address?.area + '-' +
                basketStore.address?.post_code }}
                </div>
            </div>
        </div>

        <!-- Change Address Dialog Modal -->
        <AddressChangeDialogModal />
        <!-- End Change Address Dialog Modal -->

        <!-- new Address Dialog Modal -->
        <AddressFormModal />
        <!-- End new Address Dialog Modal -->

    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'

import { MapPinIcon, CheckCircleIcon, XMarkIcon, PlusIcon } from '@heroicons/vue/24/solid';
import AddressForm from './AddressForm.vue'
import AddressFormModal from './AddressFormModal.vue'
import AddressChangeDialogModal from './AddressChangeDialogModal.vue';

import { useAuth } from '../stores/AuthStore';
import { useBaskerStore } from '../stores/BasketStore';

const basketStore = useBaskerStore();

const changeAddress = ref(false);

const authStore = useAuth();

onMounted(() => {
    authStore.fetchAddresses()
    fetchADefaultAddress()
})

const fetchADefaultAddress = () => {
    if (!basketStore.address) {
        authStore.addresses.forEach((address) => {
            if (address.is_default) {
                basketStore.address = address
                return true;
            }
        })
    }
}

</script>
