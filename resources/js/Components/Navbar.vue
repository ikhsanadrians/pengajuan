<script setup>
import { Link } from '@inertiajs/vue3';
import Avatar from 'primevue/avatar';

</script>
<template>
    <div class="bg-white h-16 shadow-sm border-b-[1.5px] border-gray-300">
        <div class="container mx-auto flex w-full items-center h-full">
            <div class="w-full flex justify-between py-8">
                <Link class="flex items-center font-bold" href="/">
                  <div class="flex flex-col">
                    <h1>PT Intertama Trikencana Bersinar</h1>
                    <p class="text-gray-600 font-light">Pengajuan Barang</p>
                  </div>
                </Link>
                <Avatar label="P" class="mr-2 !bg-red-500" size="xlarge" />
            </div>

        </div>
    </div>
</template>
