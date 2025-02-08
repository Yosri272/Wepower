<template>
    <div>
        <div class="bg-white rounded-lg md:rounded-2xl border border-slate-100 p-3 md:p-4 xl:p-6">

            <div class="text-slate-950 text-lg md:text-xl font-medium">
                Order Summary
            </div>

            <!-- Subtotal -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    Items
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ order.quantity }}
                </div>
            </div>

            <!-- Subtotal -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    Subtotal
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ master.showCurrency(order?.total_amount) }}
                </div>
            </div>

            <!-- Discount -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-red-500 text-sm sm:text-base font-normal">
                    Discount
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    -{{ master.showCurrency(order?.coupon_discount) }}
                </div>
            </div>

            <!-- Shipping Charge -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    Shipping Charge
                </div>
                <div class="text-slate-950 text-sm sm:text-base font-normal">
                    {{ master.showCurrency(order?.delivery_charge) }}
                </div>
            </div>

            <div class="w-full h-[0px] border border-slate-400"></div>

            <!-- Total Payable -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-sm sm:text-base tracking-tight">
                    Total Amount
                </div>
                <div class="text-slate-950 text-sm sm:text-base tracking-tight">
                    {{ master.showCurrency(order?.payable_amount) }}
                </div>
            </div>

            <div
                class="bg-slate-50 rounded md:rounded-lg border border-slate-100 p-2 lg:p-4 mt-2 flex flex-col gap-1 lg:gap-2">
                <div class="text-slate-500 text-xs font-normal">PAYMENT METHOD</div>
                <div
                    class="text-slate-950 text-sm md:text-lg font-medium leading-tight flex items-center justify-between flex-wrap">
                    {{ order.payment_method }}

                    <span v-if="order.payment_status === 'Pending'"
                        class="text-red-500 text-sm font-normal bg-red-100 p-1 rounded-md flex gap-1 items-center">
                        <ExclamationTriangleIcon class="w-5 h-5" />
                        {{ order.payment_status }}
                    </span>

                    <span v-if="order.payment_status === 'Paid'"
                        class="bg-emerald-500 text-sm font-normal text-white p-1 rounded-md flex items-center">
                        <CheckCircleIcon class="w-5 h-5" />
                        {{ order.payment_status }}
                    </span>
                </div>
            </div>

            <button class="p-2 lg:p-4 mt-2 flex gap-1 lg:gap-2 items-center justify-center w-full"
                @click="downloadInvoice">
                <img :src="'/assets/icons/cloud.svg'" alt="download" class="w-4 h-4 md:w-6 md:h-6" />
                <span class="text-center text-primary text-xs md:text-sm font-normal underline leading-tight">
                    Download Invoice
                </span>
            </button>
        </div>

        <div class="flex items-center gap-2">
            <button v-if="order.order_status === 'Pending'"
                class="mt-2 px-4 py-3 md:py-4 bg-white rounded-lg text-slate-700 text-base font-medium grow"
                @click="cancelModal = true">
                Cancel Order
            </button>

            <button v-if="props.order?.payment_status == 'Pending' && props.order?.payment_method == 'Online Payment'"
                class=" mt-2 px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium grow"
                @click="makePaymentModal = true">
                Make Payment
            </button>
        </div>

        <button v-if="order.order_status === 'Delivered'"
            class="w-full mt-2 px-4 py-3 md:py-4 bg-primary rounded-lg text-white text-base font-medium"
            @click="againOrder = true">
            Order Again
        </button>

        <!-- Cancel modal -->
        <TransitionRoot as="template" :show="cancelModal">
            <Dialog as="div" class="relative z-10" @close="cancelModal = false">
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
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                <div class="bg-white p-5 sm:p-8 text-center">

                                    <div
                                        class="bg-red-500 w-14 h-14 md:w-20 md:h-20 rounded-full mx-auto flex justify-center items-center">
                                        <XMarkIcon class="w-8 h-8 md:w-12 md:h-12 text-white" />
                                    </div>

                                    <div class="mt-3 text-center text-gray-900 text-2xl md:text-3xl font-bold">Cancel
                                        the Order!</div>

                                    <div class="mt-4 text-center text-slate-700 text-base md:text-xl font-normal">
                                        Are you sure want to cancel this order?
                                    </div>

                                    <div class="flex justify-between items-center gap-4 mt-8">
                                        <button
                                            class="text-slate-800 grow text-base font-medium px-4 py-3 md:px-6 md:py-4 rounded-lg md:rounded-[10px] border border-slate-300"
                                            @click="cancelModal = false">Cancel</button>

                                        <button
                                            class="text-white grow bg-red-500 text-base font-medium px-4 py-3 md:px-6 md:py-4 rounded-lg md:rounded-[10px]"
                                            @click="cancelOrder">Yes</button>
                                    </div>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <OrderDetailsPaymentAndAgainOrder :order="order" :makePayment="makePaymentModal" :againOrder="againOrder"
            @update:makePayment="makePaymentModal = false" @update:orderAgain="againOrder = false"
            @update:paymentSuccess="orderPaymentSuccess" />

    </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, ExclamationTriangleIcon, CheckCircleIcon } from '@heroicons/vue/24/outline';
import OrderDetailsPaymentAndAgainOrder from './OrderDetailsPaymentAndAgainOrder.vue';
import { useToast } from 'vue-toastification';
import { useMaster } from '../stores/MasterStore';
import { useAuth } from '../stores/AuthStore';

const master = useMaster();
const toast = useToast();
const authStore = useAuth();

const cancelModal = ref(false);
const againOrder = ref(false);

const makePaymentModal = ref(false);

const showPaymentButton = ref(false);

const emit = defineEmits(['update:paymentSuccess']);

const orderPaymentSuccess = () => {
    emit('update:paymentSuccess', true);
}

const props = defineProps({
    order: Object
});

const cancelOrder = () => {
    axios.post('/orders/cancel', {
        order_id: props.order.id
    }, {
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        toast.success(response.data.message, {
            position: "bottom-left",
        });
        authStore.orderCancel = true;
    })
    cancelModal.value = false;
}

const downloadInvoice = () => {
    if (props.order.invoice_url) {
        window.open(props.order.invoice_url, '_blank');
    } else {
        toast.error('Invoice not found', {
            position: "bottom-left",
        });
    }
}

</script>
