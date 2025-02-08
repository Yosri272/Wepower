<template>
    <div class="main-container py-6">
        <div class="text-xl md:text-2xl font-bold text-slate-800 pb-2 md:pb-3 border-b">
            {{ content?.title }}
        </div>
        <div class="mt-6" v-html="content?.description"></div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const content = ref({});

onMounted(() => {
    fetchData();
    window.scrollTo(0, 0);
});

const fetchData = () => {
    axios.get('/legal-pages/terms-and-conditions').then((response) => {
        content.value = response.data.data.content;
    });
}

</script>
