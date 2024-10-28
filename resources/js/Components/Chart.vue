<script setup>
import Card from 'primevue/card';

defineProps({
    username: {
        type: String,
    }
});


</script>
<template>
    <div>
        <div class="title font-bold text-lg">
            <h1>Welcome Back {{ username }}</h1>
        </div>
        <div class="grid grid-cols-3 gap-x-4">
            <Card class="mt-4 !bg-gradient-to-r from-yellow-500 to-yellow-400 !text-yellow-800">
                <template #title>
                    <i class="pi pi-box" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Pengajuan
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl font-extrabold">
                        56
                    </p>
                </template>
            </Card>
            <Card class="mt-4  !bg-gradient-to-r !bg-green-500 to-green-400">
                <template #title>
                    <i class="pi pi-send" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Approve
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl">
                        56
                    </p>
                </template>
            </Card>
            <Card class="mt-4 !bg-gradient-to-r from-red-500 to-red-400 !text-red-100">
                <template #title>
                    <i class="pi pi-times" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Ditolak
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl">
                        56
                    </p>
                </template>
            </Card>
        </div>

    </div>

</template>
