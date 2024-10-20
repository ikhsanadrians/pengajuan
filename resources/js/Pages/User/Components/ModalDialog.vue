<!-- ModalDialog.vue -->
<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';

// Menerima prop dari parent
const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    }
});

// Event untuk menginformasikan ke parent tentang perubahan visibility
const emit = defineEmits(['update:currentVisibility']);

// Membuat ref internal untuk sinkronisasi visibility
const visible = ref(props.currentVisibility);

// Watcher untuk memonitor perubahan prop dari parent
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Emit event ketika dialog ditutup
const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false); // Mengirim event ke parent
};
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Buat Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Keterangan</label>
            <InputText id="username" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Keterangan Disini"/>
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Departemen</label>
            <Select optionLabel="name" placeholder="Pilih Departemen" class="w-full" />
        </div>
        <div class="flex items-start gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Tambah Barang</label>
            <div class="flex justify-start gap-x-3">
                <Select optionLabel="name" placeholder="Cari Nama Barang" class="w-[20rem]" />
                <IconField>
                    <InputIcon class="pi pi-box" />
                    <InputText v-model="value1" placeholder="Jumlah Barang" />
                </IconField>
                <Button icon="pi pi-plus" label="Tambah" aria-label="Save" />
            </div>
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Cancel" severity="secondary" @click="closeDialog"></Button>
            <Button type="button" label="Save" @click="closeDialog"></Button>
        </div>
    </Dialog>
</template>
