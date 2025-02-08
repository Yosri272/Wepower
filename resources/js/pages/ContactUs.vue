<template>
    <div class="main-container">

        <div class="max-w-7xl mx-auto py-6 md:py-8 lg:py-12">

            <div class="text-slate-950 text-lg sm:text-xl lg:text-3xl font-bold mb-2">
                Can't find the answer you are looking for?
            </div>

            <div class="text-slate-950 text-base sm:text-lg font-normal tracking-tight mb-7">
                Our friendly assistant is here to assist you 24 hours a day!
            </div>
            <form @submit.prevent="contactFormSubmit()">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2 order-2 md:order-1">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="form-label mb-2">
                                    Full Name <small class="text-red-500">*</small>
                                </label>
                                <input type="text" placeholder="Enter full name" class="form-input"
                                    v-model="formData.name"
                                    :class="errors && errors?.name ? 'border-red-500' : 'border-slate-200'" />
                                <span v-if="errors && errors?.name" class="text-red-500 text-sm">{{ errors?.name[0]
                                    }}</span>
                            </div>

                            <div>
                                <label class="form-label mb-2">
                                    Phone Number <small class="text-red-500">*</small>
                                </label>
                                <input type="text" placeholder="Enter phone number" class="form-input"
                                    v-model="formData.phone"
                                    :class="errors && errors?.phone ? 'border-red-500' : 'border-slate-200'" />
                                <span v-if="errors && errors?.phone" class="text-red-500 text-sm">{{ errors?.phone[0]
                                    }}</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="form-label mb-2">
                                Subject <small class="text-red-500">*</small>
                            </label>
                            <input type="text" placeholder="Enter subject line" class="form-input"
                                v-model="formData.subject"
                                :class="errors && errors?.subject ? 'border-red-500' : 'border-slate-200'" />
                            <span v-if="errors && errors?.subject" class="text-red-500 text-sm">{{ errors?.subject[0]
                                }}</span>
                        </div>

                        <div class="mt-4">
                            <label class="form-label mb-2">
                                Message <small class="text-red-500">*</small>
                            </label>
                            <textarea rows="4" placeholder="Write your message..." class="form-input"
                                v-model="formData.message"
                                :class="errors && errors?.message ? 'border-red-500' : 'border-slate-200'"></textarea>
                            <span v-if="errors && errors?.message" class="text-red-500 text-sm">
                                {{ errors?.message[0] }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <button type="submit"
                                class="bg-primary text-white px-6 py-4 rounded-lg w-[148px]">Send</button>
                        </div>

                    </div>

                    <div class="col-span-1 items-center order-1 lg:order-2">
                        <div class="lg:max-w-[450px] lg:max-h-[450px] rounded-2xl overflow-hidden">
                            <img :src="'assets/images/contact-us.png'" alt="image" class="w-full h-full object-cover">
                        </div>
                    </div>

                </div>
            </form>
        </div>

    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useToast } from "vue-toastification";
import { useAuth } from "../stores/AuthStore";

const toast = useToast();
const authStore = useAuth();

const formData = ref({
    name: '',
    phone: '',
    subject: '',
    message: '',
});

const errors = ref({});

const contactFormSubmit = () => {
    validateForm();

    if (formData.value.name && formData.value.phone && formData.value.subject && formData.value.message) {
        axios.post('/support', formData.value, {
            headers: {
                'Authorization': authStore.token
            }
        }).then((response) => {
            toast.success(response.data.message,{
                position: "bottom-left",
            });
            formData.value.name = '';
            formData.value.phone = '';
            formData.value.subject = '';
            formData.value.message = '';
            errors.value = {};
        }).catch((error) => {
            errors.value = error.response.data.errors
            toast.error(error.response.data.message, {
                position: "bottom-left",
            });
        })
    }
}

const validateForm = () => {
    if (!formData.value.name) {
        errors.value.name = ['Name is required.'];
    }
    if (!formData.value.phone) {
        errors.value.phone = ['Phone number is required.'];
    }
    if (!formData.value.subject) {
        errors.value.subject = ['Subject is required.'];
    }
    if (!formData.value.message) {
        errors.value.message = ['Message is required.'];
    }

}

</script>

<style scoped>
.form-label {
    @apply text-slate-700 text-base font-normal leading-normal block;
}

.form-input {
    @apply p-3 rounded-lg border focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}
</style>
