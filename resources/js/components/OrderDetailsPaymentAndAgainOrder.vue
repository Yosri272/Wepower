<template>
    <div>
        <!-- Cancel modal -->
        <TransitionRoot as="template" :show="showPaymentModal">
            <Dialog as="div" class="relative z-10" @close="showPaymentModal = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
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
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-xl">
                                <div class="bg-white p-5 sm:p-8 text-center">
                                    <span class="text-slate-600 py-2 block text-md font-medium border-b">
                                        Available Payment Gateways
                                    </span>
                                    <div class="mt-3 flex flex-wrap gap-4 items-center justify-center">

                                        <label v-if="againOrder"
                                            class="flex items-center gap-4 border relative has-[:checked]:border-primary has-[:checked]:shadow-lg p-2 rounded-md border-slate-200 cursor-pointer grow max-w-40"
                                            title="Cash on delivery">
                                            <input v-model="paymentGateway" id="cash" name="paymentGateway" type="radio"
                                                class="sr-only" value="cash" />
                                            <div class="flex items-center">
                                                <img :src="'/assets/icons/money-2.svg'" alt=""
                                                    class="w-12 h-16 object-contain">
                                                <span
                                                    class="text-slate-600 text-xl overflow-hidden whitespace-nowrap text-ellipsis">
                                                    Cash On Delivery </span>
                                            </div>
                                        </label>

                                        <label v-for="gateway in master.paymentGateways" :key="gateway.id"
                                            :for="gateway.name"
                                            class="flex items-center gap-4 border relative has-[:checked]:border-primary has-[:checked]:shadow-lg p-2 rounded-md border-slate-200 cursor-pointer grow max-w-40">
                                            <input v-model="paymentGateway" :id="gateway.name" name="paymentGateway"
                                                type="radio" class="sr-only" :value="gateway.name" />
                                            <div class="">
                                                <img :src="gateway.logo" alt="" class="w-32 h-16 object-contain">
                                            </div>
                                        </label>
                                    </div>
                                    <div class="mt-5 w-full grow flex justify-end border-t pt-2">
                                        <button v-if="props.makePayment"
                                            class="px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium"
                                            @click="paymentProcessing">
                                            Confirm Payment
                                        </button>

                                        <button v-if="props.againOrder"
                                            class="px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium"
                                            @click="orderProcessing">
                                            Confirm Order
                                        </button>
                                    </div>

                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>
    </div>
</template>

<script setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { ref, watch } from 'vue';
import ToastSuccessMessage from './ToastSuccessMessage.vue';

import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useAuth } from '../stores/AuthStore';

const toast = useToast();
const router = useRouter();
const authStore = useAuth();

import axios from 'axios';
import { useMaster } from '../stores/MasterStore';
const master = useMaster();

const props = defineProps({
    order: Object,
    againOrder: Boolean,
    makePayment: Boolean
})

const showPaymentModal = ref(false);

const paymentGateway = ref(null);

const emit = defineEmits(['update:makePayment', 'update:orderAgain', 'update:paymentSuccess']);

watch(() => props.makePayment, () => {
    if (props.makePayment === true) {
        showPaymentModal.value = true;
    }
});

watch(() => props.againOrder, () => {
    if (props.againOrder === true) {
        showPaymentModal.value = true;
    }
});

watch(() => (showPaymentModal.value), () => {
    if (showPaymentModal.value === false) {
        emit('update:makePayment', false);
        emit('update:orderAgain', false);
    }
});

const paymentProcessing = () => {

    if (paymentGateway.value == null) {
        toast.error("Please select payment method", {
            position: "bottom-left",
        });
        return;
    }

    axios.get('/order-payment/' + props.order.id + '/' + paymentGateway.value, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        if (response.data.data.order_payment_url) {
            emit('update:makePayment', false);
            showPaymentModal.value = false;
            openPaymentPopupWindow(response.data.data.order_payment_url);
        }
    })

}

const againOrderContent = {
    component: ToastSuccessMessage,
    props: {
        title: 'Order Placed',
        message: 'Your order has been placed successfully.',
    },
};

const orderProcessing = () => {
    if (paymentGateway.value == null) {
        toast.error("Please select payment method", {
            position: "bottom-left",
        });
        return;
    }
    axios.post('/place-order/again', {
        order_id: props.order.id,
    }, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        emit('update:orderAgain', false);
        emit('update:makePayment', false);
        showPaymentModal.value = false;
        toast(againOrderContent, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "bottom-left",
            toastClassName: "vue-toastification-alert",
            timeout: 2000,
        });

        const paymentUrl = response.data.data.order_payment_url;

        if (paymentUrl) {
            openPaymentPopupWindow(paymentUrl);
        }
    })

}

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Payment Success!',
        message: 'Your payment has been successfully completed.',
    },
};

const openPaymentPopupWindow = (url) => {
    let winWidth = 700;
    let winHeight = 700;
    let left = (screen.width / 2) - (winWidth / 2);
    let top = (screen.height / 2) - (winHeight / 2);

    let options = "popup,resizable,height=" + winHeight + ",width=" + winWidth + ",top=" + top + ",left=" + left;

    let win = window.open(url, null, options);

    win.title = "Payment Window Screen - Make Payment";

    win.onload = () => {
        win.title = "Payment Window Screen - Make Payment";
        if (win.closed) {
            console.log('close window');
        }
    };

    win.focus();

    var intervalID = setInterval(trackURLChanges, 1000);
    function trackURLChanges() {
        const pathname = win.location.pathname;

        var currentPath = pathname.replace(/\/order\/\d+/, "");

        if (currentPath == '/payment/cancel') {
            win.close();
            clearInterval(intervalID);
            toast.error('Payment Cancelled', {
                position: "bottom-left",
            });
        }

        if (currentPath == '/payment/success') {
            win.close();
            clearInterval(intervalID);
            emit('update:paymentSuccess', true);
            toast(content, {
                type: "default",
                hideProgressBar: true,
                icon: false,
                position: "bottom-left",
                toastClassName: "vue-toastification-alert",
                timeout: 2000,
            });
        }

        if (win.closed || !win.location) {
            clearInterval(intervalID);
            console.log('window dismissed');
            win.close();
        }
    }

    // payment close after 2 minutes
    setTimeout(() => {
        clearInterval(intervalID);
        win.close();
    }, 120000);

};


</script>

<style lang="scss" scoped></style>
