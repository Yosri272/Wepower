<template>
    <div>
        <TopHeader />
        <Navbar />

        <slot />

        <Footer />
        <BasketCard />

    </div>
</template>

<script setup>
import { onMounted, watch } from "vue";
import TopHeader from "../components/TopHeader.vue"
import Navbar from "../components/Navbar.vue";
import Footer from "../components/Footer.vue";
import BasketCard from '../components/BasketCard.vue';

import { useMaster } from '../stores/MasterStore';
const master = useMaster();

onMounted(() => {
    window.scrollTo(0, 0);
    setupThemeColors();
});

watch(() => master.themeColors, () => {
    setupThemeColors();
})

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
