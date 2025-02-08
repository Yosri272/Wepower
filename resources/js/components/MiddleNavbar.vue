<template>
    <div class="main-container py-2 flex items-center justify-between gap-8">

        <div class="flex items-center gap-8 grow">
            <router-link to="/" class="w-[130px] md:w-[180px] lg:w-[240px]">
                <img :src="master.logo" alt="" class="h-11">
            </router-link>
            <div class="relative overflow-hidden grow max-w-[800px] hidden md:block">
                <input type="text" v-model="search" placeholder="Search product"
                    class="px-2 py-3 block rounded-lg border border-slate-200 focus:border-primary w-full placeholder:text-gray-400 outline-none text-base font-normal leading-normal">
                <button
                    class="bg-primary-600 h-full w-14 border-none absolute right-0 top-0 rounded-r-lg flex items-center justify-center" @click="searchProducts()">
                    <MagnifyingGlassIcon class="w-6 h-6 text-white" />
                </button>
            </div>
        </div>

        <div class="hidden md:flex items-center justify-end md:gap-4 lg:gap-8">
            <div class="flex items-center md:gap-1 lg:gap-3">
                <div class="p-3 cursor-pointer" @click="showWishlist()">
                    <div class="w-6 h-6 relative">
                        <img :src="'/assets/icons/heart.svg'" class="w-6 h-6 text-primary" />
                        <span
                            class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ AuthStore.favoriteProducts }}
                        </span>
                    </div>
                </div>

                <button class="p-3" @click="master.basketCanvas = true">
                    <div class="w-6 h-6 relative">
                        <img :src="'/assets/icons/bag.svg'" class="w-6 h-6 text-primary" />
                        <span
                            class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ basketStore.total }}
                        </span>
                    </div>
                </button>
            </div>

            <button v-if="!AuthStore.user" class="flex items-center gap-2 lg:p-3 text-slate-600 hover:text-primary"
                @click="showLoginDilog">
                <span class="text-base font-normal leading-normal">Login</span>
                <UserIcon class="w-5 h-5" />
            </button>
            <div v-else>
                <AuthUserDropdown />
            </div>
        </div>

        <!--******=== Mobile View Navbar ===********-->
        <div class="md:hidden flex items-center gap-4">

            <!-- Search Icon -->
            <div class="h-10 w-10 flex items-center justify-center bg-slate-100 rounded-[40px]">
                <MagnifyingGlassIcon class="w-5 h-5 text-slate-950" />
            </div>

            <div class="h-10 w-10 flex items-center justify-end" @click="mobileMenuOpen = true">
                <Bars3Icon class="w-6 h-6 text-slate-950" />
            </div>

            <!-- Filter Canvas Drawer -->
            <TransitionRoot as="template" :show="mobileMenuOpen">
                <Dialog as="div" class="relative z-10" @close="mobileMenuOpen = false">
                    <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                        enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-hidden">
                        <div class="absolute inset-0 overflow-hidden">
                            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                                <TransitionChild as="template"
                                    enter="transform transition ease-in-out duration-500 sm:duration-700"
                                    enter-from="translate-x-full" enter-to="translate-x-0"
                                    leave="transform transition ease-in-out duration-500 sm:duration-700"
                                    leave-from="translate-x-0" leave-to="translate-x-full">
                                    <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                                        <TransitionChild as="template" enter="ease-in-out duration-500"
                                            enter-from="opacity-0" enter-to="opacity-100"
                                            leave="ease-in-out duration-500" leave-from="opacity-100"
                                            leave-to="opacity-0">
                                            <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4">
                                            </div>
                                        </TransitionChild>
                                        <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl p-4">

                                            <div class="flex justify-between items-center">
                                                <div
                                                    class="text-slate-950 text-lg font-bold leading-normal tracking-tight">
                                                    Menu</div>
                                                <button
                                                    class="w-7 h-7 flex justify-center items-center bg-slate-100 rounded-full"
                                                    @click="mobileMenuOpen = false">
                                                    <XMarkIcon class="w-5 h-5 text-slate-700" />
                                                </button>
                                            </div>

                                            <!-- login button -->
                                            <div v-if="!AuthStore.user" class="mt-5 p-2 bg-primary rounded-lg">
                                                <div class="px-3 py-2.5 bg-white rounded-md border border-slate-100 flex justify-between"
                                                    @click="showLoginDilog">
                                                    <div class="flex items-center gap-2">
                                                        <UserIcon class="w-5 h-5 text-slate-600" />
                                                        <div class="text-slate-600 text-sm font-normal leading-tight">
                                                            Login
                                                        </div>
                                                    </div>
                                                    <ChevronRightIcon class="w-5 h-5 text-slate-600" />
                                                </div>
                                            </div>

                                            <div v-else class="bg-primary-100 p-3 rounded-lg mt-5">
                                                <AuthUserDropdown />
                                            </div>

                                            <div
                                                class="p-2 bg-slate-50 rounded-lg border border-slate-100 flex flex-col gap-1 mt-5">

                                                <div class="flex justify-between items-center px-3 py-2.5 bg-white rounded-md border border-slate-100 gap-2"
                                                    @click="showWishlist()">
                                                    <div class="flex items-center gap-2">
                                                        <img :src="'/assets/icons/heart.svg'"
                                                            class="w-5 h-5 text-slate-600" />
                                                        <div class="text-slate-600 text-sm font-normal leading-tight">
                                                            Wishlist
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="w-5 h-5 bg-red-500 rounded-3xl border border-white flex justify-center items-center text-white">
                                                        <span class="text-white text-xs font-bold">
                                                            {{ AuthStore.favoriteProducts }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="flex justify-between items-center px-3 py-2.5 bg-white rounded-md border border-slate-100 gap-2"
                                                    @click="showMyCart()">
                                                    <div class="flex items-center gap-2">
                                                        <img :src="'/assets/icons/bag.svg'"
                                                            class="w-6 h-6 text-slate-600" />
                                                        <div class="text-slate-600 text-sm font-normal leading-tight">
                                                            My Cart
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="w-5 h-5 bg-red-500 rounded-3xl border border-white flex justify-center items-center text-white">
                                                        <span class="text-white text-xs font-bold">
                                                            {{ basketStore.total }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="justify-start inline-flex grow flex-col mt-5">
                                                <router-link to="/"
                                                    class="py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                                                    Home
                                                </router-link>

                                                <div class="w-full my-1 h-[0px] border border-slate-200"></div>

                                                <router-link to="/shops"
                                                    class="py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                                                    Shops
                                                </router-link>

                                                <div class="w-full my-1 h-[0px] border border-slate-200"></div>

                                                <router-link to="/most-popular"
                                                    class="py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                                                    Most Popular
                                                </router-link>

                                                <div class="w-full my-1 h-[0px] border border-slate-200"></div>

                                                <router-link to="/best-deal"
                                                    class="py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                                                    Best Deal
                                                </router-link>

                                                <div class="w-full my-1 h-[0px] border border-slate-200"></div>

                                                <router-link to="/contact-us"
                                                    class="py-2 border-b-2 border-transparent text-base font-normal text-slate-600">
                                                    Contact
                                                </router-link>

                                            </div>

                                        </div>
                                    </DialogPanel>
                                </TransitionChild>
                            </div>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

        </div>

    </div>
    <!-- Login Dialog Modal -->
    <LoginModal />
    <!-- End Login Dialog Modal -->
</template>

<script setup>
import { ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { MagnifyingGlassIcon, EyeSlashIcon, EyeIcon } from '@heroicons/vue/24/solid'
import { UserIcon, XMarkIcon, Bars3Icon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import LoginModal from './LoginModal.vue'
import AuthUserDropdown from './AuthUserDropdown.vue'

import { useAuth } from '../stores/AuthStore'
import { useBaskerStore } from '../stores/BasketStore'
import { useMaster } from '../stores/MasterStore'

const route = useRoute();
const router = useRouter();
const basketStore = useBaskerStore();

const AuthStore = useAuth();
const master = useMaster();

const search = ref('');

const showMyCart = () => {
    mobileMenuOpen.value = false;
    master.basketCanvas = true
}

const showWishlist = () => {
    mobileMenuOpen.value = false;
    if (!AuthStore.token) {
        return showLoginDilog();
    } else {
        router.push('/wishlist')
    }
}

watch(() => route.path, () => {
    mobileMenuOpen.value = false;
});

const mobileMenuOpen = ref(false);

const showLoginDilog = () => {
    AuthStore.showLoginModal();
}

const searchProducts = () => {
    master.search = search.value
    search.value = '';
    router.push({ name: 'products' })
}

</script>

<style scoped>
.router-link-active {
    @apply border-primary text-primary
}
</style>
