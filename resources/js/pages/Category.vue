<template>
    <div class="main-container pt-8 pb-12">

        <div class="text-slate-800 text-lg lg:text-3xl font-bold">All Categories</div>

        <!-- categories -->
        <div class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-6 items-start mt-6">
            <div v-for="category in categories" :key="category.id" class="w-full">
                <CategoryCard :category="category" />
            </div>
            <div v-if="categories.length == 0" class="text-slate-950 text-xl font-medium leading-7">
                No Categories Found
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import CategoryCard from '../components/CategoryCard.vue';

const categories = ref([]);

onMounted(() => {
    fetchCategories();
    window.scrollTo(0, 0);
});

const fetchCategories = async () => {
    axios.get('/categories').then((response) => {
        categories.value = response.data.data.categories;
    })
};

</script>
