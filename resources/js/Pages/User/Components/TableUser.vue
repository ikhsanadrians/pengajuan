<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue';


let dates = ref();
let selectedStatus = ref();
let value = ref('');
let statuses = ref([]);

defineProps({
  transaksidata: {
    type: Array,
    default: () => [],
  },
  pilihanStatus: {
     type: Array,
     default: () => []
  }
});
</script>

<template>
  <div class="card mt-8">
    <DataTable :value="transaksidata" paginator :rows="25" tableStyle="min-width: 50rem" class="shadow-md rounded-lg overflow-hidden">
      <template #header>
        <div class="flex flex-wrap items-center justify-between gap-2">
          <span class="text-lg font-bold">Pengajuan</span>
          <div class="inputs flex items-center gap-x-3">
            <DatePicker v-model="dates" selectionMode="range" :manualInput="false" placeholder="Pilih Rentang Tanggal" />
            <Select v-model="selectedStatus" showClear :options="pilihanStatus" optionLabel="nameexternal" placeholder="Pilih Status" class="w-full md:w-56" />
            <div class="card flex justify-center">
              <InputText type="text" v-model="value" placeholder="Cari Kode" />
            </div>
            <Button icon="pi pi-search" iconPos="top" />
          </div>
        </div>
      </template>

      <!-- Fixed index column using template -->
      <Column header="ID">
        <template #body="{ index }">
          {{ index + 1 }}
        </template>
      </Column>

      <Column field="unique_id" header="Kode"></Column>
      <Column field="keterangan" header="Keterangan"></Column>
      <Column field="created_at" header="Tanggal Diajukan"></Column>
      <Column field="quantity" header="Jumlah Barang"></Column>
      <Column field="nameexternal" header="Status">
        <template #body="{ data }">
          <span :class="{
            'bg-yellow-500 text-white': data.nameexternal === 'Request',
            'bg-orange-500 text-white': data.nameexternal === 'Ditinjau',
            'bg-green-500 text-white': data.nameexternal === 'Approved',
            'bg-red-500 text-white': data.nameexternal === 'Ditolak'
          }" class="px-3 py-1 rounded-full">
            {{ data.nameexternal }}
          </span>
        </template>
      </Column>
      <Column header="Rincian">
        <template #body>
            <Button rounded icon="pi pi-arrow-right" iconPos="top" />
        </template>
     </Column>
    </DataTable>
  </div>
</template>
