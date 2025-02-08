<template>
    <div>
        <TransitionRoot as="template" :show="showModal">
            <Dialog as="div" class="relative z-10" @close="showModal = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-50 transition-opacity" />
                </TransitionChild>
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all my-8 md:my-0 w-full md:max-w-3xl">
                                <div class="bg-white p-5 sm:p-8 relative">
                                    <!-- close button -->
                                    <div class="w-9 h-9 bg-slate-100 rounded-[32px] absolute top-4 right-4 flex justify-center items-center cursor-pointer"
                                        @click="showModal = false">
                                        <XMarkIcon class="w-6 h-6 text-slate-600" />
                                    </div>
                                    <!-- end close button -->

                                    <div class="text-slate-950 text-2xl font-medium">
                                        Create Support Ticket
                                    </div>

                                    <form @submit.prevent="ticketFormSubmit()" class="mt-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                            <div>
                                                <label for="name" class="form-label mb-1">
                                                    Order Number
                                                </label>
                                                <select v-model="formData.order_number"
                                                    class="form-input bg-transparent"
                                                    :class="(errors && errors?.order_number) ? 'border-red-500' : 'border-slate-200'">
                                                    <option value="" selected>
                                                        Select order number
                                                    </option>
                                                    <option v-for="order in orders" :key="order.id"
                                                        :value="order.order_code">
                                                        {{ order.order_code }}
                                                    </option>
                                                </select>
                                                <span v-if="errors && errors?.order_number"
                                                    class="text-red-500 text-sm">{{ errors?.order_number[0] }}</span>
                                            </div>
                                            <div>
                                                <label for="name" class="form-label mb-1">
                                                    Issue Type<small class="text-red-500">*</small>
                                                </label>
                                                <select v-model="formData.issue_type" class="form-input bg-transparent"
                                                    :class="(errors && errors?.issue_type) ? 'border-red-500' : 'border-slate-200'">
                                                    <option value="" selected>
                                                        Select issue type
                                                    </option>
                                                    <option v-for="issueType in issueTypes" :key="issueType.id"
                                                        :value="issueType.name">
                                                        {{ issueType.name }}
                                                    </option>
                                                </select>
                                                <span v-if="errors && errors?.issue_type"
                                                    class="text-red-500 text-sm">{{ errors?.issue_type[0] }}</span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 mt-6">
                                            <div>
                                                <label for="Subject" class="form-label mb-1"> Subject</label>
                                                <input type="text" id="Subject" placeholder="Enter subject"
                                                    class="form-input" v-model="formData.subject"
                                                    :class="errors && errors?.subject ? 'border-red-500' : 'border-slate-200'">
                                                <span v-if="errors && errors?.subject" class="text-red-500 text-sm">{{
            errors?.subject[0] }}</span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                                            <div>
                                                <label class="form-label mb-1">
                                                    Message<small class="text-red-500">*</small>
                                                </label>
                                                <textarea v-model="formData.message" class="form-input" rows="4"
                                                    :class="errors && errors?.message ? 'border-red-500' : 'border-slate-200'"
                                                    placeholder="Write your message ..."></textarea>
                                                <span v-if="errors && errors?.message" class="text-red-500 text-sm">
                                                    {{ errors?.message[0] }}
                                                </span>
                                            </div>

                                            <div>
                                                <label class="form-label mb-1">
                                                    File Attachment (jpg, jpeg, png, pdf)
                                                </label>

                                                <label for="attachment"
                                                    class="cursor-pointer px-4 py-3 rounded-lg border border-dashed border-primary-400 flex items-center gap-2 text-primary text-xs">
                                                    <input type="file" id="attachment" class="hidden"
                                                        accept="image/jpeg, image/png, image/jpg, application/pdf"
                                                        @change="onFileChange">
                                                    <CloudArrowUpIcon class="w-6 h-6" />
                                                    Click to upload or, drag and drop here
                                                </label>

                                                <div class="mt-3 flex flex-wrap gap-2.5">
                                                    <div v-for="(attachment, index) in attachments" :key="index"
                                                        class="w-12 h-12 relative">
                                                        <img :src="attachment.type != 'application/pdf' ? attachment.url : '/assets/images/pdf.png'"
                                                            class="w-full h-full object-cover rounded-lg"
                                                            loading="lazy" />

                                                        <button type="button"
                                                            class="absolute -top-1 -right-1 bg-red-500 rounded-full w-5 h-5 text-white flex justify-center items-center border-2 border-white"
                                                            @click="removeAttachment(index)">
                                                            <XMarkIcon class="w-4 h-4" />
                                                        </button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="text-slate-950 text-lg font-bold mt-5 flex justify-between gap-2 flex-wrap">
                                            Contact Info
                                            <div class="flex justify-start items-center gap-3">
                                                <div class="flex gap-1.5 items-center">
                                                    <input type="checkbox" id="email" class="form-checkbox" checked>
                                                    <label class="form-label" for="email">Email Address</label>
                                                </div>

                                                <div class="flex gap-1.5 items-center">
                                                    <input type="checkbox" id="phone" class="form-checkbox" v-model="phone">
                                                    <label class="form-label" for="phone">Phone Number</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-3">
                                            <div>
                                                <div class="relative rounded-md">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-gray-500 sm:text-sm">
                                                            <EnvelopeIcon class="w-5 h-5" />
                                                        </span>
                                                    </div>
                                                    <input type="email" placeholder="Enter Email" class="form-input" style="padding-left: 2.5rem;"
                                                        v-model="formData.email"
                                                        :class="errors && errors?.email ? 'border-red-500' : 'border-slate-200'">
                                                </div>
                                                <span v-if="errors && errors?.email" class="text-red-500 text-sm">{{
                                                    errors?.email[0] }}</span>
                                            </div>

                                            <div>
                                                <div class="relative rounded-md">
                                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                        <span class="text-gray-500 sm:text-sm">
                                                            <PhoneIcon class="w-5 h-5" />
                                                        </span>
                                                    </div>
                                                    <input type="phone" placeholder="Enter phone number" class="form-input" style="padding-left: 2.5rem;"
                                                        v-model="formData.phone"
                                                        :class="errors && errors?.phone ? 'border-red-500' : 'border-slate-200'">
                                                </div>
                                                <span v-if="errors && errors?.phone" class="text-red-500 text-sm">{{
                                                    errors?.phone[0] }}</span>
                                            </div>
                                        </div>
                                        <div class="mt-6">
                                            <button class="py-4 px-5 bg-primary text-white w-full rounded-xl" :disabled="loading">
                                                Submit Ticket
                                            </button>
                                        </div>
                                    </form>
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
import axios from 'axios';
import { ref, watch, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { XMarkIcon, EnvelopeIcon, PhoneIcon } from '@heroicons/vue/24/solid';
import { CloudArrowUpIcon } from '@heroicons/vue/24/outline';

import { useAuth } from '../stores/AuthStore';

const showModal = ref(false);
const phone = ref(false);

const emits = defineEmits(['update:showModal', 'ticketCreated']);
const props = defineProps({
    showModal: Boolean
});

watch(() => props.showModal, (value) => {
    showModal.value = value
})

watch(() => showModal.value, (value) => {
    emits('update:showModal', value)
})

import { useToast } from 'vue-toastification';

const toast = useToast();
const authStore = useAuth();

const issueTypes = ref([]);

const formData = ref({
    issue_type: '',
    subject: '',
    message: '',
    email: '',
    phone: '',
    order_number: '',
});

const attachments = ref([]);

const errors = ref({});

const orders = ref([]);

onMounted(() => {
    fetchIssueTypes();
    fetchOrders();
})

const onFileChange = (event) => {
    attachments.value.push({
        url: URL.createObjectURL(event.target.files[0]),
        file: event.target.files[0],
        type: event.target.files[0].type
    });
}

const removeAttachment = (index) => {
    attachments.value.splice(index, 1);
}

const loading = ref(false);

const ticketFormSubmit = () => {
    loading.value = true;
    const processData = new FormData();

    processData.append('issue_type', formData.value.issue_type);
    processData.append('subject', formData.value.subject);
    processData.append('message', formData.value.message);
    processData.append('order_number', formData.value.order_number ? formData.value.order_number : '');
    processData.append('email', formData.value.email ? formData.value.email : '');
    processData.append('phone', formData.value.phone ? formData.value.phone : '');
    for (let i = 0; i < attachments.value.length; i++) {
        processData.append('attachments['+i+']', attachments.value[i].file);
    }

    axios.post('/support-ticket', processData, {
        headers: {
            'Authorization': authStore.token
        }
    }).then((response) => {
        loading.value = false
        showModal.value = false
        formData.value = {};
        attachments.value = [];
        toast.success(response.data.message, {
            position: "bottom-left",
        });
        emits('ticketCreated', true);
    }).catch((error) => {
        loading.value = false
        errors.value = error.response.data.errors
    })
}

const fetchIssueTypes = () => {
    axios.get('/ticket-issue-types', {
        headers: {
            'Authorization': authStore.token
        }
    }).then((response) => {
        issueTypes.value = response.data.data.issue_types
    })
}

const fetchOrders = async () => {
    axios.get('/orders', {
        headers: {
            Authorization: authStore.token,
        }
    }).then((response) => {
        orders.value = response.data.data.orders;
    })
};

</script>

<style scoped>
.form-label {
    @apply text-slate-700 text-base font-normal block leading-normal;
}

.form-input {
    @apply p-3 rounded-lg border focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}

.form-checkbox{
    @apply rounded w-[18px] h-[18px];
}
</style>
