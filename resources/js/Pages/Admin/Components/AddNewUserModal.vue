<script setup>
import Dialog from 'primevue/dialog';
import { ref, watch } from 'vue';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Button from 'primevue/button';
import { router } from '@inertiajs/vue3';  

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    rolesUserOption: {
        type: Array,
    },  
    toastMessage: {
        type: Function
    },
    departementsUser: {
        type: Array,
    }
});

const emit = defineEmits(['update:currentVisibility']);

const visible = ref(props.currentVisibility);

// Data bindings
const fullName = ref('');
const username = ref('');
const selectedRoleUser = ref(null);
const selectedDepartementUser = ref(null);
const password = ref('');
const confirmPassword = ref('');

// Watch the visibility prop
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Method to close the dialog
const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

// Validation method for password confirmation
const checkConfirmPassword = () => {
    if (password.value !== confirmPassword.value) {
        props.toastMessage('error', 'Info', 'Konfirmasi Password Tidak Sesuai!');
    } else if (password.value === "" || confirmPassword.value === "") {
        props.toastMessage('warn', 'Info', 'Password atau Confirm Password Tidak Boleh Kosong!');
    }
};

// Method for form submission using router.post with an object
const simpanUserBaru = async (e) => {
    let hasError = false;

    // Validation for required fields
    if (!fullName.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Nama Lengkap tidak boleh kosong!');
        hasError = true;
    }

    if (!username.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Username tidak boleh kosong!');
        hasError = true;
    }

    if (!selectedDepartementUser.value) {
        props.toastMessage('error', 'Validasi', 'Departemen harus dipilih!');
        hasError = true;
    }

    if (!selectedRoleUser.value) {
        props.toastMessage('error', 'Validasi', 'Role User harus dipilih!');
        hasError = true;
    }

    if (!password.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Password tidak boleh kosong!');
        hasError = true;
    }

    if (!confirmPassword.value.trim()) {
        props.toastMessage('error', 'Validasi', 'Konfirmasi Password tidak boleh kosong!');
        hasError = true;
    }

    if (password.value !== confirmPassword.value) {
        props.toastMessage('error', 'Validasi', 'Konfirmasi Password tidak sesuai!');
        hasError = true;
    }


    // Prepare the data object
    const data = {
        full_name: fullName.value,
        username: username.value,
        role_id: selectedRoleUser.value.id,
        departement_id: selectedDepartementUser.value.id,
        password: password.value,
        password_confirmation: confirmPassword.value
    };
     
    
    try {
        await router.post('/admin/users/add', data, {
            preserveScroll: true,
            onSuccess: () => {
                props.toastMessage('success', 'Berhasil', 'User berhasil ditambahkan!');
                
                // Reset fields after successful submission
                fullName.value = '';
                username.value = '';
                selectedRoleUser.value = null;
                selectedDepartementUser.value = null;
                password.value = '';
                confirmPassword.value = '';

                closeDialog();
            },
            onError: (errors) => {
                console.log(errors)
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
    <Dialog @hide="closeDialog" v-model:visible="visible" modal header="Tambah User Baru" :style="{ width: '45rem' }">
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
            <label for="password-user" class="font-semibold w-full">Password</label>
            <InputText id="password-user" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Password Disini" v-model="password" type="password"/>
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col mt-4">
            <label for="confirm-password-user" class="font-semibold w-full">Confirm Password</label>
            <InputText id="confirm-password-user" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Confirm Password Disini" v-model="confirmPassword" type="password"/>
        </div>
        <div class="confirm-button mt-6 w-full">
            <Button @click="simpanUserBaru" icon="pi pi-save" label="Simpan User" class="w-full"/>
        </div>
    </Dialog>
</template>
    