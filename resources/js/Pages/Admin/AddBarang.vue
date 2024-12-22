<script setup>
import Navbar from '@/Components/Navbar.vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import { ref, computed } from 'vue';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import ConfirmationDeleteBarang from './Components/ConfirmationDeleteBarang.vue';
import { router } from '@inertiajs/vue3';
import BarangEditModal from './Components/BarangEditModal.vue';
import AddNewBarangModal from './Components/AddNewBarangModal.vue';

const props = defineProps({
    barangs: {
        type: Array,
        default: () => [],
    },
    categories: {
        type: Array,
        default: () => [],
    }
});

const toast = useToast();

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

const selectedCategory = ref(null);
const filterName = ref("");

const filteredBarangs = computed(() => {
    return props.barangs.filter(barang => {
        const matchesName = barang.namabarang.toLowerCase().includes(filterName.value.toLowerCase());
        const matchesCategory = selectedCategory.value ? barang.category_id === selectedCategory.value : true;
        return matchesName && matchesCategory;
    });
});

const toggleModalAddBarang = () => {
    modalVisibilityAddNewBarang.value = true;
};

const modalVisibilityAddNewBarang = ref(false);
const modalVisibilityEditBarang = ref(false);
const modalVisibilityConfirmationDeleteBarang = ref(false);

const currentBarangId = ref(null);
const currentBarangToDeleteId = ref(null);

const toggleEditBarangModal = (barangId) => {
    modalVisibilityEditBarang.value = true;
    currentBarangId.value = barangId;
};

const triggerDeleteConfirmation = (barangId) => {
    modalVisibilityConfirmationDeleteBarang.value = true;
    currentBarangToDeleteId.value = barangId;
};

const refreshBarangs = () => {
    router.reload({ only: ['barangs', 'categories'] });
};

</script>

<template>
    <Navbar />
    <AddNewBarangModal v-model:currentVisibility="modalVisibilityAddNewBarang" :categories="props.categories"
        :toastMessage="loadToastMessage" @refreshBarangs="refreshBarangs" />
    <BarangEditModal :barangId="currentBarangId" v-model:currentVisibility="modalVisibilityEditBarang"
        :toastMessage="loadToastMessage" @refreshBarangs="refreshBarangs" :categories="props.categories" />
    <ConfirmationDeleteBarang :currentVisibility="modalVisibilityConfirmationDeleteBarang"
        @update:currentVisibility="modalVisibilityConfirmationDeleteBarang = $event"
        :currentBarangToDeleteId="currentBarangToDeleteId"
        @update:currentBarangToDeleteId="currentBarangToDeleteId = $event" :toastMessage="loadToastMessage"
        @refreshBarangs="refreshBarangs" />
    <div class="container mx-auto py-5 px-20">
        <div class="add-barangs bg-white p-10 rounded-lg border-[1.8px] border-slate-200">
            <div class="head flex justify-between">
                <div class="head font-semibold">
                    Master Data Barang
                </div>
                <Button @click="toggleModalAddBarang" icon="pi pi-plus" label="Tambah Barang" class="btn-tambah" />
            </div>
            <div class="wrapper flex gap-x-8 w-full">
                <div class="wrapper w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mt-4">
                    <Card v-for="(barang, index) in filteredBarangs" :key="barang.id" style="overflow: hidden"
                        class="w-full border-[1.8px] border-slate-200 flex flex-col items-center relative h-fit">
                        <template #header>
                            <p class="text-sm text-gray-500 absolute top-4 right-4">{{ barang.statusenabled ?
                                'Aktif' : 'Tidak Aktif' }}
                            </p>
                            <div
                                class="profile-picture flex justify-center mt-4 bg-[#10B981] text-white w-16 h-16 flex justify-center items-center rounded-full">
                                <i class="pi pi-box" style="font-size: 2rem"></i>
                            </div>
                        </template>
                        <template #title>
                            <h1 class="text-lg font-bold text-center">{{ barang.namabarang || '' }}</h1>
                        </template>
                        <template #footer>
                            <div class="flex w-full gap-4 mt-4">
                                <Button @click="toggleEditBarangModal(barang.id)" rounded icon="pi pi-pencil"
                                    label="Edit" severity="secondary" outlined class="w-full" />
                                <Button @click="triggerDeleteConfirmation(barang.id)" rounded icon="pi pi-trash"
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
                            <label for="" class="text-slate-400">Nama Barang</label>
                            <InputText v-model="filterName" placeholder="Masukan Nama Barang" class="w-full mt-1" />
                        </div>
                        <div class="inputs mt-4">
                            <label for="" class="text-slate-400">Kategori</label>
                            <Select filter showClear v-model="selectedCategory" :options="props.categories"
                                option-label="nameexternal" option-value="id" placeholder="Pilih Kategori"
                                class="w-full mt-1" />
                        </div>
                        <div class="button-search">
                            <Button icon="pi pi-search" label="Cari Barang" class="w-full mt-8" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Toast position="bottom-right" group="br" />
</template>
