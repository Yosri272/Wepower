<template>
    <div v-if="master.showFooter" class="main-container bg-primary-950">
        <!--==== Copyright ====-->
        <div class="py-5 w-full border-t border-primary-900">
            <div class="text-center text-white text-sm font-normal leading-tight">
                © {{ new Date().getFullYear() }} All right reserved by RazinSoft
            </div>
        </div>

    </div>
</template>

<script setup>
import { useMaster } from '../stores/MasterStore';

const master = useMaster();

</script>

