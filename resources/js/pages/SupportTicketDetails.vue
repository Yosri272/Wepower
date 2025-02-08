<template>
    <div class="h-full flex flex-col">
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2">
            <UserIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/support-tickets" class="leading-normal hover:text-primary">
                Support Ticket
            </router-link>
            <span class="leading-normal">/ Support Ticket Details</span>
        </div>

        <!-- Header -->
        <AuthPageheader title="Support Ticket Details" />

        <div class="px-2 pt-2 md:px-4 md:pt-4 lg:px-6 lg:pt-6 md:pr-8 lg:pr-16 h-full">

            <div
                class="p-3 md:p-4 xl:p-6 bg-white rounded-xl md:rounded-2xl grid grid-cols-1 lg:grid-cols-2 gap-3 md:gap-4 h-full">

                <!-- column 1 -->
                <SupportTicketDetails :supportTicket="supportTicket" :highlightedMessages="highlightedMessages" />

                <!-- column 2 -->
                <div class="">
                    <SupportTicketMessages :messages="messages" :supportTicket="supportTicket"
                        @messageSubmited="fetchSupportTicket()" />
                </div>
            </div>

        </div>

    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { UserIcon } from '@heroicons/vue/24/outline';
import AuthPageheader from '../components/AuthPageheader.vue';
import { useRoute } from 'vue-router';
import { useAuth } from "../stores/AuthStore";
import { useMaster } from '../stores/MasterStore';
import SupportTicketMessages from '../components/SupportTicketMessages.vue';
import SupportTicketDetails from '../components/SupportTicketDetails.vue';
import Pusher from 'pusher-js';

const authStore = useAuth();
const route = useRoute();

const masterStore = useMaster();

const supportTicket = ref({});
const messages = ref([]);

const highlightedMessages = ref([]);

onMounted(() => {
    fetchSupportTicket();

    const pusher = new Pusher(masterStore.pusher_app_key, {
        cluster: masterStore.pusher_app_cluster,
        encrypted: true,
    });

    const channel = pusher.subscribe('support-ticket-message-channel');

    channel.bind('support-ticket-message-event', function (data) {
        var ticketNumber = data.ticket_number;
        if (ticketNumber == route.params.ticketNumber) {
            fetchSupportTicket();
        }
    });
});

const fetchSupportTicket = () => {
    axios.get('support-ticket/show', {
        params: {
            ticket_number: route.params.ticketNumber
        },
        headers: {
            Authorization: authStore.token
        }
    }).then((response) => {
        supportTicket.value = response.data.data.support_ticket;
        highlightedMessages.value = response.data.data.highlighted_messages;
        messages.value = response.data.data.support_ticket.messages;
    })
};

</script>
