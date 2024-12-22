<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
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
const categoryName = ref('');

// Watch the visibility prop
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Method to close the dialog
const closeDialog = () => {
    emit('update:currentVisibility', false);
};

// Method for form submission using router.post with an object
const addNewCategory = async () => {
    let hasError = false;

    // Validation for required fields
    if (!categoryName.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Kategori tidak boleh kosong!');
        hasError = true;
    }

    if (hasError) return;

    // Prepare the data object
    const data = {
        nameexternal: categoryName.value
    };

    try {
        await router.post('/admin/kategori/add', data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Kategori berhasil ditambahkan!');

                // Reset fields after successful submission
                categoryName.value = '';

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
    <Dialog @hide="closeDialog" v-model:visible="visible" modal header="Tambah Kategori Baru"
        :style="{ width: '45rem' }">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="nama-kategori" class="font-semibold w-full">Nama Kategori</label>
            <InputText id="nama-kategori" class="flex-auto w-full" autocomplete="off"
                placeholder="Masukan Nama Kategori" v-model="categoryName" />
        </div>
        <div class="confirm-button mt-6 w-full">
            <Button @click="addNewCategory" icon="pi pi-save" label="Simpan Kategori" class="w-full" />
        </div>
    </Dialog>
</template>
