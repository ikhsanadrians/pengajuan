<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    toastMessage: {
        type: Function
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:currentVisibility', 'refreshBarangs']);

const visible = ref(props.currentVisibility);

// Data bindings
const namabarang = ref('');
const quantity = ref(0);
const category_id = ref(null);
const satuan = ref('');

// Watch the visibility prop
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Method to close the dialog
const closeDialog = () => {
    emit('update:currentVisibility', false);
};

// Method for form submission using router.post with an object
const simpanBarangBaru = async () => {
    let hasError = false;

    // Validation for required fields
    if (!namabarang.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Barang tidak boleh kosong!');
        hasError = true;
    }

    if (quantity.value <= 0) {
        props.toastMessage('error', 'Validasi', 'Quantity harus lebih dari 0!');
        hasError = true;
    }

    if (!category_id.value) {
        props.toastMessage('error', 'Validasi', 'Kategori harus dipilih!');
        hasError = true;
    }

    if (!satuan.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Satuan tidak boleh kosong!');
        hasError = true;
    }

    if (hasError) return;

    // Prepare the data object
    const data = {
        namabarang: namabarang.value,
        quantity: quantity.value,
        category_id: category_id.value,
        satuan: satuan.value
    };

    try {
        await router.post('/admin/barangs/add', data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Barang berhasil ditambahkan!');
                closeDialog();
                emit('refreshBarangs');
            },
            onError: (errors) => {
                console.log(errors);
                Object.keys(errors).forEach((key) => {
                    props.toastMessage('error', 'Validasi', errors[key]);
                });
            }
        });
    } catch (error) {
        console.error('Error during form submission', error);
    }
};
</script>

<template>
    <Dialog @hide="closeDialog" v-model:visible="visible" modal header="Tambah Barang Baru" :style="{ width: '45rem' }">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="nama-barang" class="font-semibold w-full">Nama Barang</label>
            <InputText id="nama-barang" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Nama Barang"
                v-model="namabarang" />
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="quantity" class="font-semibold w-full">Quantity</label>
            <InputText id="quantity" class="flex-auto w-full" type="number" autocomplete="off"
                placeholder="Masukan Quantity" v-model="quantity" />
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="category" class="font-semibold w-full">Kategori</label>
            <Select showClear filter v-model="category_id" :options="props.categories" optionLabel="nameexternal"
                optionValue="id" placeholder="Pilih Kategori" class="w-full mt-1" />
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="satuan" class="font-semibold w-full">Satuan</label>
            <InputText id="satuan" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Satuan"
                v-model="satuan" />
        </div>
        <div class="confirm-button mt-6 w-full">
            <Button @click="simpanBarangBaru" icon="pi pi-save" label="Simpan Barang" class="w-full" />
        </div>
    </Dialog>
</template>
