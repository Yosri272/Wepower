<template>
    <div class="p-4 bg-white rounded-xl border border-slate-200 ticket-details">
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
        </div>

        <div class="flex flex-col gap-1 overflow-hidden mt-3">
            <div class="text-slate-500 text-sm font-normal">Subject</div>
            <div class="text-slate-900 text-sm truncate w-full">
                {{ supportTicket.subject }}
            </div>
        </div>

        <div class="text-black text-lg font-medium tracking-tight mt-4">
            Contact Info
        </div>

        <div class="flex justify-between items-center  gap-2 flex-wrap mt-3">
            <div class="flex flex-col gap-1">
                <div class="text-slate-500 text-sm font-normal">Email</div>
                <div class="text-slate-900 text-sm">
                    {{ supportTicket.email || 'N/A' }}
                </div>
            </div>

            <div class="flex flex-col gap-1">
                <div class="text-slate-500 text-sm font-normal">phone</div>
                <div class="text-slate-900 text-sm">
                    {{ supportTicket.phone || 'N/A' }}
                </div>
            </div>
        </div>

        <div class="text-black text-lg font-medium tracking-tight mt-4">
            File Attachment
        </div>

        <div class="mt-3 flex flex-wrap gap-2.5">
            <div v-for="(attachment, index) in supportTicket?.attachments" :key="index"
                class="w-12 h-12 border border-slate-200 rounded-lg">
                <a :href="attachment.src" target="_blank" :download="'support-ticket#' + supportTicket.ticket_no">
                    <img :src="attachment.type != 'pdf' ? attachment.src : '/assets/images/pdf.png'"
                        class="w-full h-full object-cover rounded-lg" loading="lazy" />
                </a>
            </div>
        </div>

        <div class="hightighted mt-6 flex flex-col gap-3">
            <div v-for="highlightedMessage in highlightedMessages" :key="highlightedMessage.id" class="highlighted_item"
                v-html="makeLinksClickable(highlightedMessage.message)"></div>
        </div>

    </div>
</template>

<script setup>

const props = defineProps({
    supportTicket: Object,
    highlightedMessages: Array
});

function makeLinksClickable(message) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    var messageWithLinks = message.replace(urlRegex, function (url) {
        return '<a href="' + url + '" target="_blank">' + url + '</a>';
    });
    return messageWithLinks;
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
.ticket-details {
    overflow-y: auto;
    max-height: calc(100vh - 340px);
    min-height: 335px;
}

.hightighted .highlighted_item {
    background: #e2e8f0;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 12px;
}

@media (max-width: 768px) {
    .ticket-details {
        overflow-y: auto;
        max-height: calc(100vh - 220px);
    }
}

/* width */
::-webkit-scrollbar {
    width: 6px;
    border-radius: 4px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #e2e8f0;
    border-radius: 4px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #94A3B8;
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #64748b;
}
</style>
<style>
.hightighted .highlighted_item a {
    @apply text-blue-500
}
</style>
