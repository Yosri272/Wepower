<template>
    <div>
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2 leading-normal">
            <HomeIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/manage-address" class="hover:text-primary">
                Manage Address
            </router-link>
            <span>/ Add New Address</span>
        </div>
        <!-- Header -->
        <AuthPageheader title="Add New Address" />

        <div class="p-3 md:p-4 xl:p-6">

            <div class="max-w-5xl mx-auto">
                <AddressForm />
            </div>

        </div>
    </div>
</template>

<script setup>
import AuthPageheader from '../components/AuthPageheader.vue';
import AddressForm from '../components/AddressForm.vue';
import { HomeIcon } from '@heroicons/vue/24/solid';

</script>
