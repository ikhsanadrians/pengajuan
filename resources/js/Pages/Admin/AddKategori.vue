<script setup>
import Navbar from '@/Components/Navbar.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import { ref, computed } from 'vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmationDeleteCategory from './Components/ConfirmationDeleteCategory.vue';
import { router } from '@inertiajs/vue3';
import CategoryEditModal from './Components/CategoryEditModal.vue';
import AddNewCategoryModal from './Components/AddNewCategoryModal.vue';

const props = defineProps({
    kategoris: {
        type: Array,
        default: () => [],
    }
});

const toast = useToast();

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

const filterName = ref("");

const filteredKategoris = computed(() => {
    return props.kategoris.filter(kategori => {
        const matchesName = kategori.nameexternal.toLowerCase().includes(filterName.value.toLowerCase());
        return matchesName;
    });
});

const toggleModalAddCategory = () => {
    modalVisibilityAddNewCategory.value = true;
};

const modalVisibilityAddNewCategory = ref(false);
const modalVisibilityEditCategory = ref(false);
const modalVisibilityConfirmationDeleteCategory = ref(false);

const currentCategoryId = ref(null);
const currentCategoryToDeleteId = ref(null);

const toggleEditCategoryModal = (categoryId) => {
    modalVisibilityEditCategory.value = true;
    currentCategoryId.value = categoryId;
};

const triggerDeleteConfirmation = (categoryId) => {
    modalVisibilityConfirmationDeleteCategory.value = true;
    currentCategoryToDeleteId.value = categoryId;
};

const refreshCategories = () => {
    router.reload({ only: ['kategoris'] });
};

</script>

<template>
    <Navbar />
    <AddNewCategoryModal v-model:currentVisibility="modalVisibilityAddNewCategory" :toastMessage="loadToastMessage"
        :categories="kategoris" />
    <CategoryEditModal :categoryId="currentCategoryId" v-model:currentVisibility="modalVisibilityEditCategory"
        :toastMessage="loadToastMessage" @refreshCategories="refreshCategories" />
    <ConfirmationDeleteCategory :currentVisibility="modalVisibilityConfirmationDeleteCategory"
        @update:currentVisibility="modalVisibilityConfirmationDeleteCategory = $event"
        :currentCategoryToDeleteId="currentCategoryToDeleteId"
        @update:currentCategoryToDeleteId="currentCategoryToDeleteId = $event" :toastMessage="loadToastMessage"
        @refreshCategories="refreshCategories" />
    <div class="container mx-auto py-5 px-20">
        <div class="add-categories bg-white p-10 rounded-lg border-[1.8px] border-slate-200">
            <div class="head flex justify-between">
                <div class="head font-semibold">
                    Master Data Kategori
                </div>
                <Button @click="toggleModalAddCategory" icon="pi pi-plus" label="Tambah Kategori" class="btn-tambah" />
            </div>
            <div class="wrapper flex gap-x-8 w-full">
                <div class="wrapper w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mt-4">
                    <Card v-for="(kategori, index) in filteredKategoris" :key="kategori.id" style="overflow: hidden"
                        class="w-full border-[1.8px] border-slate-200 flex flex-col items-center relative">
                        <template #header>
                            <p class="text-sm text-gray-500 absolute top-4 right-4">{{ kategori.statusenabled ?
                                'Aktif' : 'Tidak Aktif' }}
                            </p>
                            <div
                                class="profile-picture flex justify-center mt-4 bg-[#10B981] text-white w-16 h-16 flex justify-center items-center rounded-full">
                                <i class="pi pi-tags" style="font-size: 2rem"></i>
                            </div>
                        </template>
                        <template #title>
                            <h1 class="text-lg font-bold text-center">{{ kategori.nameexternal || '' }}</h1>
                        </template>
                        <template #footer>
                            <div class="flex w-full gap-4 mt-4">
                                <Button @click="toggleEditCategoryModal(kategori.id)" rounded icon="pi pi-pencil"
                                    label="Edit" severity="secondary" outlined class="w-full" />
                                <Button @click="triggerDeleteConfirmation(kategori.id)" rounded icon="pi pi-trash"
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
                            <label for="" class="text-slate-400">Nama Kategori</label>
                            <InputText v-model="filterName" placeholder="Masukan Nama Kategori" class="w-full mt-1" />
                        </div>
                        <div class="button-search">
                            <Button icon="pi pi-search" label="Cari Kategori" class="w-full mt-8" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Toast position="bottom-right" group="br" />
</template>
