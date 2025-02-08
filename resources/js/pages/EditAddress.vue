<template>
    <div>
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2 leading-normal">
            <HomeIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/manage-address" class="hover:text-primary">
                Manage Address
            </router-link>
            <span>/ Edit Address</span>
        </div>
        <!-- Header -->
        <AuthPageheader title="Edit Address" />

        <div class="p-3 md:p-4 xl:p-6">
            <div class="max-w-5xl mx-auto">
                <AddressEditForm :address="address"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AuthPageheader from '../components/AuthPageheader.vue';
import AddressEditForm from '../components/AddressEditForm.vue';
import { HomeIcon } from '@heroicons/vue/24/solid';
import { useAuth } from "../stores/AuthStore";

import { useRoute } from 'vue-router';

const route = useRoute();

const authStore = useAuth();

const address = ref({});

onMounted(() => {
   address.value = authStore.getAddressById(route.params.id);
   console.log(address.value);
});



</script>
