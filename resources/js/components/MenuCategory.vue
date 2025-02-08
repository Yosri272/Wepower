<template>
    <div class="group relative">
        <button
            class="p-4 group bg-white rounded-xl border justify-start items-center gap-2.5 inline-flex flex-col hover:border-primary transition-all duration-300 w-full border-b-2"
            :class="(props.category?.id == route.params?.slug) ? 'border-primary' : 'border-slate-100'"
            @click="onClick">
            <img :src="props.category?.thumbnail"
                class="w-16 h-16 object-cover transition duration-500 group-hover:scale-110 rounded-lg" loading="lazy" />
            <div class="text-slate-600 text-sm font-medium leading-tight flex gap-1 items-center">
                {{ props.category?.name }}
                <span v-if="props.category?.sub_categories?.length > 0">
                    <ChevronDownIcon class="w-4 h-4 text-slate-600" />
                </span>
            </div>
        </button>

        <transition
            enter-active-class="transition ease-out duration-500"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-300"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
            >
            <div v-if="props.category?.sub_categories?.length > 0" class="w-60 absolute left-0 z-50 hidden group-hover:block pt-2">
            <div
                class="p-4 bg-white rounded-xl shadow-xl border-t-2 border-primary flex-col gap-3 flex transition ease-out duration-200 opacity-0 translate-y-1 group-hover:opacity-100 group-hover:translate-y-0">
                <button v-for="subcategory in props.category?.sub_categories" :key="subcategory.id"
                    class="p-4 bg-white rounded-xl border justify-start items-center gap-2 inline-flex hover:border-primary transition-all duration-300 w-full" @click="subcategoryProduct(subcategory)">
                    <img :src="subcategory?.thumbnail"
                        class="w-14 h-14 object-cover transition duration-500 group-hover:scale-110 rounded-lg" loading="lazy" />
                    <div class="text-slate-600 text-sm font-medium leading-tight flex gap-1 items-center">
                        {{ subcategory?.name }}
                    </div>
                </button>
            </div>
        </div>

        </transition>

    </div>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router';
import { ChevronDownIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    category: Object
});

const emit = defineEmits(['update:click']);

const router = useRouter();
const route = useRoute();

const onClick = () => {
    emit('update:click', true);
    router.push('/categories/' + props.category?.id);
}

const subcategoryProduct = (subcategory) => {
    emit('update:click', true);
    router.push('/categories/' + props.category?.id + '?subcategory=' + subcategory?.id);
}

</script>
