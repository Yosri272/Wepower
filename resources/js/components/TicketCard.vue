<template>
    <div class="p-4 bg-white rounded-xl border border-slate-200 cursor-pointer" @click="goToDetails()">
        <!---- header -->
        <div class="flex justify-between items-center  gap-1.5 flex-wrap pb-3 border-b border-slate-200">
            <span class=" text-slate-500 text-sm font-normal">
                {{ supportTicket.created_at }}
            </span>
            <div class="flex gap-2 items-center">
                <span class="text-blue-500 text-sm">#{{ supportTicket.ticket_no }}</span>
                <span class="px-2 py-1 rounded-xl capitalize text-xs ticketStatus" :class="supportTicket.status">
                    {{ supportTicket.status }}
                </span>
            </div>
        </div>

        <!---- content -->

        <div class="flex justify-between items-center  gap-2 flex-wrap pt-3">
            <div class="flex flex-col gap-1">
                <div class="text-slate-500 text-sm font-normal">Order Number</div>
                <div class="text-slate-900 text-sm">
                    {{ supportTicket.order_number || 'N/A' }}
                </div>
            </div>

            <div class="flex flex-col gap-1 overflow-auto">
                <div class="text-slate-500 text-sm font-normal">Issue Type</div>
                <div class="text-slate-900 text-sm truncate max-w-60 lg:min-w-60">
                    {{ supportTicket.issue_type }}
                </div>
            </div>

            <div class="flex flex-col gap-1 overflow-hidden">
                <div class="text-slate-500 text-sm font-normal">Subject</div>
                <div class="text-slate-900 text-sm truncate max-w-80 lg:min-w-80">
                    {{ supportTicket.subject }}
                </div>
            </div>

            <div class="h-8 w-8 bg-slate-100 rounded-2xl flex justify-center items-center">
                <ChevronRightIcon class="w-5 h-5 text-slate-600" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { ChevronRightIcon } from '@heroicons/vue/24/outline';
import { useRouter } from 'vue-router';

const router = useRouter();

const props = defineProps({
    supportTicket: Object
})

const goToDetails = () => {
    router.push({ name: 'support-ticket-details', params: { ticketNumber: props.supportTicket.ticket_no } })
}

</script>

<style scoped>

.ticketStatus.pending {
    @apply text-yellow-600 bg-yellow-100;
}

.ticketStatus.completed {
    @apply text-lime-500 bg-green-100;
}

.ticketStatus.confirm {
    @apply text-primary-600 bg-primary-100;
}

.ticketStatus.cancel {
    @apply text-red-600 bg-red-100;
}

</style>
