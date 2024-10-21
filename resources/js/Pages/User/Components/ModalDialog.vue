<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import Button from 'primevue/button';
import Card from 'primevue/card';
import EmptyBarang from '../../../../../public/ilustration/emptybarang.svg';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    barangdata: {
        type: Array,
    },
    departementData: {
        type: Array,
    },
    toastMessage: {
        type: Function
    }
});

const emit = defineEmits(['update:currentVisibility']);

const visible = ref(props.currentVisibility);

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

const selectedBarang = ref(null);
const selectedDepartement = ref(null);
const keteranganPengajuan = ref('');
const jumlahBarang = ref('');
const keteranganBarang = ref('');
const daftarBarang = ref([]);
const editIndex = ref(null);


// Fungsi untuk menambah barang ke daftar
const tambahBarang = () => {
    if (selectedBarang.value && jumlahBarang.value && keteranganBarang.value) {
        if (editIndex.value !== null) {
            // Jika sedang dalam mode edit, update barang di index tertentu
            daftarBarang.value[editIndex.value] = {
                namabarang: selectedBarang.value.namabarang,
                jumlah: jumlahBarang.value,
                keterangan: keteranganBarang.value
            };
            editIndex.value = null; // Reset index setelah update
        } else {
            // Jika tidak dalam mode edit, tambahkan barang baru ke daftar
            daftarBarang.value.unshift({
                namabarang: selectedBarang.value.namabarang,
                jumlah: jumlahBarang.value,
                keterangan: keteranganBarang.value
            });
        }

        // Reset form input setelah menambah atau mengedit barang

        selectedBarang.value = null;
        jumlahBarang.value = '';
        keteranganBarang.value = '';
    }
};

// Fungsi untuk mengedit barang yang dipilih
const editBarang = (index) => {
    const barang = daftarBarang.value[index];
    selectedBarang.value = props.barangdata.find(b => b.namabarang === barang.namabarang);
    jumlahBarang.value = barang.jumlah;
    keteranganBarang.value = barang.keterangan;
    editIndex.value = index; // Set index barang yang sedang diedit
};

// Fungsi untuk menghapus barang dari daftar
const deleteBarang = (index) => {
    daftarBarang.value.splice(index, 1); // Hapus barang berdasarkan index
};

const isTambahBarangDisabled = ref(true);

watch([keteranganPengajuan, selectedDepartement], ([newKeterangan, newDepartement]) => {
    isTambahBarangDisabled.value = !newKeterangan || !newDepartement;
});

const simpanPengajuan = () => {
    const pengajuan = {
        departement: selectedDepartement.value,
        keteranganPengajuan: keteranganPengajuan.value,
        jumlahBarang: jumlahBarang.value,
        barangDiajukan: daftarBarang.value
    };

    props.toastMessage('success', 'Info', 'Berhasil Menambahkan Pengajuan')


    closeDialog();
};
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Buat Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog" class="overflow-thin">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="keterangan-pengajuan" class="font-semibold w-full">Keterangan</label>
            <InputText id="keterangan-pengajuan" class="flex-auto w-full" autocomplete="off" placeholder="Masukan Keterangan Disini" v-model="keteranganPengajuan"/>
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col w-full">
            <label for="departement" class="font-semibold w-full">Departemen</label>
            <Select v-model="selectedDepartement" :options="departementData" :filter="true" :filter-fields="['namadepartemen']" optionLabel="namadepartemen" placeholder="Pilih Departement" class="w-full">
                <template #value="slotProps">
                    <div v-if="slotProps.value" class="flex items-center">
                        <div>{{ slotProps.value.namadepartemen }}</div>
                    </div>
                    <span v-else>
                        {{ slotProps.placeholder }}
                    </span>
                </template>
                <template #option="slotProps">
                    <div class="flex items-center">
                        <div>{{ slotProps.option.namadepartemen }}</div>
                    </div>
                </template>
            </Select>
        </div>
        <div class="flex items-start gap-3 mb-3 flex-col">
            <label for="username" class="font-semibold w-full">Tambah Barang</label>
            <div class="flex justify-start gap-x-3 w-full">
                <Select v-model="selectedBarang" :options="barangdata" :filter="true" :filter-fields="['namabarang']" optionLabel="namabarang" placeholder="Pilih Barang" class="w-full md:w-[20rem]">
                    <template #value="slotProps">
                        <div v-if="slotProps.value" class="flex items-center">
                            <div>{{ slotProps.value.namabarang }}</div>
                        </div>
                        <span v-else>
                            {{ slotProps.placeholder }}
                        </span>
                    </template>
                    <template #option="slotProps">
                        <div class="flex items-center">
                            <div>{{ slotProps.option.namabarang }}</div>
                        </div>
                    </template>
                </Select>
                <IconField>
                    <InputIcon class="pi pi-box" />
                    <InputText class="w-40" v-model="jumlahBarang" placeholder="Jumlah Barang" />
                </IconField>
                <InputText class="w-80" v-model="keteranganBarang" placeholder="Keterangan Per Barang" />
                <Button :icon="editIndex == null ? 'pi pi-plus' : 'pi pi-pencil'" :label="editIndex == null ? 'Tambah' : 'Update'" aria-label="Save" @click="tambahBarang" :disabled="isTambahBarangDisabled || !selectedBarang || !jumlahBarang || !keteranganBarang" />
            </div>
        </div>

        <label for="username" class="font-semibold w-full">Daftar Barang Diajukan</label>
        <div class="w-full h-[10rem] overflow-y-scroll scrollbar-thin">
            <div class="card-wrappers flex flex-col gap-y-3 my-4 pr-4">
                <div v-if="daftarBarang.length === 0" class="emptybarang flex flex-col items-center">
                    <img :src="EmptyBarang" alt="emptybarang" class="h-28">
                    <p>Anda belum menambahkan barang</p>
                </div>
                <Card v-for="(barang, index) in daftarBarang" :key="index" class="border-[1.3px]">
                    <template #content>
                        <div class="twrappers flex items-start justify-start gap-x-3">
                            <div class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                            </div>
                            <div class="tblnbtn flex justify-between items-start w-full">
                                <table class="w-fit">
                                    <tr>
                                        <th class="text-left">Nama Barang</th>
                                        <td class="text-left px-3">{{ barang.namabarang }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Jumlah</th>
                                        <td class="text-left px-3">{{ barang.jumlah }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-left">Keterangan</th>
                                        <td class="text-left px-3">{{ barang.keterangan }}</td>
                                    </tr>
                                </table>
                                <div class="btn-action flex items-center gap-x-2">
                                    <div id="edit-barang" class="bg-yellow-100 hover:bg-yellow-200 hover:scale-105 duration-200  w-fit h-fit p-4 rounded-full grid place-items-center" @click="editBarang(index)">
                                        <i class="pi pi-pen-to-square text-yellow-600" style="font-size: 15px"></i>
                                    </div>
                                    <div id="delete-barang" class="bg-red-100 hover:bg-red-200 hover:scale-105 duration-200 w-fit h-fit p-4 rounded-full grid place-items-center" @click="deleteBarang(index)">
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
            <Button type="button" label="Batal" severity="secondary" @click="closeDialog"></Button>
            <Button icon="pi pi-save" type="button" label="Simpan" @click="simpanPengajuan"></Button>
        </div>
    </Dialog>
</template>
