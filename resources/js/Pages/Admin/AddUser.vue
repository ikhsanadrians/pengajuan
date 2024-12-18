<script setup>
import Navbar from '@/Components/Navbar.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import { ref } from 'vue';
import AddNewUserModal from './Components/AddNewUserModal.vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmationDeleteUser from './Components/ConfirmationDeleteUser.vue';
import { router } from '@inertiajs/vue3'; 
import UserEditModal from './Components/UserEditModal.vue';

const props = defineProps({
    users: {
        type: Array,
        default: () => [],
    },
    rolesUser: {
        type: Array,
        default: () => [],
    },
    departementsUser: {
        type: Array,
        default: () => []
    }
});

const toast = useToast();

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

const selectedRoleUser = ref(null);

const currentUserToDeleteId = ref(null);

const modalVisibilityAddNewUser = ref(false);
const modalVisibilityConfirmationDeleteUser = ref(false);

const toggleModalAddUser = () => {
    modalVisibilityAddNewUser.value = true;
};

const modalVisibilityEditUser = ref(false);
const currentUserId = ref(null);


const toggleEditUserModal = (userId) => {
    console.log(userId);
    modalVisibilityEditUser.value = true;
    currentUserId.value = userId;
};

const triggerDeleteConfirmation = (userId) => {
    modalVisibilityConfirmationDeleteUser.value = true;
    currentUserToDeleteId.value = userId;
};

const refreshUsers = () => {
    router.reload({ only: ['users'] });
};
</script>

<template>
    <Navbar />
    <AddNewUserModal :toastMessage="loadToastMessage" :currentVisibility="modalVisibilityAddNewUser" :rolesUserOption="rolesUser" :departementsUser="departementsUser"/>
    <UserEditModal :toastMessage="loadToastMessage" 
    :rolesUserOption="rolesUser" :departementsUser="departementsUser" :currentUserId="currentUserId" @update:currentUserId="currentUserId = $event"  :currentVisibility="modalVisibilityEditUser" @update:currentVisibility="modalVisibilityEditUser = $event"
    @refreshUsers="refreshUsers"/>
    <ConfirmationDeleteUser 
        :currentVisibility="modalVisibilityConfirmationDeleteUser"
        @update:currentVisibility="modalVisibilityConfirmationDeleteUser = $event" 
        :currentUserToDeleteId="currentUserToDeleteId"
        @update:currentUserToDeleteId="currentUserToDeleteId = $event" 
        :toastMessage="loadToastMessage" 
        @refreshTransaction="refreshUsers" 
    />
    <div class="container mx-auto py-5 px-20">
        <div class="add-users bg-white p-10 rounded-lg border-[1.8px] border-slate-200">
            <div class="head flex justify-between">
                <div class="head font-semibold">
                    Master Data User
                </div>
                <Button @click="toggleModalAddUser" icon="pi pi-plus" label="Tambah User" class="btn-tambah" />
            </div>
            <div class="wrapper flex gap-x-8 w-full">
                <div class="wrapper w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mt-4">
                    <Card v-for="(user, index) in users" :key="user.id" style="overflow: hidden"
                        class="w-full border-[1.8px] border-slate-200 flex flex-col items-center relative">
                        
                        <template #header>
                            <p class="text-sm text-gray-500 absolute top-4 right-4">{{ user.statusenabled ? 'Aktif' : 'Tidak Aktif' }}
                            </p>
                            <div class="profile-picture flex justify-center mt-4">
                                <img src="../../../../public/ilustration/user-avatar.png" class="h-16" />
                            </div>
                        </template>
                        <template #title>
                            <h1 class="text-lg font-bold text-center">{{ user.username }}</h1>
                            <p class="text-[15px] text-center text-gray-400">{{ user.namalengkap ||  "-" }}</p>
                        </template>
                        <template #content>
                            <div class="roles  flex justify-center self-center">
                                <p class="text-sm text-gray-500 text-center flex items-center gap-x-1 bg-yellow-200 text-yellow-600 rounded-2xl w-fit px-4 py-1">
                                    <i class="pi pi-id-card"></i> 
                                    {{ user.role.toUpperCase() }}
                                </p>
                            </div>
                        </template>
                        <template #footer>
                            <div class="flex w-full gap-4 mt-1">
                                <Button @click="toggleEditUserModal(user.id)" rounded icon="pi pi-pencil" label="Edit" severity="secondary" outlined class="w-full" />
                                <Button @click="triggerDeleteConfirmation(user.id)" rounded icon="pi pi-trash" label="Hapus" severity="danger" class="w-full" />
                            </div>
                        </template>
                    </Card>
                </div>
                <div class="filter w-[30%] mt-4">
                    <div class="head font-semibold">
                        Filter
                    </div>
                    <div class="input-filter mt-4">
                        <div class="inputs">
                            <label for="" class="text-slate-400">Nama User</label>
                            <InputText placeholder="Masukan Nama User" class="w-full mt-1" />
                        </div>
                        <div class="inputs-select-role mt-4">
                            <label for="" class="text-slate-400">Role User</label>
                            <Select v-model="selectedRoleUser" showClear :options="rolesUser" filter optionLabel="namarole"
                                placeholder="Pilih Role User" class="w-full mt-1"/>
                        </div>
                        <div class="button-search">
                            <Button icon="pi pi-search" label="Cari User" class="w-full mt-8">

                            </Button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <Toast position="bottom-right" group="br" />
</template>
