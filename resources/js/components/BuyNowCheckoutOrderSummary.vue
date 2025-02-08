<template>
    <div>
        <div class="p-6 bg-white rounded-2xl border border-slate-200">
            <div class="text-slate-950 text-xl font-medium leading-7">
                Order Summary
            </div>

            <!-- Subtotal -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-base font-normal leading-normal">
                    Subtotal
                </div>
                <div class="text-slate-950 text-base font-normal leading-normal">
                    {{ master.showCurrency(orderData.total_amount) }}
                </div>
            </div>

            <!-- Discount -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-red-500 text-base font-normal leading-normal">
                    Discount
                </div>
                <div class="text-slate-950 text-base font-normal leading-normal">
                    -{{ master.showCurrency(orderData.coupon_discount) }}
                </div>
            </div>

            <div class="w-full h-[0px] border-t border-dashed border-slate-400"></div>

            <!-- Subtotal After Discount -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-base font-normal leading-normal">
                    Subtotal After Discount
                </div>
                <div class="text-slate-950 text-base font-normal leading-normal">
                    {{ master.showCurrency((orderData.total_amount - orderData.coupon_discount).toFixed(2)) }}
                </div>
            </div>

            <!-- Shipping Charge -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-base font-normal leading-normal">
                    Shipping Charge
                </div>
                <div class="text-slate-950 text-base font-normal leading-normal">
                    {{ master.showCurrency(orderData.delivery_charge) }}
                </div>
            </div>

            <div class="w-full h-[0px] border border-slate-500"></div>

            <!-- Total Payable -->
            <div class="my-4 flex justify-between gap-4">
                <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                    Total Payable
                </div>
                <div class="text-slate-950 text-lg font-medium leading-normal tracking-tight">
                    {{ master.showCurrency(orderData.total_payable_amount) }}
                </div>
            </div>

            <!-- Have a coupon -->
            <div class="p-4 mt-6 bg-slate-100 rounded-xl">
                <div class="text-black text-base font-normal leading-normal">
                    Have a coupon?
                </div>

                <!-- Coupon Input -->
                <div class="relative mt-2">
                    <input type="text" v-model="coupon" class="formInputCoupon pr-14 p-3"
                        placeholder="Enter coupon code" :class="hasCoupon ? 'text-green-500 pl-10' : ''" />

                    <button v-if="!hasCoupon"
                        class="bg-slate-700 absolute top-1/2 -translate-y-1/2 right-1.5 h-10 w-10 rounded flex justify-center items-center"
                        @click="ApplyCoupon">
                        <ArrowRightIcon class="w-6 h-6 text-white" />
                    </button>

                    <button v-else
                        class="bg-slate-100 absolute top-1/2 -translate-y-1/2 right-1.5 h-10 w-10 rounded flex justify-center items-center"
                        @click="removeCoupon">
                        <TrashIcon class="w-6 h-6 text-red-500" />
                    </button>

                    <span class="absolute top-1/2 -translate-y-1/2 left-3">
                        <CheckCircleIcon class="w-6 h-6 text-green-500" v-if="hasCoupon" />
                    </span>
                </div>
            </div>
        </div>

        <button class="px-6 py-4 w-full mt-4 bg-primary rounded-[10px] text-white text-base font-medium"
            @click="processOrderConfirm">
            Place Order
        </button>

        <!-- End Order Confirm Dialog Modal -->
        <OrderConfirmModal />
    </div>
</template>

<script setup>
import { ArrowRightIcon, TrashIcon } from "@heroicons/vue/24/outline";
import { CheckCircleIcon } from "@heroicons/vue/24/solid";
import { onMounted, ref } from "vue";
import OrderConfirmModal from "../components/OrderConfirmModal.vue";
import ToastSuccessMessage from "../components/ToastSuccessMessage.vue";

import { useToast } from "vue-toastification";
import { useAuth } from "../stores/AuthStore";
import { useBaskerStore } from "../stores/BasketStore";
import { useMaster } from "../stores/MasterStore";

import { useRouter } from "vue-router";
const router = new useRouter();

const basketStore = useBaskerStore();
const master = useMaster();
const authStore = useAuth();

const toast = useToast();

const hasCoupon = ref(false);

const coupon = ref("");

const props = defineProps({
    note: String,
    paymentMethod: String,
});

