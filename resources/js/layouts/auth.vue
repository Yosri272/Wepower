<template>
    <div>
        <TopHeader />
        <MiddleNavbar />

        <div class="flex flex-col md:flex-row md:h-[calc(100vh-106px)]">
            <div
                class="md:w-[240px] lg:w-[330px] lg:pl-8 lg:pr-6 md:px-3 px-2 py-2 bg-white h-full md:shrink-0 md:overflow-hidden md:overflow-y-auto">
                <DashboardSidebar />
            </div>
            <div
                class="w-full bg-slate-100 overflow-y-auto flex flex-col justify-between gap-3 h-[calc(100vh-175px)] md:h-full">
                <div class="grow">
                    <slot />
                </div>

                <div class="w-full text-center text-slate-500 text-sm font-normal py-3">
                    © {{ year }} All right reserved by RazinSoft
                </div>
            </div>
        </div>
        <DashboardBasketCard />
    </div>
</template>

<script setup>
import { onMounted, watch } from "vue";
import { useRouter, useRoute } from "vue-router";
import TopHeader from "../components/TopHeader.vue"
import MiddleNavbar from "../components/MiddleNavbar.vue";
import DashboardSidebar from "../components/DashboardSidebar.vue";
import DashboardBasketCard from "../components/DashboardBasketCard.vue";

import { useAuth } from "../stores/AuthStore";
import { useMaster } from "../stores/MasterStore";

const authStore = useAuth();
const master = useMaster();

const router = useRouter();
const route = useRoute();

watch(() => authStore.user, () => {
    if (!authStore.user) {
        router.push('/');
    }
})

watch(() => route.path, () => {
    window.scrollTo(0, 0);
})

onMounted(() => {
    if (!authStore.user) {
        router.push('/');
    }
    setupThemeColors();
})

const year = new Date().getFullYear();

const areThemeColorsValid = () => {
    const colorProperties = [
        'primary', 'primary50', 'primary100', 'primary200', 'primary300',
        'primary400', 'primary500', 'primary600', 'primary700', 'primary800',
        'primary900', 'primary950'
    ];

    for (const color of colorProperties) {
        if (!master.themeColors[color]) {
            return false;
        }
    }
    return true;
}

const setupThemeColors = () => {
    if (!areThemeColorsValid()) {
        return;
    }
    document.documentElement.style.setProperty('--primary', master.themeColors.primary);
    document.documentElement.style.setProperty('--primary-50', master.themeColors.primary50);
    document.documentElement.style.setProperty('--primary-100', master.themeColors.primary100);
    document.documentElement.style.setProperty('--primary-200', master.themeColors.primary200);
    document.documentElement.style.setProperty('--primary-300', master.themeColors.primary300);
    document.documentElement.style.setProperty('--primary-400', master.themeColors.primary400);
    document.documentElement.style.setProperty('--primary-500', master.themeColors.primary500);
    document.documentElement.style.setProperty('--primary-600', master.themeColors.primary600);
    document.documentElement.style.setProperty('--primary-700', master.themeColors.primary700);
    document.documentElement.style.setProperty('--primary-800', master.themeColors.primary800);
    document.documentElement.style.setProperty('--primary-900', master.themeColors.primary900);
    document.documentElement.style.setProperty('--primary-950', master.themeColors.primary950);
}

</script>
