<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Card from 'primevue/card';
import EmptyBarang from '../../../../../public/ilustration/emptybarang.svg';
import { router } from '@inertiajs/vue3';
import Pusher from 'pusher-js';
import FileUpload from 'primevue/fileupload';
import ToggleSwitch from 'primevue/toggleswitch';


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
    },
    satuanData: {
        type: Array
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
const jumlahBarang = ref(null);
const keteranganBarang = ref('');
const daftarBarang = ref([]);
const editIndex = ref(null);
const isSubmitting = ref(false);
const selectedSatuan = ref(null);
const src = ref(null);
const checked = ref(false);
const selectedFile = ref(null);



const onFileSelect = (event) => {
    const file = event.files[0];
    if (file) {
        selectedFile.value = file; // Simpan file yang dipilih
        src.value = URL.createObjectURL(file);
        console.log('File selected:', file); // Debug log
    } else {
        selectedFile.value = null;
        src.value = null;
    }
}


const tambahBarang = () => {
    if ((checked.value && selectedBarang.value && jumlahBarang.value && keteranganBarang.value && selectedSatuan.value) ||
        (!checked.value && selectedBarang.value?.id && jumlahBarang.value && keteranganBarang.value && selectedSatuan.value)) {

        // Membuat objek barang baru
        const newBarang = {
            barang_id: checked.value ? null : selectedBarang.value.id,
            namabarang: checked.value ? selectedBarang.value : selectedBarang.value.namabarang,
            jumlah: jumlahBarang.value,
            keterangan: keteranganBarang.value,
            satuan: selectedSatuan.value,
            has_image: !!selectedFile.value, // Set true jika ada file
            imageFile: selectedFile.value,   // Simpan file untuk upload
            image: src.value                 // URL untuk preview
        };



        if (editIndex.value !== null) {
            daftarBarang.value[editIndex.value] = newBarang;
            editIndex.value = null;
        } else {
            daftarBarang.value.unshift(newBarang);
        }

        // Reset semua input
        selectedBarang.value = null;
        jumlahBarang.value = null;
        keteranganBarang.value = '';
        selectedSatuan.value = null;
        src.value = null;
        selectedFile.value = null;
    }
};





const editBarang = (index) => {
    const barang = daftarBarang.value[index];
    // Jika barang adalah manual (barang_id null), set selectedBarang dengan nama manual
    if (barang.barang_id === null) {
        selectedBarang.value = barang.namabarang; // Ini untuk barang manual
    } else {
        selectedBarang.value = props.barangdata.find(b => b.namabarang === barang.namabarang);
    }
    jumlahBarang.value = barang.jumlah;
    keteranganBarang.value = barang.keterangan;
    selectedSatuan.value = barang.satuan;
    editIndex.value = index;
};


const deleteBarang = (index) => {
    daftarBarang.value.splice(index, 1);
};

const isTambahBarangDisabled = ref(true);

watch([keteranganPengajuan, selectedDepartement, selectedSatuan], ([newKeterangan, newDepartement, newSatuan]) => {
    isTambahBarangDisabled.value = !newKeterangan || !newDepartement || !newSatuan;
});

const simpanPengajuan = async () => {
    if (isSubmitting.value) return;

    try {
        isSubmitting.value = true;

        if (!selectedDepartement.value?.id || !keteranganPengajuan.value || !daftarBarang.value.length) {
            props.toastMessage('error', 'Info', 'Mohon lengkapi semua data yang diperlukan');
            return;
        }

        const formData = new FormData();
        formData.append('departement', selectedDepartement.value.id);
        formData.append('keterangan', keteranganPengajuan.value);

        // Prepare barangDiajukan data and handle files
        const barangDiajukan = daftarBarang.value.map((barang, index) => {
            // If this barang has an image, append it to FormData
            if (barang.imageFile) {
                formData.append(`images[]`, barang.imageFile);

                // Return barang data with image info
                return {
                    barang_id: barang.barang_id,
                    quantity: barang.jumlah,
                    keterangan: barang.keterangan,
                    status_id: 1,
                    satuan_id: barang.satuan.id,
                    nama_manual: barang.barang_id === null ? barang.namabarang : null,
                    has_image: true,
                    image_index: index
                };
            }

            // Return barang data without image
            return {
                barang_id: barang.barang_id,
                quantity: barang.jumlah,
                keterangan: barang.keterangan,
                satuan_id: barang.satuan.id,
                status_id: 1,
                nama_manual: barang.barang_id === null ? barang.namabarang : null,
                has_image: false
            };
        });

        // Debug log
        console.log('Sending barangDiajukan:', barangDiajukan);
        console.log('FormData entries:');
        for (let pair of formData.entries()) {
            console.log(pair[0], pair[1]);
        }

        formData.append('barangDiajukan', JSON.stringify(barangDiajukan));

        console.log(formData);

        await router.post('user/simpan-pengajuan-user', formData, {
            forceFormData: true,
            preserveScroll: true,
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onSuccess: () => {
                props.toastMessage('success', 'Info', 'Berhasil Menambahkan Pengajuan');
                resetAll();
                closeDialog();
            },
            onError: (errors) => {
                console.error('Upload error:', errors);
                const errorMessage = Object.values(errors)[0] || 'Gagal Menambahkan Pengajuan';
                props.toastMessage('error', 'Info', errorMessage);
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });

    } catch (error) {
        console.error('Error submitting form:', error);
        props.toastMessage('error', 'Info', 'Terjadi kesalahan saat menyimpan data');
        isSubmitting.value = false;
    }
};

const resetAll = () => {
    selectedDepartement.value = null;
    keteranganPengajuan.value = '';
    daftarBarang.value = [];
    selectedBarang.value = null;
    jumlahBarang.value = null;
    keteranganBarang.value = '';
    selectedSatuan.value = null;
    editIndex.value = null;
};
</script>
<template>
    <Dialog v-model:visible="visible" modal header="Buat Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog"
        class="overflow-thin">
        <div class="flex items-center gap-3 mb-3 flex-col">
            <label for="keterangan-pengajuan" class="font-semibold w-full">Keterangan Pengajuan</label>
            <InputText id="keterangan-pengajuan" class="flex-auto w-full" autocomplete="off"
                placeholder="Masukan Keterangan Disini" v-model="keteranganPengajuan" />
        </div>
        <div class="flex items-center gap-3 mb-3 flex-col w-full">
            <label for="departement" class="font-semibold w-full">Departemen</label>
            <Select show-clear v-model="selectedDepartement" :options="departementData" :filter="true"
                :filter-fields="['namadepartemen']" optionLabel="namadepartemen" placeholder="Pilih Departement"
                class="w-full">
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
        <div class="flex items-start gap-3 mb-3 flex-col bg-gray-100 p-2">
            <label for="username" class="font-semibold w-full">Tambah Barang</label>
            <div class="barangbaru flex items-center gap-2">
                <label for="">Barang Tidak Ada Di Pilihan?</label>
                <ToggleSwitch v-model="checked" />
            </div>
            <div class="flex justify-between w-full gap-x-2">
                <div v-if="!checked" class="w-full md:w-[25rem]">
                    <Select show-clear class="w-full" v-model="selectedBarang" :options="barangdata" :filter="true"
                        :filter-fields="['namabarang']" optionLabel="namabarang" placeholder="Pilih Barang">
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
                </div>
                <div v-else class="w-full md:w-[30rem]">
                    <InputText class="w-full" v-model="selectedBarang" placeholder="Masukkan Nama Barang" />
                </div>
                <InputNumber :min="1" v-model="jumlahBarang" placeholder="QTY Barang" />
                <Select show-clear v-model="selectedSatuan" :options="satuanData" :filter="true"
                    :filter-fields="['namasatuan']" optionLabel="namasatuan" placeholder="Pilih Satuan"
                    class="w-full md:w-[20rem]">
                    <template #value="slotProps">
                        <div v-if="slotProps.value" class="flex items-center">
                            <div>{{ slotProps.value.namasatuan }}</div>
                        </div>
                        <span v-else>
                            {{ slotProps.placeholder }}
                        </span>
                    </template>
                    <template #option="slotProps">
                        <div class="flex items-center">
                            <div>{{ slotProps.option.namasatuan }}</div>
                        </div>
                    </template>
                </Select>
                <InputText class="w-[35%]" v-model="keteranganBarang" placeholder="Keterangan Per Barang" />
                <Button rounded class="!p-5" :icon="editIndex == null ? 'pi pi-plus' : 'pi pi-pencil'" aria-label="Save"
                    @click="tambahBarang"
                    :disabled="isTambahBarangDisabled || !selectedBarang || !jumlahBarang || !keteranganBarang || !selectedSatuan" />
            </div>

            <div class="upload-gambar flex flex-col justify-start items-start">
                <label for="username" class="font-semibold w-full">File / Gambar Pendukung Barang ( Opsional )</label>
                <div class="flex gap-x-4">
                    <FileUpload mode="basic" @select="onFileSelect" customUpload :auto="true" severity="secondary"
                        class="p-button-outlined mt-1" accept="image/*" />
                    <div v-if="src" class="gambar h-20 w-28 relative overflow-hidden">
                        <img v-if="src" :src="src" alt="Preview Gambar"
                            class="shadow-md rounded-xl w-full h-full sm:w-64 object-cover"
                            style="filter: grayscale(100%)" />
                    </div>
                </div>


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
                            <div v-if="barang.image" class="gambar h-20 w-28 relative overflow-hidden">
                                <img :src="barang.image" alt="Gambar Barang"
                                    class="shadow-md rounded-xl w-full h-full sm:w-64" />
                            </div>
                            <div class="tblnbtn flex justify-between items-start w-full">
                                <table class="w-fit">
                                    <tbody>
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
                                    </tbody>

                                </table>
                                <div class="btn-action flex items-center gap-x-2">
                                    <div id="edit-barang"
                                        class="bg-yellow-100 hover:bg-yellow-200 hover:scale-105 duration-200  w-fit h-fit p-4 rounded-full grid place-items-center"
                                        @click="editBarang(index)">
                                        <i class="pi pi-pen-to-square text-yellow-600" style="font-size: 15px"></i>
                                    </div>
                                    <div id="delete-barang"
                                        class="bg-red-100 hover:bg-red-200 hover:scale-105 duration-200 w-fit h-fit p-4 rounded-full grid place-items-center"
                                        @click="deleteBarang(index)">
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
            <Button type="button" label="Batal" severity="secondary" @click="closeDialog" :disabled="isSubmitting">
            </Button>
            <Button icon="pi pi-save" type="button" label="Simpan" @click="simpanPengajuan" :loading="isSubmitting"
                :disabled="isSubmitting || !selectedDepartement || !keteranganPengajuan || daftarBarang.length === 0">
            </Button>
        </div>
    </Dialog>
</template>
