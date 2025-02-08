<template>
    <div class="main-container pt-8 pb-6 sm:pt-14 sm:pb-10 bg-primary-950 text-white">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <!--===== ReadyeCommerce (col-1) =====-->
            <div>
                <div>
                    <img :src="master.footerLogo" class="h-10" loading="lazy" />
                </div>
                <div class="mt-6 max-w-[328px]">
                    <div class="text-white text-sm font-normal leading-normal">
                        {{ master.footerDescription }}
                    </div>

                    <div class="mt-4 p-4 bg-black bg-opacity-20 rounded-[56px] flex gap-3">
                        <DevicePhoneMobileIcon class="w-6 h-6 text-white" />
                        <div class="w-[0px] h-6 border border-primary-900"></div>
                        <div class="text-white text-base font-normal leading-normal">
                            {{ master.mobile }}
                        </div>
                    </div>
                    <div class="mt-3 p-4 bg-black bg-opacity-20 rounded-[56px] flex gap-3">
                        <EnvelopeIcon class="w-6 h-6 text-white" />
                        <div class="w-[0px] h-6 border border-primary-900"></div>
                        <div class="text-white text-base font-normal leading-normal">
                            {{ master.email }}
                        </div>
                    </div>
                </div>

                <div class="flex justify-start mt-5 items-center gap-6">
                    <div class="flex items-center gap-3">
                        <a v-for="socialLink in master.socialLinks" :key="socialLink.name" target="_blank" :href="socialLink.link" class="w-8 h-8 bg-primary-800 rounded-full overflow-hidden">
                            <img :src="socialLink.logo" alt="" class="w-full h-full object-cover">
                        </a>
                    </div>
                </div>
            </div>

            <!--===== Quick Links (col-2) =====-->
            <div class="mt-4 sm:mt-0">
                <div class="text-white text-lg font-medium leading-normal tracking-tight">Quick Links</div>

                <div class="flex flex-col gap-2 mt-6">
                    <router-link v-if="master.getMultiVendor" to="/shops"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Shops
                    </router-link>
                    <router-link to="/most-popular"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Most Popular
                    </router-link>
                    <router-link to="/best-deal"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Best Deal
                    </router-link>
                    <a v-if="master.getMultiVendor" href="/shop"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Become a Seller
                    </a>

                    <router-link v-if="!master.getMultiVendor" to="/contact-us"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Contact
                    </router-link>
                </div>
            </div>

            <!--===== Company (col-3) =====-->
            <div class="mt-4 lg:mt-0">
                <div class="text-white text-lg font-medium leading-normal tracking-tight">Company</div>

                <div class="flex flex-col gap-2 mt-6">
                    <router-link to="/about-us"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        About us
                    </router-link>
                    <router-link v-if="master.getMultiVendor" to="/contact-us"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Contact
                    </router-link>
                    <router-link to="/terms-and-conditions"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Terms & Conditions
                    </router-link>
                    <router-link to="/privacy-policy"
                        class="py-2 hover:text-primary text-white text-base font-normal leading-normal">
                        Privacy Policy
                    </router-link>
                </div>
            </div>

            <!--===== Download our app (col-4) =====-->
            <div v-if="master.showDownloadApp" class="mt-4 lg:mt-0">
                <div class="text-white text-lg font-medium leading-normal tracking-tight">
                    Download our app
                </div>

                <div class="mt-6">
                    <div v-if="master.footerQr" class="bg-white rounded-md w-28 overflow-hidden">
                        <img :src="master.footerQr" class="h-28 w-full" loading="lazy" />
                        <div class="text-center text-primary-950 text-sm font-normal leading-tight pb-1">
                            Scan the QR
                        </div>
                    </div>

                    <div class="flex justify-start gap-4 mt-6">
                        <button class="border-none w-[119.70px] h-10 py-1 px-2 bg-primary-900 rounded-lg" @click="appStore">
                            <img :src="'/assets/icons/appstoreFooter.svg'" alt="appstore" class="w-full h-full object-fill" loading="lazy" />
                        </button>
                        <button class="border-none w-[119.70px] h-10 py-1 px-2 bg-primary-900 rounded-lg" @click="playStore">
                            <img :src="'/assets/icons/playstoreFooter.svg'" alt="playstore" class="w-full h-full object-fill" loading="lazy" />
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { DevicePhoneMobileIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';

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

</script>
