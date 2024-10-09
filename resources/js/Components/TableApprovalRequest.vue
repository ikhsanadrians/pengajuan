<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue';

let dates = ref();

defineProps({
  transaksidata: {
    type: Array,
    default: () => [],
  }
});
</script>

<template>
  <div class="card mt-8">
    <DataTable :value="transaksidata" tableStyle="min-width: 50rem" class="shadow-md rounded-lg overflow-hidden">
      <template #header>
        <div class="flex flex-wrap items-center justify-between gap-2">
          <span class="text-lg font-bold">Pengajuan</span>
          <div class="inputs flex items-center gap-x-3">
            <DatePicker v-model="dates" selectionMode="range" :manualInput="false" placeholder="Pilih Rentang Tanggal" />
            <Select v-model="selectedCountry" :options="countries" filter optionLabel="name" placeholder="Pilih Status" class="w-full md:w-56" />
            <div class="card flex justify-center">
              <InputText type="text" v-model="value" placeholder="Cari Kode" />
            </div>
            <Button icon="pi pi-search" iconPos="top" />
          </div>
        </div>
      </template>
      <Column field="id" header="ID"></Column>
      <Column field="name" header="Nama"></Column>
      <Column field="status" header="Status"></Column>
      <Column field="date" header="Tanggal"></Column>
    </DataTable>
  </div>
</template>
