<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { ref, watch, toRef } from 'vue';
import { truncate } from '../Helpers/userHelpers';
import { getStatusClass } from '../Helpers/userHelpers';

const dates = ref();
const selectedStatus = ref();
const searchQuery = ref('');
const currentPengajuanSelected = ref(null);

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

const applyFilters = () => {
    emit('update:filter', {
        start_date: dates.value ? dates.value[0] : null,
        end_date: dates.value ? dates.value[1] : null,
        status: selectedStatus.value,
        search_query: searchQuery.value,
    });
};




</script>
<template>
    <div class="card mt-8">
        <DataTable :value="transaksidata" paginator :rows="25" tableStyle="min-width: 50rem"
            class="shadow-md rounded-lg overflow-hidden">
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-lg font-bold">Pengajuan</span>
                    <div class="inputs flex items-center gap-x-3">
                        <DatePicker v-model="dates" selectionMode="range" :manualInput="false"
                            placeholder="Pilih Rentang Tanggal" />
                        <Select v-model="selectedStatus" showClear :options="pilihanStatus" optionLabel="nameexternal"
                            placeholder="Pilih Status" class="w-full md:w-56" />
                        <div class="card flex justify-center">
                            <InputText type="text" v-model="searchQuery" placeholder="Cari Kode" />
                        </div>
                        <Button icon="pi pi-search" iconPos="top"  @click="applyFilters" />
                    </div>
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
