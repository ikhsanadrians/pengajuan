<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    categoryId: {
        type: Number,
        required: true,
    },
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    toastMessage: {
        type: Function
    }
});

const emit = defineEmits(['update:currentVisibility', 'refreshCategories']);

const visible = ref(props.currentVisibility);
const categoryName = ref('');

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

const closeDialog = () => {
    emit('update:currentVisibility', false);
};

const fetchCategoryData = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/admin/kategori/${props.categoryId}`);
        if (response.status === 200) {
            const data = response.data.data;
            categoryName.value = data.nameexternal;
        } else {
            props.toastMessage('error', 'Error', 'Gagal memuat data kategori!');
        }
    } catch (error) {
        console.error('Error fetching category data:', error);
        props.toastMessage('error', 'Error', 'Terjadi kesalahan saat memuat data kategori!');
    } finally {
        isLoading.value = false;
    }
};

const isLoading = ref(false);

const updateCategory = async () => {
    let hasError = false;

    if (!categoryName.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Kategori tidak boleh kosong!');
        hasError = true;
    }

    if (hasError) return;

    const data = {
        nameexternal: categoryName.value
    };

    try {
        await router.put(`/admin/kategori/${props.categoryId}/update`, data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Kategori berhasil diperbarui!');
                closeDialog();
                emit('refreshCategories');
            },
            onError: (errors) => {
                console.log(errors);
                Object.keys(errors).forEach((key) => {
                    props.toastMessage('error', 'Validasi', errors[key]);
                });
            }
        });
    } catch (error) {
        console.error('Error updating category:', error);
        props.toastMessage('error', 'Error', 'Terjadi kesalahan saat memperbarui kategori!');
    }
};

const handleEditClick = () => {
    if (props.categoryId) {
        fetchCategoryData();
    }
};

watch(() => visible.value, (newVal) => {
    if (newVal) handleEditClick();
});
</script>

<template>
    <Dialog @hide="closeDialog" v-model:visible="visible" modal :header='`Edit Kategori: ${categoryName}`'
        :style="{ width: '45rem' }">
        <template v-if="isLoading">
            <div class="flex justify-center items-center">
                <i class="pi pi-spin pi-spinner text-4xl"></i>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center gap-3 mb-3 flex-col">
                <label for="nama-kategori" class="font-semibold w-full">Nama Kategori</label>
                <InputText id="nama-kategori" class="flex-auto w-full" autocomplete="off"
                    placeholder="Masukan Nama Kategori" v-model="categoryName" />
            </div>
            <div class="confirm-button mt-6 w-full">
                <Button @click="updateCategory" icon="pi pi-save" label="Perbarui Kategori" class="w-full" />
            </div>
        </template>
    </Dialog>
</template>