const orderData = ref({
    total_amount: 0,
    coupon_discount: 0,
    total_payable_amount: 0,
    delivery_charge: 0
});

onMounted(() => {
    coupon.value = basketStore.coupon_code;
    fetchBuyNowSummary();
});

const fetchBuyNowSummary = () => {
    if (basketStore.buyNowProduct) {
        axios.post("/buy-now", { product_id: basketStore.buyNowProduct.id, coupon_code: coupon.value, quantity: 1 },
            {
                headers: {
                    Authorization: authStore.token,
                },
            }).then((response) => {
                orderData.value = response.data.data;
                hasCoupon.value = response.data.data.apply_coupon;

                if (hasCoupon.value && coupon.value.length > 0) {
                    toast.success(response.data.message, {
                        position: "bottom-left",
                    });
                    basketStore.coupon_code = coupon.value;
                } else if (!hasCoupon.value && coupon.value.length > 0) {
                    toast.error(response.data.message, {
                        position: "bottom-left",
                    });
                    basketStore.coupon_code = '';
                }
            })
            .catch((error) => {
                toast.error(error.response.data.message, {
                    position: "bottom-left",
                });
            });
    }
};

const ApplyCoupon = () => {
    if (coupon.value.length > 0) {
        fetchBuyNowSummary();
    }
};

const removeCoupon = () => {
    coupon.value = "";
    hasCoupon.value = false;
    basketStore.coupon_code = "";
    fetchBuyNowSummary();
};

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Order Placed',
        message: 'Your order has been placed successfully.',
    },
};

const processOrderConfirm = () => {
    if (!basketStore.address) {
        toast.error("Please select shipping address");
        return;
    }
    if (props.paymentMethod == null) {
        toast.error("Please select payment method", {
            position: "bottom-left",
        });
        return;
    }

    if (basketStore.buyNowProduct) {
        axios.post('/buy-now/place-order', {
            product_id: basketStore.buyNowProduct.id,
            quantity: 1,
            address_id: basketStore.address.id,
            payment_method: props.paymentMethod,
            coupon_code: coupon.value,
            note: props.note,
            size: basketStore.buyNowProduct.size ?? null,
            color: basketStore.buyNowProduct.color ?? null,
        }, {
            headers: {
                Authorization: authStore.token,
            }
        }).then((response) => {
            toast(content, {
                type: "default",
                hideProgressBar: true,
                icon: false,
                position: "bottom-left",
                toastClassName: "vue-toastification-alert",
                timeout: 2000,
            });
            orderData.value.coupon_discount = 0;
            orderData.value.total_payable_amount = 0;
            orderData.value.delivery_charge = 0;
            orderData.value.total_amount = 0;
            basketStore.buyNowProduct = null;
            basketStore.coupon_code = '';
            let paymentUrl = response.data.data.order_payment_url;

            if (paymentUrl != null) {
                openPaymentPopupWindow(paymentUrl);
                return;
            } else {
                basketStore.showOrderConfirmModal = true
            }
        }).catch(() => {})
    } else {
        toast.error("Please select at least one product", {
            position: "bottom-left",
        });
    }
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
    };

    win.focus();

    var intervalID = setInterval(trackURLChanges, 1000);

    function trackURLChanges() {
        try {
            const pathname = win.location.pathname;

            var currentPath = pathname.replace(/\/order\/\d+/, "");

            if (currentPath == '/payment/cancel') {
                win.close();
                clearInterval(intervalID);
                basketStore.orderPaymentCancelModal = true
                toast.error('Sorry! Payment Canceled', {
                    position: "bottom-left",
                });
                router.push({ name: 'home' });
                return
            }

            if (currentPath == '/payment/success') {
                win.close();
                clearInterval(intervalID);
                basketStore.showOrderConfirmModal = true
                return
            }

            if (win.closed || !win) {
                clearInterval(intervalID);
                console.log('window dismissed');
                win.close();
                basketStore.orderPaymentCancelModal = true
                router.push({ name: 'home' });
            }
        } catch (error) { }
    }

    // payment close after 3 minutes
    setTimeout(() => {
        clearInterval(intervalID);
        win.close();
    }, 180000);
};

</script>

<style scoped>
.formInputCoupon {
    @apply rounded-lg border border-slate-200 focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}
</style>
