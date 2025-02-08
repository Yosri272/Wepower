<template>
    <div class="main-container">

        <!-- Breakcrumbs -->
        <div class="flex items-center gap-2 overflow-hidden pt-4">
            <router-link to="/" class="w-6 h-6">
                <HomeIcon class="w-5 h-5 text-slate-600" />
            </router-link>

            <div class="grow w-full overflow-hidden">
                <div class="space-x-1 text-slate-600 text-sm font-normal truncate">
                    <span>Home</span>
                    <span>/</span>
                    <span>Cart</span>
                    <span>/</span>
                    <span>Checkout</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 my-3 gap-8">

            <div class="col-span-1 xl:col-span-2">

                <div class="py-4 border-b tran" :class="showProductItems ? 'border-primary' : 'border-slate-200'">
                    <!-- checkout -->
                    <div class="flex gap-2 justify-between items-center">
                        <div class="text-slate-950 text-lg sm:text-3xl font-medium leading-10">Checkout</div>
                        <div class="flex items-center gap-2 cursor-pointer"
                            @click="showProductItems = !showProductItems">
                            <div class="text-primary-600 text-lg font-medium leading-normal tracking-tight">
                                ({{ basketStore.buyNowProduct ? 1 : 0 }} items)
                            </div>
                            <ChevronDownIcon class="w-5 h-5 text-primary-600 transition duration-300"
                                :class="showProductItems ? 'rotate-180' : ''" />
                        </div>
                    </div>

                    <!-- Product items -->
                    <div v-if="showProductItems && basketStore.buyNowProduct">
                        <BuyNowCheckoutProduct/>
                    </div>
                </div>

                <!-- Shipping Address -->
                <ShippingAddress />

                <div class="mt-6">
                    <div class="mb-1">
                        <span class="text-slate-950 text-xl font-medium leading-7">Note</span>
                        <span class="text-slate-500 text-lg font-normal leading-7 tracking-tight">
                            (Optional)
                        </span>
                    </div>
                    <textarea v-model="note" rows="3" class="form-input"
                        placeholder="Write your note here..."></textarea>
                </div>

                <!-- Payment Method -->
                <div class="p-6 mt-4 bg-white rounded-2xl border border-slate-200">
                    <div class="text-slate-950 text-xl font-medium leading-7">
                        Payment Method
                    </div>

                    <div class="mt-4 flex flex-wrap gap-4">

                        <label for="cash" class="flex items-center gap-4 xl:min-w-80">
                            <input v-model="paymentType" id="cash" name="payment" type="radio" class="radioBtn2"
                                value="cash" checked />
                            <div class="p-2 bg-white rounded-xl border border-slate-200">
                                <img :src="'assets/icons/money-2.svg'" alt="" class="w-7 h-7">
                            </div>
                            <span class="text-slate-500 text-base font-normal leading-normal">Cash on delivery</span>
                        </label>

                        <label for="card" class="flex items-center gap-4 xl:min-w-80">
                            <input v-model="paymentType" id="card" name="payment" type="radio" class="radioBtn2"
                                value="card" />
                            <div class="p-2 bg-white rounded-xl border border-slate-200">
                                <img :src="'assets/icons/card.svg'" alt="" class="w-7 h-7">
                            </div>
                            <span class="text-slate-500 text-base font-normal leading-normal">
                                Credit or Debit Card
                            </span>
                        </label>

                    </div>
                    <!-- Payment Gateways -->
                    <Transition leave-active-class="transition ease-in duration-300"
                        enter-active-class="transition ease-out duration-300"
                        enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95">

                        <div v-if="paymentType === 'card'" class="mt-5 border-t border-slate-200">
                            <span class="text-slate-600 pt-2 block text-md font-medium leading-7">
                                Available Payment Gateways
                            </span>
                            <div class="mt-3 flex flex-wrap gap-4">
                                <label v-for="gateway in master.paymentGateways" :key="gateway.id" :for="gateway.name"
                                    class="flex items-center gap-4 border relative has-[:checked]:border-primary has-[:checked]:shadow-lg p-2 rounded-md border-slate-200 cursor-pointer">
                                    <input v-model="paymentGateway" :id="gateway.name" name="paymentGateway"
                                        type="radio" class="sr-only" :value="gateway.name" />
                                    <div class="">
                                        <img :src="gateway.logo" alt="" class="w-32 h-16 object-contain">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </Transition>

                </div>

            </div>

            <!-- Order Summary -->
            <BuyNowCheckoutOrderSummary :note="note" :paymentMethod="paymentMethod" />

        </div>

    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { HomeIcon, ChevronDownIcon } from '@heroicons/vue/24/outline';

import BuyNowCheckoutProduct from '../components/BuyNowCheckoutProduct.vue';
import BuyNowCheckoutOrderSummary from '../components/BuyNowCheckoutOrderSummary.vue';
import ShippingAddress from '../components/CheckoutShippingAddress.vue';

import { useMaster } from '../stores/MasterStore';
import { useBaskerStore } from '../stores/BasketStore';

import { useRouter } from 'vue-router';
const router = new useRouter();

import { useAuth } from '../stores/AuthStore';
const AuthStore = useAuth();

const master = useMaster();
const basketStore = useBaskerStore();

const showProductItems = ref(true);

const note = ref("");

const paymentType = ref('cash');
const paymentMethod = ref(null);

const paymentGateway = ref(null);

onMounted(() => {
    window.scrollTo(0, 0);
    basketStore.coupon_code = "";
    paymentMethod.value = paymentType.value;
    if (!AuthStore.user) {
        router.push({ name: 'home' });
    }
});

watch(paymentType, () => {
    if (paymentType.value === 'card') {
        paymentMethod.value = paymentGateway.value;
    } else {
        paymentMethod.value = paymentType.value;
    }
});

watch(paymentGateway, () => {
    if (paymentType.value === 'card') {
        paymentMethod.value = paymentGateway.value;
    }
});

</script>
<style scoped>
.form-label {
    @apply text-slate-700 text-base font-normal leading-normal;
}

.form-input {
    @apply p-3 rounded-lg border border-slate-200 focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}

.formInputCoupon {
    @apply rounded-lg border border-slate-200 focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}

.radio-btn {
    @apply w-5 h-5 border appearance-none border-slate-300 rounded-full checked:bg-primary ring-primary checked:outline-1 outline-offset-1 checked:outline-primary checked:outline transition duration-100 ease-in-out m-0;
}

.radioBtn2 {
    @apply w-4 h-4 border appearance-none border-slate-300 rounded-full checked:bg-primary ring-primary checked:outline-1 outline-offset-1 checked:outline-primary checked:outline transition duration-100 ease-in-out m-0;
}
</style>
