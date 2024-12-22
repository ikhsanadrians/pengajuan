<script setup>
import Navbar from '@/Components/Navbar.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import { ref, computed } from 'vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmationDeleteDepartement from './Components/ConfirmationDeleteDepartement.vue';
import { router } from '@inertiajs/vue3';
import UserEditModal from './Components/UserEditModal.vue';
import AddNewDepartmentModal from './Components/AddNewDepartmentModal.vue';
import DepartementEditModal from './Components/DepartementEditModal.vue';

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
const filterName = ref("");

const filteredDepartmenets = computed(() => {
    return props.departementsUser.filter(departement => {
        const matchesName = departement.namadepartemen.toLowerCase().includes(filterName.value.toLowerCase());
        return matchesName;
    });
});

const toggleModalAddDepartment = () => {
    modalVisibilityAddNewDepartement.value = true;
};

const modalVisibilityAddNewDepartement = ref(false);
const modalVisibilityEditDepartement = ref(false);
const modalVisibilityConfirmationDeleteDepartement = ref(false);

const currentDepartementId = ref(null);
const currentDepartmentToDeleteId = ref(null);


const toggleEditDepartementModal = (departementId) => {
    modalVisibilityEditDepartement.value = true;
    currentDepartementId.value = departementId;
};

const triggerDeleteConfirmation = (userId) => {
    modalVisibilityConfirmationDeleteDepartement.value = true;
    currentDepartmentToDeleteId.value = userId;
};

const refreshUsers = () => {
    router.reload({ only: ['departementsUser'] });
};

</script>

<template>
    <Navbar />
    <AddNewDepartmentModal v-model:currentVisibility="modalVisibilityAddNewDepartement" :toastMessage="loadToastMessage"
        :rolesUserOption="rolesUser" :departementsUser="departementsUser" />
    <DepartementEditModal :departmentId="currentDepartementId"
        v-model:currentVisibility="modalVisibilityEditDepartement" :toastMessage="loadToastMessage"
        @refreshUsers="refreshUsers" />
    <ConfirmationDeleteDepartement :currentVisibility="modalVisibilityConfirmationDeleteDepartement"
        @update:currentVisibility="modalVisibilityConfirmationDeleteDepartement = $event"
        :currentDepartmentToDeleteId="currentDepartmentToDeleteId"
        @update:currentDepartmentToDeleteId="currentDepartmentToDeleteId = $event" :toastMessage="loadToastMessage"
        @refreshTransaction="refreshUsers" />
    <div class="container mx-auto py-5 px-20">
        <div class="add-users bg-white p-10 rounded-lg border-[1.8px] border-slate-200">
            <div class="head flex justify-between">
                <div class="head font-semibold">
                    Master Data Departement
                </div>
                <Button @click="toggleModalAddDepartment" icon="pi pi-plus" label="Tambah Departement"
                    class="btn-tambah" />
            </div>
            <div class="wrapper flex gap-x-8 w-full">
                <div class="wrapper w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mt-4">
                    <Card v-for="(departement, index) in filteredDepartmenets" :key="departement.id"
                        style="overflow: hidden"
                        class="w-full border-[1.8px] border-slate-200 flex flex-col items-center relative">
                        <template #header>
                            <p class="text-sm text-gray-500 absolute top-4 right-4">{{ departement.statusenabled ?
                                'Aktif' : 'Tidak Aktif' }}
                            </p>
                            <div
                                class="profile-picture flex justify-center mt-4 bg-[#10B981] text-white w-16 h-16 flex justify-center items-center rounded-full">
                                <i class="pi pi-building" style="font-size: 2rem"></i>
                            </div>
                        </template>
                        <template #title>
                            <h1 class="text-lg font-bold text-center">{{ departement.namadepartemen || '' }}</h1>
                        </template>
                        <template #footer>
                            <div class="flex w-full gap-4 mt-4">
                                <Button @click="toggleEditDepartementModal(departement.id)" rounded icon="pi pi-pencil"
                                    label="Edit" severity="secondary" outlined class="w-full" />
                                <Button @click="triggerDeleteConfirmation(departement.id)" rounded icon="pi pi-trash"
                                    label="Hapus" severity="danger" class="w-full" />
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
                            <label for="" class="text-slate-400">Nama Departement</label>
                            <InputText v-model="filterName" placeholder="Masukan Nama Departement"
                                class="w-full mt-1" />
                        </div>
                        <div class="button-search">
                            <Button icon="pi pi-search" label="Cari Department" class="w-full mt-8" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Toast position="bottom-right" group="br" />
</template>
