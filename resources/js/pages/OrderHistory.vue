<template>
    <div>

        <!-- Header -->
        <AuthPageheader title="Order History" />

        <!-- Order Status -->
        <div class="bg-white px-3 border-t border-slate-100 flex gap-4 md:gap-8 overflow-x-auto">

            <label class="statusLinkBtn" for="Pending">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="Pending" id="Pending" checked />
                Pending ({{ statusWiseOrders.pending }})
            </label>

            <label class="statusLinkBtn" for="Confirm">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="Confirm" id="Confirm" />
                Confirm ({{ statusWiseOrders.confirm }})
            </label>

            <label class="statusLinkBtn" for="Processing">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="Processing" id="Processing" />
                Processing ({{ statusWiseOrders.processing }})
            </label>

            <label class="statusLinkBtn" for="OnTheWay">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="On The Way" id="OnTheWay" />
                On The Way ({{ statusWiseOrders.on_the_way }})
            </label>

            <label class="statusLinkBtn" for="delivered">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="Delivered" id="delivered" />
                Delivered ({{ statusWiseOrders.delivered }})
            </label>

            <label class="statusLinkBtn" for="Cancelled">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="cancelled" id="Cancelled" />
                Cancelled ({{ statusWiseOrders.cancelled }})
            </label>

            <label class="statusLinkBtn" for="All">
                <input type="radio" v-model="orderStatus" name="status" class="sr-only" value="" id="All" />
                All ({{ statusWiseOrders.all }})
            </label>

        </div>

        <!-- Order History -->
        <div class="px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6">
            <div class="p-4 lg:p-6 bg-white rounded-xl flex flex-col gap-3">

                <!-- Order Item -->
                <div v-for="order in orders" :key="order.id">
                    <OrderHistoryOrderItem :order="order" />
                </div>

                <!-- Order list empty -->
                <div v-if="orders.length == 0">
                    <p>No Order Found</p>
                </div>

            </div>
        </div>

        <!-- paginations -->
        <div v-if="totalItems > perPage" class="px-2 md:px-4 lg:px-6 mt-4">
            <div class="bg-white p-3 rounded-xl flex justify-between items-center w-full  gap-4 flex-wrap">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    Showing {{ perPage * (currentPage - 1) + 1 }} to {{ perPage * (currentPage - 1) +
                    orders.length }} of {{ totalItems }} results
                </div>
                <div>
                    <vue-awesome-paginate :total-items="totalItems" :items-per-page="perPage" type="button"
                        :max-pages-shown="3" v-model="currentPage" :on-click="onClickHandler" />
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import AuthPageheader from '../components/AuthPageheader.vue';
import OrderHistoryOrderItem from '../components/OrderHistoryOrderItem.vue';

import { useAuth } from '../stores/AuthStore';
const authStore = useAuth();
const orderStatus = ref('Pending');

const orders = ref([]);

const totalItems = ref(20);
const currentPage = ref(1);
const perPage = ref(10);

const statusWiseOrders = ref({
    all: 0,
    pending: 0,
    confirm: 0,
    processing: 0,
    on_the_way: 0,
    delivered: 0,
    cancelled: 0
});

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchOrders();
};

watch(orderStatus, () => {
    currentPage.value = 1;
    fetchOrders();
});

onMounted(() => {
    fetchOrders();
});

const fetchOrders = async () => {
    axios.get('/orders', {
        params: {
            order_status: orderStatus.value,
            page: currentPage.value,
            per_page: perPage.value
        },
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        totalItems.value = response.data.data.total;
        orders.value = response.data.data.orders;
        statusWiseOrders.value = response.data.data.status_wise_orders;
    })
};

</script>
<style scoped>
    .statusLinkBtn{
        @apply py-4 border-b-2 relative has-[:checked]:text-primary text-base font-normal leading-normal has-[:checked]:border-primary cursor-pointer border-transparent shrink-0;
    }
</style>
