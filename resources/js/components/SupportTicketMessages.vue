<template>
    <div class="message_wrapper h-100">

        <div class="messages" ref="messagesBox" :class="props.supportTicket?.status == 'pending' || props.supportTicket?.status == 'confirm' ? 'runningConversation' : ''">
            <div v-for="message in messages" :key="message.id" class="message_item"
                :class="(authStore.user.id == message.sender_id) ? 'sender' : 'receiver'">
                <div class="message_item_content">

                    <div v-if="authStore.user.id != message.sender_id" class="message_item_avatar shrink-0">
                        <img :src="message.profile_photo" alt="avatar" loading="lazy" />
                    </div>
                    <div class="message_item_text" v-html="makeLinksClickable(message.message)">
                    </div>

                    <div v-if="authStore.user.id == message.sender_id" class="message_item_avatar shrink-0">
                        <img :src="message.profile_photo" alt="avatar" loading="lazy" />
                    </div>
                </div>
                <div class="message_date">
                    {{ message.created_at }}
                </div>
            </div>

        </div>

        <div v-if="props.supportTicket?.status == 'pending' || props.supportTicket?.status == 'confirm'"
            class="reply-form mt-2 mx-3 mb-3">
            <form @submit.prevent="sendMessage">
                <input type="text" placeholder="Reply..."
                    class="w-full p-3 outline-none focus:ring-1 focus:ring-primary" required v-model="message"
                    :disabled="!enabledSendButton">
                <button v-if="enabledSendButton" class="send-btn bg-primary btn" type="submit">
                    <PaperAirplaneIcon v-if="!loading" class="w-5 h-5 -rotate-45" />
                    <svg v-else class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from "vue";
import { useAuth } from "../stores/AuthStore";
import { PaperAirplaneIcon } from "@heroicons/vue/24/solid";
import { useToast } from "vue-toastification";

const toast = useToast();

const authStore = useAuth();

const messagesBox = ref(null);

const props = defineProps({
    messages: Array,
    supportTicket: Object
});

const emits = defineEmits(['messageSubmited']);

const message = ref('');
const loading = ref(false);

const enabledSendButton = ref(false);

watch(() => props.supportTicket, () => {
    enabledSendButton.value = props.supportTicket?.user_chat ? true : false;
}, { deep: true });

watch(() => props.messages, () => {
    nextTick(scrollBottom);
}, { deep: true });

onMounted(() => {
    nextTick(scrollBottom);
});

const sendMessage = () => {
    if (message.value) {
        loading.value = true;
        axios.post('/support-ticket-message', {
            message: message.value,
            ticket_number: props.supportTicket?.ticket_no
        }, {
            headers: {
                'Authorization': authStore.token
            }
        }).then(() => {
            message.value = '';
            emits('messageSubmited', true);
            setTimeout(() => {
                loading.value = false;
            });
        }).catch((error) => {
            loading.value = false;
            toast.error(error.response.data.message, {
                position: "bottom-left",
            });
        })
    }
}

const scrollBottom = () => {
    if (messagesBox.value) {
        messagesBox.value.scrollTop = messagesBox.value.scrollHeight;
    }
};

function makeLinksClickable(message) {
    var urlRegex = /(https?:\/\/[^\s]+)/g;
    var messageWithLinks = message.replace(urlRegex, function (url) {
        return '<a href="' + url + '" target="_blank">' + url + '</a>';
    });
    return messageWithLinks;
}
</script>

<style scoped>
.message_wrapper {
    height: 100%;
    width: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    background: #f1f5f9;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.message_wrapper .messages {
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
    gap: 12px;
    padding: 12px;
    max-height: calc(100vh - 345px);
    min-height: 260px;
}

.message_wrapper .messages.runningConversation {
    max-height: calc(100vh - 410px);
}

.message_wrapper .messages .message_item .message_item_content {
    display: flex;
    gap: 12px;
    align-items: flex-start;
}

.message_wrapper .messages .message_item .message_item_avatar img {
    width: 40px;
    height: 40px;
    border-radius: 100%;
    object-fit: cover;
}

.message_item .message_item_text {
    border-radius: 12px;
    padding: 16px;
    font-size: 14px;
    line-height: 20px;
    color: #0f172a;
    position: relative;
}

.message_item .message_item_text .pinBtn {
    position: absolute;
    background: #f1f5f9;
    border: 1px solid #dee2e6;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    justify-content: center;
    align-items: center;
    top: -4px;
    box-shadow: 0 2px 2px 2px #eee;
    color: #0f172a;
    font-size: 15px;
    transition: all 0.5s ease-in-out;
    display: none;
}

.message_item .message_item_text:hover .pinBtn {
    display: flex;
}

.message_item.receiver .message_item_text .pinBtn {
    left: 0;
    transform: rotate(270deg);
}

.message_item.sender .message_item_text .pinBtn {
    right: 0;
}

.message_item.sender .message_item_text {
    background: #f8fafc;
    border: 1px solid #f1f5f9;
}

.message_item.receiver .message_item_text {
    background: #e2e8f0;
}

.message_date {
    color: #64748b;
    font-size: 12px;
    margin-top: 2px;
}

.message_item.sender .message_date {
    text-align: left !important;
}

.message_item.receiver .message_date {
    text-align: left !important;
    padding-left: 55px;
}

.hightighted .highlighted_item {
    background: #e2e8f0;
    border: 1px solid #f1f5f9;
    border-radius: 12px;
    padding: 12px;
}

.message_wrapper .reply-form {
    position: relative;
}

.message_wrapper .reply-form input {
    border-radius: 48px !important;
    padding-right: 50px !important;
    min-height: 46px !important;
    background: #fff;
    border: 1px solid transparent;
}

.message_wrapper .reply-form .send-btn {
    position: absolute;
    right: 6px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 2;
    cursor: pointer;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #fff;
    border: none;
    outline: none;
}

@media (min-width: 1024px) {
    .message_item.sender {
        max-width: 88%;
        margin-left: auto;
    }

    .message_item.receiver {
        max-width: 88%;
        margin-left: 0;
    }
}

@media (min-width: 1200px) {
    .message_item.sender {
        max-width: 80%;
        margin-left: auto;
    }

    .message_item.receiver {
        max-width: 80%;
        margin-left: 0;
    }
}

@media (max-width: 991px) {
    .message_item.sender {
        max-width: 80%;
        margin-left: auto;
    }

    .message_item.receiver {
        max-width: 80%;
        margin-left: 0;
    }
}

@media (max-width: 768px) {
    .message_item.sender {
        max-width: 95%;
        margin-left: auto;
    }

    .message_item.receiver {
        max-width: 95%;
        margin-left: 0;
    }

    .message_wrapper .messages {
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
.message_item .message_item_text a {
    color: #3b82f6 !important;
}
</style>
