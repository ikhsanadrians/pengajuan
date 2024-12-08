<script setup>

import Navbar from '@/Components/Navbar.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
});


</script>

<template>
    <Navbar />
    <div class="container mx-auto py-5 px-20">
        <div class="add-users bg-white p-4 rounded-md border-[1.8px] border-slate-200">
            <div class="head font-semibold">
                Tambah User
            </div>
            <div class="wrapper flex gap-x-8">
                <div class="wrapper grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                    <Card
                        v-for="(user, index) in users"
                        :key="user.id"
                        style="width: 16rem; overflow: hidden"
                        class="border-[1.8px] border-slate-200 flex flex-col items-center">
                        <template #header>
                            <div class="profile-picture flex justify-center mt-4">
                                <img src="../../../../public/ilustration/user-avatar.png" class="h-16" />
                            </div>
                        </template>
                        <template #title>
                            <h1 class="text-lg font-bold text-center">{{ user.username }}</h1>
                        </template>
                        <template #content>
                            <p class="text-sm text-gray-500">Role: {{ user.role }}</p>
                            <p class="text-sm text-gray-500">Status: {{ user.statusenabled ? 'Aktif' : 'Tidak Aktif' }}</p>

                        </template>
                        <template #footer>
                            <div class="flex w-full gap-4 mt-1">
                                <Button label="Edit" severity="secondary" outlined class="w-full" />
                                <Button label="Hapus" severity="danger" class="w-full" />
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="filter">
                    <div class="head font-semibold">
                        Filter
                    </div>


                </div>
            </div>

        </div>
    </div>
</template>
