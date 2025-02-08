<template>
    <div>
        <div class="bg-white px-3 text-slate-600 flex items-center gap-1 pt-2 leading-normal">
            <UserIcon class="w-5 h-5 md:w-6 md:h-6" />
            <router-link to="/profile" class="hover:text-primary">
                Profile
            </router-link>
            <span>/ Change Password</span>
        </div>
        <!-- Header -->
        <AuthPageheader title="Change Password" />

        <div class="p-3 md:p-4 xl:p-6">

            <div class="max-w-5xl mx-auto">
                <form @submit.prevent="formSubmit()" class="bg-white rounded-lg md:rounded-xl p-4 md:p-6">

                    <div class="">
                        <label class="form-label">Current Password</label>
                        <div class="relative">
                            <input :type="showCurrentPassword ? 'text' : 'password'" v-model="formData.current_password"  placeholder="Enter Current Password" class="form-input" :class="(errors && errors?.current_password) ? 'border-red-500' : 'border-slate-200'">
                            <button type="button" @click="showCurrentPassword = !showCurrentPassword">
                                <EyeIcon v-if="showCurrentPassword"
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                                <EyeSlashIcon v-else
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                            </button>
                        </div>
                        <span v-if="errors && errors?.current_password" class="text-red-500 text-sm">{{ errors?.current_password[0] }}</span>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Create New Password</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" v-model="formData.password"  placeholder="Create New Password"
                                class="form-input" :class="(errors && errors?.password) ? 'border-red-500' : 'border-slate-200'">
                            <button type="button" @click="showPassword = !showPassword">
                                <EyeIcon v-if="showPassword"
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                                <EyeSlashIcon v-else
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                            </button>
                        </div>
                        <span v-if="errors && errors?.password" class="text-red-500 text-sm">{{ errors?.password[0] }}</span>
                    </div>

                    <div class="mt-4">
                        <label class="form-label">Confirm New Password</label>
                        <div class="relative">
                            <input :type="showConformPassword ? 'text' : 'password'" v-model="formData.password_confirmation"  placeholder="Confirm New Password" class="form-input" :class="(errors && errors?.password_confirmation) ? 'border-red-500' : 'border-slate-200'">
                            <button type="button" @click="showConformPassword = !showConformPassword">
                                <EyeIcon v-if="showConformPassword"
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                                <EyeSlashIcon v-else
                                    class="w-6 h-6 text-slate-700 absolute right-4 top-1/2 -translate-y-1/2" />
                            </button>
                        </div>
                        <span v-if="errors && errors?.password_confirmation" class="text-red-500 text-sm">{{ errors?.password_confirmation[0] }}</span>
                    </div>

                    <button type="submit"
                        class="px-4 py-3 md:px-6 md:py-4 bg-primary text-white text-sm md:text-base rounded-[10px] mt-6">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { UserIcon } from '@heroicons/vue/24/outline';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid';
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import AuthPageheader from '../components/AuthPageheader.vue';
import ToastSuccessMessage from '../components/ToastSuccessMessage.vue';
import { useAuth } from '../stores/AuthStore';

import { useToast } from 'vue-toastification';
const authStore = useAuth();

const toast = useToast();
const router = useRouter();

const showCurrentPassword = ref(false);
const showPassword = ref(false);
const showConformPassword = ref(false);

const formData = ref({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const errors = ref({});

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Password Updated',
        message: 'Your password updated successfully',
    },
};

const formSubmit = () => {
    axios.post('/change-password', formData.value, {
        headers: {
            'Authorization': authStore.token
        }
    }).then(() => {
        formData.value = {};
        toast(content, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "bottom-left",
            toastClassName: "vue-toastification-alert",
            timeout: 2000,
        });
        errors.value = null
        router.push({ name: 'profile' })
    }).catch((error) => {
        errors.value = error.response.data.errors
        if (!error.response.data.errors) {
            toast.error(error.response.data.message, {
                position: "bottom-left",
            });
        }
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
</style>
