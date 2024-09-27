<script setup>
  import Card from 'primevue/card';

</script>
<template>
    <div>
        <div class="title font-bold text-lg">
            <h1>Welcome Back Admin!</h1>
        </div>
        <div class="grid grid-cols-3 gap-x-4">
            <Card class="mt-4 !bg-yellow-500 !text-yellow-800">
                <template #title>Pengajuan</template>
                <template #content>
                    <p class="m-0 text-2xl">
                       56
                    </p>
                </template>
            </Card>
            <Card class="mt-4 !bg-green-500">
                <template #title>Approve</template>
                <template #content>
                    <p class="m-0 text-2xl">
                       56
                    </p>
                </template>
            </Card>
            <Card class="mt-4 !bg-red-500 !text-red-100">
                <template #title>Tertolak</template>
                <template #content>
                    <p class="m-0 text-2xl">
                       56
                    </p>
                </template>
            </Card>
        </div>

    </div>

</template>
