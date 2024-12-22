<script setup>
import Card from 'primevue/card';

defineProps({
    username: {
        type: String,
    },
    role: {
        type: String,
    },
    pengajuanCount: {
        type: Number,
        default: 0
    },
    approveCount: {
        type: Number,
        default: 0
    },
    ditinjauCount: {
        type: Number,
        default: 0
    },
    ditolakCount: {
        type: Number,
        default: 0
    }
});

</script>
<template>
    <div>
        <div class="title font-bold text-lg">
            <h1>Welcome Back {{ username }} </h1>
        </div>
        <div class="grid grid-cols-4 gap-x-4">
            <Card class="mt-4 !bg-gradient-to-r from-yellow-400 to-yellow-500 !text-yellow-800">
                <template #title>
                    <i class="pi pi-box" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Request
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl font-extrabold  bg-white px-4 py-2 rounded-3xl w-fit h-fit">
                        {{ pengajuanCount }}
                    </p>
                </template>
            </Card>
            <Card class="mt-4 !bg-gradient-to-r from-orange-400 to-orange-500 !text-orange-800">
                <template #title>
                    <i class="pi pi-user-edit" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Ditinjau
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl font-extrabold bg-white px-4 py-2 rounded-3xl w-fit h-fit">
                        {{ ditinjauCount }}
                    </p>
                </template>
            </Card>
            <Card class="mt-4  !bg-gradient-to-r !bg-green-400 to-green-500">
                <template #title>
                    <i class="pi pi-send" style="font-size: 1.5rem"></i>
                    <h1 class="font-semibold">
                        Approve
                    </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl  bg-white px-4 py-2 rounded-3xl w-fit h-fit">
                        {{ approveCount }}
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
                    <p class="m-0 text-2xl text-red-500 font-semibold bg-white px-4 py-2 rounded-3xl w-fit h-fit">
                        {{ ditolakCount }}
                    </p>
                </template>
            </Card>
        </div>

    </div>

</template>
