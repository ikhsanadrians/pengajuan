<script setup>
import { Link } from '@inertiajs/vue3';
import Avatar from 'primevue/avatar';

</script>
<template>
    <div class="bg-white h-14 shadow-sm border-b-[1.5px] border-gray-300">
        <div class="container mx-auto flex w-full items-center h-full">
            <div class="w-full flex justify-between">
                <Link class="flex items-center font-bold" href="/">Pengajuan Barang</Link>
                <Avatar label="P" class="mr-2 !bg-red-500" size="xlarge" />
            </div>

        </div>
    </div>
</template>
