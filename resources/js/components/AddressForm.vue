<template>
    <div class="p-6 bg-white rounded-2xl border border-slate-200 mt-3">
        <form @submit.prevent="addressFormSubmit()">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="form-label mb-2"> Name <small>*</small></label>
                    <input type="text" id="name" v-model="formData.name" placeholder="Enter name" class="form-input"
                        :class="(errors && errors?.name) ? 'border-red-500' : 'border-slate-200'">
                    <span v-if="errors && errors?.name" class="text-red-500 text-sm">{{ errors?.name[0] }}</span>
                </div>
                <div>
                    <label for="Phone" class="form-label mb-2"> Phone <small>*</small></label>
                    <input type="text" id="Phone" placeholder="Enter phone" value="0123456789" class="form-input"
                        v-model="formData.phone"
                        :class="errors && errors?.phone ? 'border-red-500' : 'border-slate-200'" />
                    <span v-if="errors && errors?.phone" class="text-red-500 text-sm">{{ errors?.phone[0] }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
                <div>
                    <label for="Area" class="form-label mb-2"> Area <small>*</small></label>
                    <input type="text" id="Area" placeholder="Enter Area" class="form-input" v-model="formData.area"
                        :class="errors && errors?.area ? 'border-red-500' : 'border-slate-200'">
                    <span v-if="errors && errors?.area" class="text-red-500 text-sm">{{ errors?.area[0] }}</span>
                </div>
                <div>
                    <label for="Flat" class="form-label mb-2"> Flat</label>
                    <input type="text" id="Flat" placeholder="Enter Flat no" value="" class="form-input"
                        v-model="formData.flat_no"
                        :class="errors && errors?.flat_no ? 'border-red-500' : 'border-slate-200'" />
                    <span v-if="errors && errors?.flat_no" class="text-red-500 text-sm">{{ errors?.flat_no[0] }}</span>
                </div>

                <div>
                    <label for="Postal" class="form-label mb-2"> Postal Code <small>*</small></label>
                    <input type="text" id="Postal" v-model="formData.post_code" placeholder="Enter Postal Code" value=""
                        class="form-input"
                        :class="errors && errors?.post_code ? 'border-red-500' : 'border-slate-200'" />
                    <span v-if="errors && errors?.post_code" class="text-red-500 text-sm">{{ errors?.post_code[0]
                        }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="address" class="form-label mb-2"> Address Line 1 <small>*</small></label>
                    <input type="text" id="address" v-model="formData.address_line" placeholder="Enter address 1"
                        class="form-input"
                        :class="errors && errors?.address_line ? 'border-red-500' : 'border-slate-200'" />
                    <span v-if="errors && errors?.address_line" class="text-red-500 text-sm">{{ errors?.address_line[0]
                        }}</span>
                </div>
                <div>
                    <label for="address2" class="form-label mb-2"> Address Line 2</label>
                    <input type="text" id="address2" v-model="formData.address_line2" placeholder="Enter address 2"
                        value="" class="form-input"
                        :class="errors && errors?.address_line2 ? 'border-red-500' : 'border-slate-200'" />
                    <span v-if="errors && errors?.address_line2" class="text-red-500 text-sm">{{
            errors?.address_line2[0] }}</span>
                </div>
            </div>

            <div class="mt-4">
                <div class="text-slate-950 text-base font-medium leading-normal">
                    Address Tag
                </div>

                <div class="flex justify-between items-center gap-2 mt-2 flex-wrap">
                    <div class="flex items-center gap-2">
                        <label for="home"
                            class="px-3 py-2 bg-white rounded-[42px] border flex gap-2 items-center text-slate-600 text-base font-normal leading-normal cursor-pointer has-[:checked]:border-primary has-[:checked]:text-primary">
                            <input type="radio" id="home" v-model="formData.address_type" name="tag" value="home"
                                class="radio-btn" :checked="formData.address_type === 'home'">
                            <span class="text-base font-normal">HOME</span>
                        </label>

                        <label for="office"
                            class="px-3 py-2 bg-white rounded-[42px] border flex gap-2 items-center text-slate-600 text-base font-normal leading-normal cursor-pointer has-[:checked]:border-primary has-[:checked]:text-primary">
                            <input type="radio" id="office" v-model="formData.address_type" name="tag" value="office"
                                class="radio-btn" :checked="formData.address_type === 'office'">
                            <span class="text-base font-normal">OFFICE</span>
                        </label>

                        <label for="other"
                            class="px-3 py-2 bg-white rounded-[42px] border flex gap-2 items-center text-slate-600 text-base font-normal leading-normal cursor-pointer has-[:checked]:border-primary has-[:checked]:text-primary">
                            <input type="radio" id="other" v-model="formData.address_type" name="tag" value="other"
                                class="radio-btn" :checked="formData.address_type === 'other'">
                            <span class="text-base font-normal">OTHER</span>
                        </label>

                    </div>
                    <div class="flex items-center gap-2">
                        <input id="default" v-model="formData.is_default" name="default" type="checkbox" class="w-4 h-4"
                            :checked="formData.is_default" />
                        <label for="default" class="text-slate-500 text-sm font-normal leading-tight m-0">
                            Make it default address
                        </label>
                    </div>

                    <button v-if="route.name != 'add-new-address'" type="submit"
                        class="px-8 py-2 bg-primary text-white rounded-[42px]">
                        Submit
                    </button>
                </div>
                <button v-if="route.name === 'add-new-address'" type="submit"
                    class="px-4 py-3 md:px-6 md:py-4 bg-primary text-white text-sm md:text-base rounded-[10px] mt-6">
                    Save Address
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import { useAuth } from '../stores/AuthStore';
import ToastSuccessMessage from './ToastSuccessMessage.vue';

const toast = useToast();
const route = useRoute();
const router = useRouter();
const authStore = useAuth();

const formData = ref({
    name: '',
    phone: '',
    area: '',
    flat_no: '',
    post_code: '',
    address_line: '',
    address_line2: '',
    address_type: 'home',
    langitude: '',
    longitude: '',
    is_default: false
});

const errors = ref({});

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Address Added',
        message: 'Address added successfully',
    },
};

const addressFormSubmit = () => {
    axios.post('/address/store', formData.value, {
        headers: {
            'Authorization': authStore.token
        }
    }).then(() => {
        formData.value = {};
        authStore.fetchAddresses();
        toast(content, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "bottom-left",
            toastClassName: "vue-toastification-alert",
            timeout: 2000,
        });
        if (route.name === 'add-new-address') {
            router.push({ name: 'manage-address' })
        }
    }).catch((error) => {
        errors.value = error.response.data.errors
        toast.error(error.response.data.message, {
            position: "bottom-left",
        });
    })
}

</script>

<style scoped>
.form-label {
    @apply text-slate-700 text-base font-normal leading-normal;
}

.form-input {
    @apply p-3 rounded-lg border focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}

.formInputCoupon {
    @apply rounded-lg border border-slate-200 focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}

.radio-btn {
    @apply w-4 h-4 border appearance-none border-slate-300 rounded-full checked:bg-primary ring-primary checked:outline-1 outline-offset-1 checked:outline-primary checked:outline transition duration-100 ease-in-out m-0;
}

.radioBtn2 {
    @apply w-4 h-4 border appearance-none border-slate-300 rounded-full checked:bg-primary ring-primary checked:outline-1 outline-offset-1 checked:outline-primary checked:outline transition duration-100 ease-in-out m-0;
}
</style>
