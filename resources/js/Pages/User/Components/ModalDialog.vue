<!-- ModalDialog.vue -->
<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Panel from 'primevue/panel';
import Card from 'primevue/card';



// Menerima prop dari parent
const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    }
});

// Event untuk menginformasikan ke parent tentang perubahan visibility
const emit = defineEmits(['update:currentVisibility']);

// Membuat ref internal untuk sinkronisasi visibility
const visible = ref(props.currentVisibility);

// Watcher untuk memonitor perubahan prop dari parent
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

// Emit event ketika dialog ditutup
const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false); // Mengirim event ke parent
};
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Buat Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Keterangan</label>
            <InputText id="username" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Keterangan Disini"/>
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Departemen</label>
            <Select optionLabel="name" placeholder="Pilih Departemen" class="w-full" />
        </div>
        <div class="flex items-start gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Tambah Barang</label>
            <div class="flex justify-start gap-x-3">
                <Select optionLabel="name" placeholder="Cari Nama Barang" class="w-[20rem]" />
                <IconField>
                    <InputIcon class="pi pi-box" />
                    <InputText v-model="value1" placeholder="Jumlah Barang" />
                </IconField>
                <Button icon="pi pi-plus" label="Tambah" aria-label="Save" />
            </div>
        </div>
        <label for="username" class="font-semibold w-full">Daftar Barang Diajukan</label>
        <div class="w-full h-[10rem] overflow-y-scroll">
            <div class="card-wrappers flex flex-col gap-y-3 my-4 pr-4">
                <Card class="border-[1.3px]">
                    <template #content>
                        <div class="twrappers flex items-start justify-start gap-x-3">
                            <div class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                            </div>
                            <div class="tblnbtn flex justify-between items-start w-full">
                                <table class="w-fit">
                                    <tr>
                                        <th class="text-left">Nama Barang</th>
                                        <td class="text-left px-3">Lenovo Thinkpad</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Jumlah</th>
                                        <td class="text-left px-3">2</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Keterangan</th>
                                        <td class="text-left px-3">Blabla bla</td>
                                    </tr>
                                </table>
                                <div class="btn-action flex items-center gap-x-2">
                                    <div class="bg-yellow-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-pen-to-square text-yellow-600" style="font-size: 15px"></i>
                                    </div>
                                    <div class="bg-red-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-trash text-red-600" style="font-size: 15px"></i>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </template>
                </Card>
                <Card class="border-[1.3px]">
                    <template #content>
                        <div class="twrappers flex items-start justify-start gap-x-3">
                            <div class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                            </div>
                            <div class="tblnbtn flex justify-between items-start w-full">
                                <table class="w-fit">
                                    <tr>
                                        <th class="text-left">Nama Barang</th>
                                        <td class="text-left px-3">Lenovo Thinkpad</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Jumlah</th>
                                        <td class="text-left px-3">2</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Keterangan</th>
                                        <td class="text-left px-3">Blabla bla</td>
                                    </tr>
                                </table>
                                <div class="btn-action flex items-center gap-x-2">
                                    <div class="bg-yellow-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-pen-to-square text-yellow-600" style="font-size: 15px"></i>
                                    </div>
                                    <div class="bg-red-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-trash text-red-600" style="font-size: 15px"></i>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </template>
                </Card>
                <Card class="border-[1.3px]">
                    <template #content>
                        <div class="twrappers flex items-start justify-start gap-x-3">
                            <div class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                            </div>
                            <div class="tblnbtn flex justify-between items-start w-full">
                                <table class="w-fit">
                                    <tr>
                                        <th class="text-left">Nama Barang</th>
                                        <td class="text-left px-3">Lenovo Thinkpad</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Jumlah</th>
                                        <td class="text-left px-3">2</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Keterangan</th>
                                        <td class="text-left px-3">Blabla bla</td>
                                    </tr>
                                </table>
                                <div class="btn-action flex items-center gap-x-2">
                                    <div class="bg-yellow-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-pen-to-square text-yellow-600" style="font-size: 15px"></i>
                                    </div>
                                    <div class="bg-red-100 w-fit h-fit p-4 rounded-full grid place-items-center">
                                        <i class="pi pi-trash text-red-600" style="font-size: 15px"></i>
                                    </div>
                                </div>
                            </div>
                          
                        </div>
                    </template>
                </Card>
            </div>
          
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <Button type="button" label="Cancel" severity="secondary" @click="closeDialog"></Button>
            <Button type="button" label="Save" @click="closeDialog"></Button>
        </div>
    </Dialog>
</template>
