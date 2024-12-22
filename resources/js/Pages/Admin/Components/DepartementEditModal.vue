<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Button from 'primevue/button';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    departmentId: {
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

const emit = defineEmits(['update:currentVisibility', 'refreshUsers']);

const visible = ref(props.currentVisibility);
const namadepartemen = ref('');
const address = ref('');

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

const closeDialog = () => {
    emit('update:currentVisibility', false);
};

const fetchDepartmentData = async () => {
    isLoading.value = true;
    try {
        const response = await axios.get(`/admin/departements/${props.departmentId}`);
        if (response.status === 200) {
            const data = response.data.data;
            namadepartemen.value = data.namadepartemen;
            address.value = data.address;
        } else {
            props.toastMessage('error', 'Error', 'Gagal memuat data departemen!');
        }
    } catch (error) {
        console.error('Error fetching department data:', error);
        props.toastMessage('error', 'Error', 'Terjadi kesalahan saat memuat data departemen!');
    } finally {
        isLoading.value = false;
    }
};

const isLoading = ref(false);


const updateDepartment = async () => {
    let hasError = false;

    if (!namadepartemen.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Departemen tidak boleh kosong!');
        hasError = true;
    }

    if (!address.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Alamat Departemen tidak boleh kosong!');
        hasError = true;
    }

    if (hasError) return;

    const data = {
        namadepartemen: namadepartemen.value,
        address: address.value
    };

    try {
        await router.put(`/admin/departements/${props.departmentId}/update`, data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'Departemen berhasil diperbarui!');
                closeDialog();
                emit('refreshUsers');
            },
            onError: (errors) => {
                console.log(errors);
                Object.keys(errors).forEach((key) => {
                    props.toastMessage('error', 'Validasi', errors[key]);
                });
            }
        });
    } catch (error) {
        console.error('Error updating department:', error);
        props.toastMessage('error', 'Error', 'Terjadi kesalahan saat memperbarui departemen!');
    }
};

const handleEditClick = () => {
    if (props.departmentId) {
        fetchDepartmentData();
    }
};

watch(() => visible.value, (newVal) => {
    if (newVal) handleEditClick();
});
</script>

<template>
    <Dialog @hide="closeDialog" v-model:visible="visible" modal :header='`Edit Departemen: ${namadepartemen}`'
        :style="{ width: '45rem' }">
        <template v-if="isLoading">
            <div class="flex justify-center items-center">
                <i class="pi pi-spin pi-spinner text-4xl"></i>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center gap-3 mb-3 flex-col">
                <label for="nama-departemen" class="font-semibold w-full">Nama Departemen</label>
                <InputText id="nama-departemen" class="flex-auto w-full" autocomplete="off"
                    placeholder="Masukan Nama Departemen" v-model="namadepartemen" />
            </div>
            <div class="flex items-center gap-3 mb-3 flex-col">
                <label for="alamat-departemen" class="font-semibold w-full">Alamat Departemen</label>
                <Textarea id="alamat-departemen" class="flex-auto w-full" autocomplete="off"
                    placeholder="Masukan Alamat Departemen" v-model="address" />
            </div>
            <div class="confirm-button mt-6 w-full">
                <Button @click="updateDepartment" icon="pi pi-save" label="Perbarui Departemen" class="w-full" />
            </div>
        </template>
    </Dialog>
</template>
