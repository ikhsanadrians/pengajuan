<script setup>
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Select from 'primevue/select';
import InputText from 'primevue/inputtext';
import Button from 'primevue/button';
import DatePicker from 'primevue/datepicker';
import { ref } from 'vue';


let dates = ref();

</script>

<template>
    <div class="card mt-8">
        <DataTable :value="products" tableStyle="min-width: 50rem" class="shadow-md rounded-lg overflow-hidden">
            <template #header>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <span class="text-lg font-bold">Pengajuan</span>
                    <div class="inputs flex items-center gap-x-3">
                        <DatePicker v-model="dates" selectionMode="range" :manualInput="false" placeholder="Pilih Rentang Tanggal" />
                        <Select v-model="selectedCountry" :options="countries" filter optionLabel="name"
                        placeholder="Pilih Status" class="w-full md:w-56">
                        <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex items-center">
                                <img :alt="slotProps.value.label"
                                    src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                                    :class="`mr-2 flag flag-${slotProps.value.code.toLowerCase()}`"
                                    style="width: 18px" />
                                <div>{{ slotProps.value.name }}</div>
                            </div>
                            <span v-else>
                                {{ slotProps.placeholder }}
                            </span>
                        </template>
                        <template #option="slotProps">
                            <div class="flex items-center">
                                <img :alt="slotProps.option.label"
                                    src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                                    :class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`"
                                    style="width: 18px" />
                                <div>{{ slotProps.option.name }}</div>
                            </div>
                        </template>
                    </Select>
                    <div class="card flex justify-center">
                        <InputText type="text" v-model="value" placeholder="Cari Kode" />
                    </div>
                    <Button icon="pi pi-search" iconPos="top" />

                    </div>

                </div>
            </template>
            <Column field="code" header="Code"></Column>
            <Column field="name" header="Name"></Column>
            <Column field="category" header="Category"></Column>
            <Column field="quantity" header="Quantity"></Column>
        </DataTable>
    </div>
</template>
