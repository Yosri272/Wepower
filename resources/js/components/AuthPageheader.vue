<template>
    <div class="py-3 px-2 text-slate-800 text-lg md:text-2xl font-medium font-['Roboto'] tracking-tight md:leading-loose bg-white">
        {{ title }}
    </div>
</template>

<script setup>
const props = defineProps({
    title: String
});

</script>
