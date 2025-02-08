<template>
    <div
        class="main-container flex items-center justify-between md:gap-3 lg:gap-8 border-t border-b border-slate-100 flex-wrap md:flex-nowrap relative">

        <div class="xl:w-[240px] flex">
            <!--==== Categories dropdown menu ====-->
            <Popover v-slot="{ open }">
                <div class="border-r border-slate-100 p-1">
                    <PopoverButton class="h-10 lg:h-12 flex items-center gap-2 outline-none rounded-lg transition-all"
                        :class="open ? 'bg-primary-100 text-primary' : 'text-slate-600'">
                        <div class="w-12 md:w-auto lg:w-12 flex items-center justify-center">
                            <img :src="open ? '/assets/icons/menuActive.svg' : '/assets/icons/menu.svg'" alt=""
                                class="w-6 h-6" />
                        </div>
                        <div
                            class="hidden md:block text-sm lg:w-28 xl:w-36 text-left lg:text-base font-normal leading-normal">
                            Categories</div>
                    </PopoverButton>
                </div>

                <transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-1">

                    <PopoverPanel class="absolute pb-6 left-0 right-0 z-10 mt-0 flex main-container">
                        <div class="w-screen p-6 bg-white shadow-md grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-7 xl:grid-cols-10 gap-4 rounded-br-xl rounded-bl-xl">
                            <div v-for="category in master.categories" :key="category.id" class="">
                                <MenuCategory :category="category" @update:click="hiddenPopover" />
                            </div>
                        </div>
                    </PopoverPanel>
                </transition>
            </Popover>

        </div>

        <!-- Main menu -->
        <div class="hidden md:inline-flex justify-start items-center gap-2.5 lg:gap-4 xl:gap-8 grow">
            <router-link to="/" class="h-10 py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                Home
            </router-link>

            <div class="w-[0px] h-4 border border-slate-200"></div>

            <router-link v-if="master.getMultiVendor" to="/shops"
                class="h-10 py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                Shops
            </router-link>

            <div v-if="master.getMultiVendor" class="w-[0px] h-4 border border-slate-200"></div>

            <router-link to="/most-popular"
                class="h-10 py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                Most Popular
            </router-link>

            <div class="w-[0px] h-4 border border-slate-200"></div>

            <router-link to="/best-deal"
                class="h-10 py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                Best Deal
            </router-link>

            <div class="w-[0px] h-4 border border-slate-200"></div>

            <router-link to="/contact-us"
                class="h-10 py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                Contact
            </router-link>

        </div>

        <!-- Download our app -->
        <div v-if="master.showDownloadApp" class="inline-block">
            <Menu as="div" class="relative text-left" v-slot="{ open }">
                <div>
                    <MenuButton class="flex items-center gap-1 lg:gap-2 pr-1 lg:pr-3 p-3 rounded-lg"
                        :class="open ? 'bg-primary-100 text-primary' : 'text-slate-600'">
                        <DevicePhoneMobileIcon class="w-4 h-5" />
                        <div class="text-sm lg:text-base font-normal leading-normal">Download our app</div>
                        <ChevronDownIcon class="w-4 h-4 transition" :class="open ? 'rotate-180' : ''" />
                    </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75"
                    leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-10 mt-0 lg:w-56 origin-top-right p-3 bg-white rounded-xl shadow border border-primary-300  ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="flex-col flex gap-2">
                            <MenuItem v-slot="{ active }">
                            <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="playStore">
                                <img :src="'/assets/icons/playstore.png'" alt="">
                            </button>
                            </MenuItem>

                            <MenuItem v-slot="{ active }">
                            <button :class="active ? 'bg-gray-100 text-gray-900' : 'text-gray-700'" @click="appStore">
                                <img :src="'/assets/icons/applestore.png'" alt="">
                            </button>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu>

        </div>

    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { DevicePhoneMobileIcon, ChevronDownIcon } from '@heroicons/vue/24/outline'
import MenuCategory from './MenuCategory.vue';

import { useMaster } from "../stores/MasterStore";
const master = useMaster();

const appStore = () => {
    if (master.appStoreLink) {
        window.open(master.appStoreLink, '_blank');
    }
}

const playStore = () => {
    if (master.playStoreLink) {
        window.open(master.playStoreLink, '_blank');
    }
}

const hiddenPopover = () => {
   open = false
}

</script>

<style scoped>
.router-link-active {
    @apply border-b-2 border-primary text-primary
}
</style>
