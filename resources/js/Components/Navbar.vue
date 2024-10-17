<script setup>
import { Link } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Avatar from 'primevue/avatar';

</script>
<template>
    <div class="bg-white h-16 shadow-sm border-b-[1.5px] border-gray-300">
        <div class="container mx-auto px-8 flex w-full items-center h-full">
            <div class="w-full flex justify-between items-center py-8">
                <Link class="flex items-center font-bold" href="/">
                  <div class="flex flex-col">
                    <h1>PT Intertama Trikencana Bersinar</h1>
                    <p class="text-gray-600 font-light">Pengajuan Barang</p>
                  </div>
                </Link>
                <div class="right-navbar gap-x-4 flex items-center">
                    <span class="relative">
                        <i class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 dark:text-surface-600" />
                        <InputText v-model="value1" placeholder="Cari Pengajuan" class="!pl-10 w-64" size="small" />
                    </span>
                    <Avatar label="AH" class="mr-2" size="small" style="background-color: #ece9fc; color: #2a1261" shape="circle" />
                </div>
            </div>

        </div>
    </div>
</template>
