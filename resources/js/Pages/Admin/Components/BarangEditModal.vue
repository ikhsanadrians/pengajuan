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
    barangId: {
        type: Number,
        default: null,
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
    if (props.barangId) {
        fetchBarangData(props.barangId); // Fetch data when the modal is shown
    }
});

// Method to close the dialog
const closeDialog = () => {
    emit('update:currentVisibility', false);
};

// Method to fetch barang data
const fetchBarangData = async (barangId) => {
    try {
        const response = await axios.get(`/admin/barangs/${barangId}`);
        if (response.status === 200) {
            const data = response.data.data; // Adjusted to access the 'data' property
            namabarang.value = data.namabarang;
            quantity.value = data.quantity;
            category_id.value = data.category_id;
            satuan.value = data.satuan;
        } else {
            props.toastMessage('error', 'Error', 'Gagal memuat data barang!');
        }
    } catch (error) {
        console.error('Error fetching barang data:', error);
        props.toastMessage('error', 'Error', 'Terjadi kesalahan saat memuat data barang!');
    }
};

// Method for form submission using router.put with an object
const updateBarang = async () => {
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
        await router.put(`/admin/barangs/${props.barangId}/update`, data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Barang berhasil diperbarui!');
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
    <Dialog @hide="closeDialog" v-model:visible="visible" modal header="Edit Barang" :style="{ width: '45rem' }">
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
            <Select v-model="category_id" :options="categories" optionLabel="name" placeholder="Pilih Kategori"
                class="w-full mt-1" />
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="satuan" class="font-semibold w-full">Satuan</label>
            <InputText id="satuan" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Satuan"
                v-model="satuan" />
        </div>
        <div class="confirm-button mt-6 w-full">
            <Button @click="updateBarang" icon="pi pi-save" label="Perbarui Barang" class="w-full" />
        </div>
    </Dialog>
</template>
