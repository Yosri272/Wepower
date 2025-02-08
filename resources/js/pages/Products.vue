<template>
    <div>
        <div class="main-container py-4 bg-slate-100">

            <div class="w-full p-2 md:p-4 bg-white rounded-lg sm:rounded-xl md:rounded-2xl flex gap-3 md:gap-6 items-center justify-between">
                <!-- Back -->
                <router-link to="/" class="py-2 flex gap-1 sm:gap-2 items-center justify-center">
                    <ArrowLeftIcon class="w-4 h-4 sm:w-6 sm:h-6 text-slate-600" />
                    <div class="text-slate-600 text-sm sm:text-base font-normal leading-normal">Back</div>
                </router-link>

                <!-- Categorories slider -->
                <div class="grow overflow-x-auto whitespace-nowrap">
                    <span class="text-primary">“{{ master.search || 'all' }}”</span>
                    <span class="text-slate-800 text-base font-normal pl-2">
                        {{ totalProducts }} items found
                    </span>
                </div>

                <!-- Filter button -->
                <div>
                    <button
                        class="p-2 sm:px-4 sm:py-3 bg-slate-200 rounded-md sm:rounded-[10px] justify-center items-center gap-2 inline-flex text-slate-600 text-sm sm:text-base font-normal leading-normal border-0 outline-none hover:text-primary transition duration-300"
                        @click="showfilterCanvas = true">
                        <FunnelIcon class="w-4 h-4 sm:w-6 sm:h-6" />
                        <div class="grow shrink basis-0">
                            Filter
                        </div>
                    </button>
                </div>

            </div>
        </div>

        <div class="main-container py-12">
            <div
                class="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-3 sm:gap-6 items-start">

                <div v-for="product in products" :key="product.id" class="w-full">
                    <ProductCard :product="product" />
                </div>

            </div>
            <div v-if="products.length == 0" class="flex justify-center items-center w-full mt-8">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    No products found
                </div>

            </div>

            <!-- Pagination -->
            <div class="flex justify-between items-center w-full mt-8  gap-4 flex-wrap">
                <div class="text-slate-800 text-base font-normal leading-normal">
                    Showing {{ perPage * (currentPage - 1)+1 }} to {{ perPage * (currentPage - 1) + products.length }} of {{ totalProducts }} results
                </div>
                <div>
                    <vue-awesome-paginate :total-items="totalProducts" :items-per-page="perPage" type="button" :max-pages-shown="5"
                        v-model="currentPage" :on-click="onClickHandler" />
                </div>
            </div>
        </div>

        <!-- Filter Canvas Drawer -->
        <TransitionRoot as="template" :show="showfilterCanvas">
            <Dialog as="div" class="relative z-10" @close="showfilterCanvas = false">
                <TransitionChild as="template" enter="ease-in-out duration-500" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in-out duration-500" leave-from="opacity-100"
                    leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-30 transition-opacity" />
                </TransitionChild>

                <div class="fixed inset-0 overflow-hidden">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                            <TransitionChild as="template"
                                enter="transform transition ease-in-out duration-500 sm:duration-700"
                                enter-from="translate-x-full" enter-to="translate-x-0"
                                leave="transform transition ease-in-out duration-500 sm:duration-700"
                                leave-from="translate-x-0" leave-to="translate-x-full">
                                <DialogPanel class="pointer-events-auto relative w-screen max-w-md">
                                    <TransitionChild as="template" enter="ease-in-out duration-500"
                                        enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-500"
                                        leave-from="opacity-100" leave-to="opacity-0">
                                        <div class="absolute left-0 top-0 -ml-8 flex pr-2 pt-4 sm:-ml-10 sm:pr-4"></div>
                                    </TransitionChild>
                                    <div
                                        class="flex h-full flex-col justify-between overflow-y-scroll bg-white shadow-xl">
                                        <div class="p-4 flex flex-col gap-7">
                                            <div class="flex justify-between items-center">
                                                <div class="text-slate-950 text-xl font-bold leading-loose">Filter</div>
                                                <button
                                                    class="w-8 h-8 flex justify-center items-center bg-slate-100 rounded-full"
                                                    @click="showfilterCanvas = false">
                                                    <XMarkIcon class="w-5 h-5 text-slate-700" />
                                                </button>
                                            </div>

                                            <!-- Customer Review -->
                                            <div>
                                                <div class="text-slate-950 text-base font-medium leading-normal">
                                                    Customer Review
                                                </div>
                                                <!-- Rating -->
                                                <div class="flex flex-col gap-2 mt-3">
                                                    <div v-for="rating in ratings" :key="rating" class="grow">
                                                        <label :for="`rating${rating}`"
                                                            class="cursor-pointer has-[:checked]:border-primary text-slate-800 flex items-center justify-between px-2 py-1.5 bg-white rounded-lg border border-slate-100 gap-1.5">
                                                            <div class="flex items-center gap-1">
                                                                <div class="flex items-center">
                                                                    <StarIcon v-for="i in 5" :key="i" class="w-5 h-5"
                                                                        :class="i <= rating ? 'text-amber-500' : 'text-gray-200'" />
                                                                </div>
                                                                <div class="text-base font-medium leading-normal">{{
                        rating
                    }}.0</div>
                                                            </div>
                                                            <input type="radio" v-model="filterFormData.review"
                                                                :id="`rating${rating}`" name="review" :value="rating"
                                                                class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sort by -->
                                            <div>
                                                <div class="text-slate-950 text-base font-medium leading-normal">Sort by
                                                </div>

                                                <select v-model="filterFormData.shortBy"
                                                    class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none">
                                                    <option v-for="shortBy in filterSortBy" :key="shortBy"
                                                        :value="shortBy.value">{{ shortBy.name }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <div class="flex justify-between items-center gap-2">
                                                    <div class="text-slate-950 text-base font-medium leading-normal">
                                                        Product
                                                        Price</div>
                                                    <div class="text-primary text-base font-normal leading-normal">
                                                        $300 - $4200
                                                    </div>
                                                </div>
                                                <div class="flex mt-2">
                                                    <input type="range" min="300" max="4200"
                                                        v-model="filterFormData.minPrice"
                                                        class="w-full rotate-180 appearance-none  bg-slate-300 accent-primary-600 focus:accent-primary h-2 rounded-r-full" />
                                                    <input type="range" min="00" max="4200"
                                                        v-model="filterFormData.maxPrice"
                                                        class="w-full appearance-none bg-slate-300 accent-primary-600  focus:accent-primary h-2 rounded-r-full -ml-0.5" />
                                                </div>
                                                <div
                                                    class="text-slate-400 text-xs font-normal leading-none flex justify-between mt-2">
                                                    <span>
                                                        ${{ filterFormData.minPrice }}
                                                    </span>
                                                    <span>
                                                        ${{ filterFormData.maxPrice }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Brand -->
                                            <div>
                                                <div class="text-slate-950 text-base font-medium leading-normal">Brand
                                                </div>

                                                <select v-model="filterFormData.brand"
                                                    class="w-full mt-1 p-3 rounded bg-transparent border border-gray-100 outline-none">
                                                    <option value="" selected disabled>Select Brand</option>
                                                    <option value="brand">Brand Name</option>
                                                    <option value="brand1">Brand Name1</option>
                                                    <option value="brand2">Brand Name2</option>
                                                    <option value="brand3">Brand Name3</option>
                                                </select>
                                            </div>

                                            <!-- color -->
                                            <div>
                                                <div class="text-slate-950 text-base font-medium leading-normal">Color
                                                </div>

                                                <div
                                                    class="flex flex-wrap gap-4 p-3 rounded-xl border border-slate-200 mt-1">

                                                    <label
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color" value="red"
                                                            id="red" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>Red</span>
                                                    </label>

                                                    <label for="Black"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color" value="black"
                                                            id="Black" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>Black</span>
                                                    </label>

                                                    <label for="Blue"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color" value="Blue"
                                                            id="Blue" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>Blue</span>
                                                    </label>

                                                    <label for="Orange"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color"
                                                            value="Orange" id="Orange" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>Orange</span>
                                                    </label>

                                                    <label for="White"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color" value="White"
                                                            id="White" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>White</span>
                                                    </label>

                                                    <label for="Multicolour"
                                                        class="cursor-pointer has-[:checked]:text-primary text-slate-800 flex items-center p-2 bg-white gap-2">
                                                        <input type="radio" v-model="filterFormData.color"
                                                            value="Multicolour" id="Multicolour" name="color"
                                                            class="w-5 h-5 appearance-none checked:bg-primary rounded-full border-2 border-slate-300 shrink-0 transition duration-300" />
                                                        <span>Multicolour</span>
                                                    </label>

                                                </div>
                                            </div>

                                        </div>

                                        <!-- button Clear and Apply -->
                                        <div class="flex gap-6 p-6 border-t border-slate-200">
                                            <button
                                                class="grow px-4 py-3 rounded-[10px] border border-primary text-primary text-base font-medium leading-normal">
                                                Clear
                                            </button>
                                            <button
                                                class="grow px-4 py-3 bg-primary rounded-[10px] border border-primary text-white text-base font-medium leading-normal">
                                                Apply
                                            </button>
                                        </div>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { FunnelIcon, XMarkIcon, ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { StarIcon } from '@heroicons/vue/24/solid';
import ProductCard from '../components/ProductCard.vue';
import { useMaster } from '../stores/MasterStore';

const master = useMaster();

onMounted(() => {
    fetchProducts();
    window.scrollTo(0, 0);
});

const search = master.search;

watch(() => master.search, () => {
    fetchProducts();
});

const currentPage = ref(1);
const perPage = 12;

const onClickHandler = (page) => {
    currentPage.value = page;
    fetchProducts();
};

const showfilterCanvas = ref(false);

const filterFormData = ref({
    review: null,
    shortBy: null,
    brand: null,
    color: null,
    size: null,
    minPrice: 0,
    maxPrice: 1000
});

const ratings = [5, 4, 3, 2, 1];

const categories = ref([]);

const products = ref([]);
const totalProducts = ref(0);

const fetchProducts = async () => {
    axios.get('/products', { params: { page: currentPage.value, per_page: perPage, search: master.search } }).then((response) => {
        totalProducts.value = response.data.data.total;
        products.value = response.data.data.products;
    });
};

const fetchCategories = async () => {
    if (categories.value.length === 0) {
        axios.get('/categories').then((response) => {
            categories.value = response.data.data.categories;
        });
    }
};

const filterSortBy = [
    {
        name: 'High to Low',
        value: 'high_to_low'
    },
    {
        name: 'Low to High',
        value: 'low_to_high'
    },
    {
        name: 'Most Selling',
        value: 'most_selling'
    },
    {
        name: 'Top Seller',
        value: 'top_seller'
    },
    {
        name: 'New Product',
        value: 'new_product'
    }
];

</script>

<style>

input[type=range]::-webkit-slider-runnable-track,
input[type=range]::-ms-track,
input[type=range]::-moz-range-track {
    background: #000;
}

input[type=range]::-moz-range-thumb,
input[type=range]::-ms-thumb,
input[type=range]::-webkit-slider-thumb {
    background: #000;
}
</style>
