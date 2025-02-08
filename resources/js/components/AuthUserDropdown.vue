<template>
    <Menu as="div" class="relative hidden md:inline-block text-left" v-slot="{ open }">
        <div>
            <MenuButton class="inline-flex w-full items-center gap-2 p-2 rounded-lg"
                :class="open ? 'bg-primary-100 text-primary' : 'text-slate-600'">
                <span class="max-w-24 truncate text-base font-normal leading-normal">
                    {{ authStore.user?.name }}
                </span>
                <div class="relative">
                    <img :src="authStore.user?.profile_photo" alt="" class="w-8 h-8 object-cover rounded-full">
                    <div class="w-4 h-4 absolute -bottom-2 right-2/4 translate-x-2/4 rounded-2xl"
                        :class="open ? 'bg-primary-100' : 'bg-white'">
                        <ChevronDownIcon class="w-4 h-4" />
                    </div>
                </div>
            </MenuButton>
        </div>

        <transition enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
            <MenuItems
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg border border-primary-300">
                <div class="py-3 px-4 flex flex-col gap-2">
                    <MenuItem>
                    <router-link to="/dashboard"
                        class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem">
                        <img :src="'/assets/icons/dashboard.svg'" alt="" width="24" height="24"> Dashboard
                    </router-link>
                    </MenuItem>

                    <MenuItem>
                    <router-link to="/change-password"
                        class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem">
                        <img :src="'/assets/icons/key.svg'" alt="" width="24" height="24"> Change Password
                    </router-link>
                    </MenuItem>

                    <MenuItem>
                    <button class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem"
                        @click="logoutModal = true">
                        <img :src="'/assets/icons/logout.svg'" alt="" width="24" height="24"> Log Out
                    </button>
                    </MenuItem>
                </div>
            </MenuItems>
        </transition>
    </Menu>

    <div class="relative md:hidden">
        <div class="flex justify-between w-full items-center gap-2 rounded-lg text-primary pb-2 mb-2">
            <span class="truncate text-base font-normal leading-normal">
                {{ authStore.user?.name }}
            </span>
            <div class="relative">
                <img :src="authStore.user?.profile_photo" alt="" class="w-8 h-8 object-cover rounded-full">
                <div class="w-4 h-4 absolute -bottom-2 right-2/4 translate-x-2/4 rounded-2xl bg-primary-100">
                    <ChevronDownIcon class="w-4 h-4" />
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2 p-2 bg-white rounded-lg">
            <router-link to="/dashboard"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between">
                <span class="flex gap-2">
                    <img :src="'/assets/icons/dashboard.svg'" alt="" width="24" height="24"> Dashboard
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <router-link to="/change-password"
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between">
                <span class="flex gap-2 truncate overflow-hidden">
                    <img :src="'/assets/icons/key.svg'" alt="" width="24" height="24"> Change Password
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </router-link>

            <button
                class="flex gap-2 text-slate-600 text-base py-2 hover:text-primary menuLinkItem w-full justify-between"
                @click="logoutModal = true">
                <span class="flex gap-2">
                    <img :src="'/assets/icons/logout.svg'" alt="" width="24" height="24"> Log Out
                </span>
                <ChevronRightIcon class="w-4 h-4 text-slate-400" />
            </button>
        </div>
    </div>
    <!-- Logout modal -->
    <TransitionRoot as="template" :show="logoutModal">
        <Dialog as="div" class="relative z-10" @close="logoutModal = false">
            <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <TransitionChild as="template" enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="bg-white p-5 sm:p-8 text-center">

                                <div class="bg-red-500 w-20 h-20 rounded-full mx-auto flex justify-center items-center">
                                    <img :src="'/assets/icons/logoutWhite.svg'" alt="icon" loading="lazy" />
                                </div>

                                <div
                                    class="mt-3 text-center text-gray-900 text-3xl font-bold font-['Roboto'] leading-9">
                                    Log Out!</div>

                                <div
                                    class="mt-4 text-center text-slate-700 text-xl font-normal font-['Roboto'] leading-7">
                                    Are you sure want to log out?
                                </div>

                                <div class="flex justify-between items-center gap-4 mt-8">
                                    <button
                                        class="text-slate-800 grow text-base font-medium  px-6 py-4 rounded-[10px] border border-slate-300"
                                        @click="logoutModal = false">Cancel</button>

                                    <button
                                        class="text-white grow bg-red-500 text-base font-medium px-6 py-4 rounded-[10px]"
                                        @click="logout">Yes</button>
                                </div>

                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>


</template>

<script setup>
import { ref } from 'vue'
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ChevronDownIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

import { useAuth } from '../stores/AuthStore'
import { useToast } from 'vue-toastification';
import { useBaskerStore } from '../stores/BasketStore';

const authStore = useAuth();
const basketStore = useBaskerStore();

const toast = useToast();

const logoutModal = ref(false)


const logout = () => {
    authStore.logout();
    basketStore.total = 0;
    basketStore.checkoutProducts = [];
    basketStore.products = [];
    basketStore.address = null;
    basketStore.selectedShopIds = [];
    basketStore.coupon_code = '';
    basketStore.payable_amount = 0;
    basketStore.delivery_charge = 0;
    basketStore.coupon_discount = 0;

    toast.success('Logout successfully', {
        position: "bottom-left",
    });
}

</script>

<style>
.menuLinkItem:hover img {
    filter: brightness(0) saturate(100%) invert(39%) sepia(96%) saturate(6525%) hue-rotate(256deg) brightness(97%) contrast(91%);
}
</style>
