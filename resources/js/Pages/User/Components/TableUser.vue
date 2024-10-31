<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import ProgressSpinner from 'primevue/progressspinner';
import { ref, watch, toRef } from 'vue';
import { truncate } from '../Helpers/userHelpers';
import { getStatusClass } from '../Helpers/userHelpers';
import { format } from 'date-fns';

const dates = ref();
const selectedStatus = ref();
const searchQuery = ref('');
const currentPengajuanSelected = ref(null);
const isLoading = ref(false);

const props = defineProps({
    transaksidata: {
        type: Array,
        default: () => [],
    },
    pilihanStatus: {
        type: Array,
        default: () => []
    },
    isCurrentDetailRequestModalOpen: {
        type: Boolean,
        required: true
    },
    currentPengajuanId: {
        type: String,
    }
});

const emit = defineEmits(['update:isCurrentDetailRequestModalOpen', 'update:currentPengajuanId', 'update:filter']);

const openModalDetailRequest = (unique_id) => {
    emit('update:isCurrentDetailRequestModalOpen', true);
    currentPengajuanSelected.value = unique_id;
    emit('update:currentPengajuanId', currentPengajuanSelected.value);
}

watch(() => props.isCurrentDetailRequestModalOpen, (newValue) => {
    if (!newValue) {
        currentPengajuanSelected.value = null;
    }
});

const formatDate = (date) => {
    if (!date) return null;
    return format(new Date(date), 'yyyy-MM-dd');
}

const applyFilters = async () => {
    isLoading.value = true;
    try {
        await emit('update:filter', {
            start_date: dates.value?.[0] ? formatDate(dates.value[0]) : null,
            end_date: dates.value?.[1] ? formatDate(dates.value[1]) : null,
            status: selectedStatus.value,
            search_query: searchQuery.value,
        });
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="card mt-8">
        <DataTable :value="transaksidata" paginator :rows="25" tableStyle="min-width: 50rem"
            class="shadow-md rounded-lg overflow-hidden" :loading="isLoading">
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-lg font-bold">Pengajuan</span>
                    <div class="inputs flex items-center gap-x-3">
                        <DatePicker v-model="dates" showClear selectionMode="range" :manualInput="true"
                            placeholder="Pilih Rentang Tanggal" showIcon fluid iconDisplay="input" />
                        <Select v-model="selectedStatus" :options="pilihanStatus" optionLabel="nameexternal"
                            placeholder="Pilih Status" class="w-full md:w-56" />
                        <div class="card flex justify-center">
                            <InputText type="text" v-model="searchQuery" placeholder="Cari Kode" />
                        </div>
                        <Button icon="pi pi-search" iconPos="top" @click="applyFilters" :loading="isLoading" />
                    </div>
                </div>
            </template>

            <template #empty>
                <div class="flex flex-col items-center justify-center py-6">
                    <i class="pi pi-search text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">Tidak ada data yang ditemukan</p>
                </div>
            </template>

            <template #loading>
                <div class="flex items-center justify-center py-6">
                    <ProgressSpinner class="w-8 h-8" strokeWidth="4" />
                    <span class="ml-2">Memuat data...</span>
                </div>
            </template>

            <Column header="ID">
                <template #body="{ index }">
                    {{ index + 1 }}
                </template>
            </Column>
            <Column field="unique_id" header="Kode"></Column>
            <Column field="keterangan" header="Keterangan">
                <template #body="{ data }">
                    <span :title="data.keterangan">
                        {{ truncate(data.keterangan, 20) }}
                    </span>
                </template>
            </Column>
            <Column field="created_at" header="Tanggal Diajukan"></Column>
            <Column field="quantity" header="Jumlah Barang"></Column>
            <Column field="nameexternal" header="Status">
                <template #body="{ data }">
                    <span :class="getStatusClass(data.nameexternal)" class="px-3 py-1 rounded-full">
                        {{ data.nameexternal }}
                    </span>
                </template>
            </Column>
            <Column header="Rincian">
                <template #body="{ data }">
                    <Button @click="openModalDetailRequest(data.unique_id)" rounded icon="pi pi-arrow-right"
                        iconPos="top" />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
