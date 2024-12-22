<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import axios from 'axios';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentDepartmentToDeleteId: {
        type: String,
    },
    toastMessage: {
        type: Function,
    },
    onSuccessReject: {
        type: Function,
        required: true,
    },
});

const emit = defineEmits(['update:currentVisibility', 'refreshTransaction']);
const visible = ref(props.currentVisibility);
const loading = ref(false);
const currentDepartementId = ref(props.currentDepartementToDeleteId);

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

watch(() => props.currentDepartementToDeleteId, (newValue) => {
    currentDepartmentToDeleteId.value = newValue;
});

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});

const deleteUser = async () => {
    try {
        loading.value = true;
        const response = await axios.post('/admin/departements/delete', { departmentIdToDelete: props.currentDepartmentToDeleteId });
        console.log(response)

        if (response.status == 200) {
            props.toastMessage('success', 'Info', 'Berhasil Menghapus User!');
            closeDialog();
            emit('refreshTransaction');
        } else {
            props.toastMessage('error', 'Info', 'Gagal Menghapus User');
        }
    } catch (error) {
        props.toastMessage('error', 'Info', error.message || 'Terjadi kesalahan saat menghapus user');
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Apakah Anda Yakin Ingin Menghapus Department Ini?"
        :style="{ width: '30rem', MaxHeight: '90%' }" @hide="closeDialog">
        <div class="btn flex gap-x-4 items-center mt-3 justify-end w-full float-right">
            <div class="btn-1">
                <Button @click="closeDialog" label="Batal" class="!px-6" rounded severity="secondary" />
            </div>
            <div class="btn-2">
                <Button :loading="loading" icon="pi pi-trash" label="Hapus" severity="danger" rounded
                    :disabled="loading" @click="deleteUser" />
            </div>
        </div>
    </Dialog>
</template>
