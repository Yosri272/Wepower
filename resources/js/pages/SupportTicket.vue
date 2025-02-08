<template>
    <div>
        <!-- Header -->
        <div
            class="py-3 px-2 text-slate-800 text-lg md:text-2xl font-medium tracking-tight md:leading-loose bg-white flex justify-between gap-2 flex-wrap items-center md:pr-8 lg:pr-16">
            Support Ticket

            <button class="px-3 py-2.5 bg-primary text-white rounded-lg flex items-center text-sm font-normal gap-1" @click="showModal = true">
                <PlusCircleIcon class="w-5 h-5" />
                Create Ticket
            </button>
        </div>

        <!-- Order Status -->
        <div class="bg-white px-3 border-t border-slate-100 flex gap-4 md:gap-8 overflow-x-auto">

            <label class="statusLinkBtn" for="Pending">
                <input type="radio" v-model="status" name="status" class="sr-only" value="Pending" id="Pending"
                    checked />
                Running ({{ runningTicket }})
            </label>

            <label class="statusLinkBtn" for="Confirm">
                <input type="radio" v-model="status" name="status" class="sr-only" value="completed" id="Confirm" />
                Completed ({{ completedTicket }})
            </label>

            <label class="statusLinkBtn" for="Cancelled">
                <input type="radio" v-model="status" name="status" class="sr-only" value="cancel" id="Cancelled" />
                Cancel ({{ cancelledTicket }})
            </label>

        </div>

        <div class="px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6">

            <div class="p-3 md:p-4 xl:p-6 bg-white rounded-xl md:rounded-2xl flex flex-col gap-3 md:gap-4">
                <TicketCard v-for="ticket in supportTickets" :key="ticket.id" :supportTicket="ticket" />

                <!-- Ticket list empty -->
                <div v-if="supportTickets.length == 0">
                    <p>No Ticket Found</p>
                </div>
            </div>

            <!-- Pagination -->
            <div v-if="totalItems > perPage" class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    Showing {{ perPage * (currentPage - 1) + 1 }} to {{ perPage * (currentPage - 1) +
                    supportTickets.length }} of
                    {{ totalItems }} results
                </div>
                <div>
                    <vue-awesome-paginate :total-items="totalItems" :items-per-page="perPage" type="button"
                        :max-pages-shown="5" v-model="currentPage" :on-click="onClickHandler" />
                </div>
            </div>

        </div>

        <CreateTicketModal  :showModal="showModal" @update:showModal="showModal = $event" @ticketCreated="fetchSupportTickets()"/>

    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useAuth } from "../stores/AuthStore";
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import TicketCard from '../components/TicketCard.vue';
import CreateTicketModal from '../components/CreateTicketModal.vue';

const authStore = useAuth();

const showModal = ref(false);

const status = ref('running');

const runningTicket = ref(0);
const completedTicket = ref(0);
const cancelledTicket = ref(0);

const supportTickets = ref([]);

const totalItems = ref(10);
const perPage = ref(10);
const currentPage = ref(1);

onMounted(() => {
    fetchSupportTickets();
    window.scrollTo(0, 0);
})

watch(() => status.value, () => {
    currentPage.value = 1;
    fetchSupportTickets();
})

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchSupportTickets();
};

const fetchSupportTickets = () => {
    axios.get('/support-tickets', {
        params: {
            page: currentPage.value,
            per_page: perPage.value,
            status: status.value
        },
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        totalItems.value = response.data.data.total;
        runningTicket.value = response.data.data.running;
        completedTicket.value = response.data.data.completed;
        cancelledTicket.value = response.data.data.cancel;
        supportTickets.value = response.data.data.support_tickets;
    })
}

</script>

<style scoped>
.statusLinkBtn {
    @apply py-4 border-b-2 relative has-[:checked]:text-primary text-base font-normal leading-normal has-[:checked]:border-primary cursor-pointer border-transparent shrink-0;
}
</style>
