<template>
    <router-link :to="routeUrl" class="w-full">
        <div
            class="p-5 bg-white rounded-2xl border border-slate-100 hover:border-primary transition duration-300 group">
            <div class="w-full overflow-hidden 2xl:h-32 xl:h-24 lg:h-24 md:h-36 sm:h-36 h-28">
                <img :src="props.category?.thumbnail"
                    class="w-full h-full object-cover transition duration-500 group-hover:scale-110" loading="lazy" />
            </div>
            <div
                class="text-center text-slate-600 group-hover:text-primary transition-all text-base font-medium leading-normal truncate">
                {{ props.category?.name }}
            </div>
        </div>
    </router-link>
</template>

<script setup>
import { useRoute } from 'vue-router';
import { ref, onMounted } from 'vue';

const route = useRoute();

const props = defineProps({
    category: Object
});

const routeUrl = ref(`/categories/${props.category?.id}`);

onMounted(() => {
    if (route.name === 'shop-detail') {
        routeUrl.value = `/shops/${route.params.id}/categories/${props.category?.id}`
    }
});

</script>
