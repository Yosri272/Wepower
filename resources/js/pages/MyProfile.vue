<template>
    <div>
        <!-- Header -->
        <AuthPageheader title="My Profile" />

        <div class="p-3 md:p-4 xl:p-6">

            <form @submit.prevent="sumitForm()">
                <div class="max-w-[920px] mx-auto bg-white rounded-xl md:rounded-2xl p-3 md:p-4 xl:p-6">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-100">

                        <div class="text-primary text-sm font-medium">Profile Picture</div>
                        <div class="flex flex-col gap-2 justify-center items-center">
                            <img :src="profilePhoto" alt=""
                                class="w-24 h-24 md:w-36 md:h-36 bg-white rounded-full object-cover">

                            <div class="flex gap-2">
                                <label for="upload"
                                    class="text-white text-sm font-normal px-2 py-1 md:px-3 md:py-2 bg-slate-800 rounded-md cursor-pointer">
                                    <input type="file" id="upload" class="hidden" @change="ChangeProfilePhoto">
                                    Change Photo
                                </label>
                                <button v-if="ChangeProfile"
                                    class="px-2 py-1 md:px-3 md:py-2 bg-red-500 text-white rounded-md"
                                    @click="removePhoto">
                                    <TrashIcon class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-white rounded-xl border border-slate-100 mt-3">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="form-label"> Name</label>
                                <input type="text" id="name" v-model="formData.name" placeholder="Enter name"
                                    class="form-input"
                                    :class="(errors && errors?.name) ? 'border-red-500' : 'border-slate-200'">
                                <span v-if="errors && errors?.name" class="text-red-500 text-sm">
                                    {{ errors?.name[0] }}
                                </span>
                            </div>

                            <div>
                                <label for="phone" class="form-label">Mobile Number</label>
                                <input type="text" id="phone" v-model="formData.phone" placeholder="Enter phone"
                                    readonly class="form-input"
                                    :class="(errors && errors?.phone) ? 'border-red-500' : 'border-slate-200'">
                                <span v-if="errors && errors?.phone" class="text-red-500 text-sm">
                                    {{ errors?.phone[0] }}
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label for="gender" class="form-label bg-transparent">Gender</label>
                                <select name="gender" id="gender" v-model="formData.gender" class="form-input bg-transparent h-[52px]"
                                    :class="(errors && errors?.gender) ? 'border-red-500' : 'border-slate-200'">
                                    <option value="" disabled selected>Select a gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <span v-if="errors && errors?.gender" class="text-red-500 text-sm">
                                    {{ errors?.gender[0] }}
                                </span>
                            </div>

                            <div>
                                <label for="date" class="form-label">Date of Birth</label>
                                <input type="date" id="date" v-model="formData.date_of_birth" class="form-input"
                                    :class="(errors && errors?.date_of_birth) ? 'border-red-500' : 'border-slate-200'">
                                <span v-if="errors && errors?.date_of_birth" class="text-red-500 text-sm">
                                    {{ errors?.date_of_birth[0] }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="email" class="form-label">Email (Optional)</label>
                            <input type="email" id="email" v-model="formData.email" class="form-input" placeholder="Enter email"
                                :class="(errors && errors?.email) ? 'border-red-500' : 'border-slate-200'" readonly>
                            <span v-if="errors && errors?.email" class="text-red-500 text-sm">
                                {{ errors?.email[0] }}
                            </span>
                        </div>

                        <button type="submit"
                            class="bg-primary px-4 md:px-6 py-2 md:py-4 rounded-lg :md:rounded-[10px] text-white mt-6">
                            Update Profile
                        </button>

                    </div>
                </div>
            </form>

        </div>

    </div>
</template>

<script setup>
import { TrashIcon } from '@heroicons/vue/24/solid';
import { onMounted, ref } from "vue";
import { useToast } from "vue-toastification";
import AuthPageheader from "../components/AuthPageheader.vue";
import ToastSuccessMessage from "../components/ToastSuccessMessage.vue";
import { useAuth } from "../stores/AuthStore";

const toast = useToast();
const authStore = useAuth();

const errors = ref(null);

const profilePhoto = ref(null);
const ChangeProfile = ref(false);

const removePhoto = () => {
    profilePhoto.value = authStore.user?.profile_photo;
    ChangeProfile.value = false;
}

const ChangeProfilePhoto = (event) => {
    profilePhoto.value = URL.createObjectURL(event.target.files[0]);
    formData.value.profile_photo = event.target.files[0];
    ChangeProfile.value = true;
}

const formData = ref({
    name: authStore.user?.name,
    phone: authStore.user?.phone,
    gender: authStore.user?.gender ? authStore.user?.gender : '',
    date_of_birth: authStore.user?.date_of_birth,
    email: authStore.user?.email,
    profile_photo: null,
});

const demouser = ref(false);

onMounted(() => {
    if (authStore.user) {
        profilePhoto.value = authStore.user?.profile_photo;
    }
    window.scrollTo(0, 0);

    if(authStore.user?.email === 'user@readyecommerce.com') {
        demouser.value = true;
    }
});

const content = {
    component: ToastSuccessMessage,
    props: {
        title: 'Profile Updated',
        message: 'Your profile updated successfully',
    },
};

const sumitForm = () => {
    const processData = new FormData();
    processData.append('name', formData.value.name);
    processData.append('phone', formData.value.phone);
    processData.append('gender', formData.value.gender ? formData.value.gender : '');
    processData.append('date_of_birth', formData.value.date_of_birth ? formData.value.date_of_birth : '');
    processData.append('email', formData.value.email ? formData.value.email : '');
    if (formData.value.profile_photo) {
        processData.append('profile_photo', formData.value.profile_photo);
    }

    axios.post('/update-profile', processData, {
        headers: {
            'Authorization': authStore.token
        }
    }).then((response) => {
        authStore.user = response.data.data.user;
        ChangeProfile.value = false;
        toast(content, {
            type: "default",
            hideProgressBar: true,
            icon: false,
            position: "bottom-left",
            toastClassName: "vue-toastification-alert",
            timeout: 2000,
        });
        errors.value = {}
    }).catch((error) => {
        errors.value = error.response.data.errors
        if (errors.value?.profile_photo) {
            toast.error(errors.value?.profile_photo[0], {
                position: "bottom-left",
            });
        }
    })
}

</script>

<style scoped>
.form-label {
    @apply text-slate-700 text-base font-normal block mb-1;
}

.form-input {
    @apply p-3 rounded-lg border focus:border-primary w-full outline-none text-base font-normal leading-normal placeholder:text-slate-400;
}
</style>
