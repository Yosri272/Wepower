<template>
    <div class="bg-primary-700">
        <div class="main-container flex justify-between items-center py-2 text-white">

            <div class="flex sm:items-center flex-col sm:flex-row gap-1 sm:gap-4">
                <a v-if="master.getMultiVendor" href="/shop" class="text-white text-sm font-normal font-['Roboto'] leading-tight">
                    Become a Seller
                </a>
                <div v-if="master.getMultiVendor" class="w-[0] h-3 border border-primary-500 hidden sm:block"></div>
                <div class="text-white text-sm font-normal font-['Roboto'] leading-tight">
                    Hotline: {{ master.mobile }}
                </div>
            </div>

            <!-- <Menu as="div" class="relative inline-block text-left">
                <div>
                    <MenuButton
                        class="inline-flex items-center text-white font-['Roboto'] gap-1 text-sm font-normal leading-tight">
                        English
                        <ChevronDownIcon class="w-4 h-4" aria-hidden="true" />
                    </MenuButton>
                </div>

                <transition enter-active-class="transition ease-out duration-100"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-20 w-24 mt-1 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <div class="py-1">
                            <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">English</a>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Bangla</a>
                            </MenuItem>
                            <MenuItem v-slot="{ active }">
                            <a href="#"
                                :class="[active ? 'bg-gray-100 text-gray-900' : 'text-gray-700', 'block px-4 py-2 text-sm']">Hindi</a>
                            </MenuItem>
                        </div>
                    </MenuItems>
                </transition>
            </Menu> -->
        </div>
    </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'

import { useMaster } from "../stores/MasterStore";
const master = useMaster();

</script>
