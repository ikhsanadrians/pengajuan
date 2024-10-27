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
                  <h1 class="font-bold">
                      Pengajuan
                  </h1>
                </template>
                <template #content>
                    <p class="m-0 text-2xl">
                       56
                    </p>
                </template>
            </Card>
            <Card class="mt-4  !bg-gradient-to-r !bg-green-500 to-green-400">
                <template #title>Approve</template>
                <template #content>
                    <p class="m-0 text-2xl">
                       56
                    </p>
                </template>
            </Card>
            <Card class="mt-4 !bg-gradient-to-r from-red-500 to-red-400 !text-red-100">
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
