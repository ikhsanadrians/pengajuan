<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';  // Import router untuk post request
import axios from 'axios'; // Import Axios untuk HTTP requests

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentUserId: {
        type: Number,
        default: null,
    },
    rolesUserOption: {
        type: Array,
    },
    departementsUser: {
        type: Array,
    },
    toastMessage: {
        type: Function
    }
});

const emit = defineEmits(['update:currentVisibility', 'refreshUsers']);

// State for modal visibility
const visible = ref(props.currentVisibility);

// Data bindings
const fullName = ref('');
const username = ref('');
const selectedRoleUser = ref(null);
const selectedDepartementUser = ref(null);
const password = ref('');
const confirmPassword = ref('');
const statusEnabled = ref(null); 
// Watch the visibility prop
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
    if (props.currentUserId) {
        fetchUserData(props.currentUserId); // Fetch data when the modal is shown
    }
});

// Method to close the dialog
const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

const statusUsers = [
   {
      id: 1,
      name: "Aktif"
   },
   {
      id: 0,
      name: "Non Aktif"
   }
];

// Fetch user data by ID
const fetchUserData = async (userId) => {
    isLoading.value = true; 
    try {
        const response = await axios.post(`/admin/users/${userId}`);
        const user = response.data.data;

        // Map fetched user data to the form fields
        fullName.value = user.namalengkap || '-';
        username.value = user.username || '-';
        selectedRoleUser.value = props.rolesUserOption.find(role => role.id === user.role_id) || null;
        selectedDepartementUser.value = props.departementsUser.find(dept => dept.id === user.departement_id) || null;
        password.value = ''; // Do not prefill password fields
        confirmPassword.value = '';
        statusEnabled.value =  statusUsers.find(status => status.id === user.statusenabled || null); 
    } catch (error) {
        console.error('Error fetching user data:', error);
        props.toastMessage('error', 'Gagal', 'Gagal mengambil data pengguna!');
    } finally {
        isLoading.value = false;
    }
};

const isLoading = ref(false);


// Update user data
const updateUserData = async () => {
    const data = {
        namalengkap: fullName.value,
        username: username.value,
        role_id: selectedRoleUser.value?.id,
        departement_id: selectedDepartementUser.value?.id,
        password: password.value,
        statusenabled: statusEnabled.value.id,
    };

    // Validate password confirmation
    if (password.value !== confirmPassword.value) {
        props.toastMessage('error', 'Validasi', 'Konfirmasi Password tidak sesuai!');
        return;
    }

    try {
        const response = await axios.post(`/admin/users/${props.currentUserId}/update`, data);
        
        if(!response){
            props.toastMessage('error', 'Gagal', 'Data pengguna gagal diperbarui!');
        }

        props.toastMessage('success', 'Berhasil', 'Data pengguna berhasil diperbarui!');
        emit('refreshUsers'); 
        closeDialog();
    } catch (error) {
        console.error('Error updating user data:', error);
        props.toastMessage('error', 'Gagal', 'Terjadi kesalahan saat memperbarui data pengguna!');
    } 
};
</script>
<template>
    <Dialog @hide="closeDialog" v-model:visible="visible" modal :header="`Edit User ${username}`" :style="{ width: '45rem' }">
        <template v-if="isLoading">
            <div class="flex justify-center items-center">
                <i class="pi pi-spin pi-spinner text-4xl"></i>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center gap-3 mb-3 flex-col">
                <label for="nama-user" class="font-semibold w-full">Nama Lengkap</label>
                <InputText id="nama-user" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Nama Lengkap Disini" v-model="fullName"/>
            </div>
            <div class="flex items-center gap-3 mb-3 flex-col">
                <label for="username" class="font-semibold w-full">Username</label>
                <InputText id="username" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Username Disini" v-model="username"/>
            </div>
            <div class="inputs-essential grid grid-cols-2 gap-x-2 nt-2">
                <div class="inputs-select-role">
                    <label for="departement" class="font-semibold">Departement User</label>
                    <Select 
                        v-model="selectedDepartementUser"
                        :options="departementsUser"
                        optionLabel="namadepartemen" 
                        filter 
                        showClear
                        placeholder="Pilih Departemen"
                        class="w-full mt-1"
                    />
                </div>
                <div class="inputs-select-role">
                    <label for="role" class="font-semibold">Role User</label>
                    <Select 
                        v-model="selectedRoleUser"
                        :options="rolesUserOption"
                        optionLabel="namarole"
                        filter 
                        showClear
                        placeholder="Pilih Role User"
                        class="w-full mt-1"
                    />
                </div>
            </div>
            <div class="flex items-center gap-3 mb-3 flex-col mt-4">
                <label for="password-user" class="font-semibold w-full">Password Baru</label>
                <InputText id="password-user" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Password Disini" v-model="password" type="password"/>
            </div>
            <div class="flex items-center gap-3 mb-3 flex-col mt-4">
                <label for="confirm-password-user" class="font-semibold w-full">Confirm Password Baru</label>
                <InputText id="confirm-password-user" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Confirm Password Disini" v-model="confirmPassword" type="password"/>
            </div>
            <div class="flex items-center gap-3 mb-3 flex-col mt-4">
                <label for="status-user" class="font-semibold w-full">Status User</label>
                <Select 
                    v-model="statusEnabled"  
                    placeholder="Pilih Status User"
                    class="w-full mt-1"
                    optionLabel="name"
                    :options="statusUsers"
                />
            </div>
            <div class="confirm-button mt-6 w-full">
                <Button @click="updateUserData" icon="pi pi-save" label="Simpan Perubahan" class="w-full"/>
            </div>
        </template>
    </Dialog>
</template>
