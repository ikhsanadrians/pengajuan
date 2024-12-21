<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    toastMessage: {
        type: Function
    }
});

const emit = defineEmits(['update:currentVisibility']);

const visible = ref(props.currentVisibility);

// Data bindings
const namadepartemen = ref('');
const address = ref('');

// Watch the visibility prop
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Method to close the dialog
const closeDialog = () => {
    emit('update:currentVisibility', false);
};

// Method for form submission using router.post with an object
const simpanDepartemenBaru = async () => {
    let hasError = false;

    // Validation for required fields
    if (!namadepartemen.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Departemen tidak boleh kosong!');
        hasError = true;
    }

    if (!address.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Alamat Departemen tidak boleh kosong!');
        hasError = true;
    }

    if (hasError) return;

    // Prepare the data object
    const data = {
        namadepartemen: namadepartemen.value,
        address: address.value
    };

    try {
        await router.post('/admin/departements/add', data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Departemen berhasil ditambahkan!');

                // Reset fields after successful submission
                namadepartemen.value = '';
                address.value = '';

                closeDialog();
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
    <Dialog @hide="closeDialog" v-model:visible="visible" modal header="Tambah Departemen Baru" :style="{ width: '45rem' }">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="nama-departemen" class="font-semibold w-full">Nama Departemen</label>
            <InputText id="nama-departemen" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Nama Departemen" v-model="namadepartemen"/>
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="alamat-departemen" class="font-semibold w-full">Alamat Departemen</label>
            <Textarea id="alamat-departemen" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Alamat Departemen" v-model="address"/>
        </div>
        <div class="confirm-button mt-6 w-full">
            <Button @click="simpanDepartemenBaru" icon="pi pi-save" label="Simpan Departemen" class="w-full"/>
        </div>
    </Dialog>
</template>
