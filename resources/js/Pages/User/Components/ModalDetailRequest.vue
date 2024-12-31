<script setup>
import { ref, watch, defineEmits, onMounted } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Card from 'primevue/card';
import axios from 'axios';
import { getStatusClass, formatRupiah } from '../Helpers/userHelpers';



const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentTransactionId: {
        type: String,
        required: true,
    },
    currentVisibilityConfirmationDelete: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['update:currentVisibility', 'update:currentVisibilityConfirmationDelete']);
const visible = ref(props.currentVisibility);
const loading = ref(false);
const currentTransaction = ref({});

// Watch visibility to load data
watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
    if (newValue) {
        fetchCurrentTransaction();
    }
});

// Fetch transaction details
const fetchCurrentTransaction = async () => {
    loading.value = true;
    try {
        const response = await axios.post('user/get-detail-pengajuan', {
            pengajuanId: props.currentTransactionId,
        });
        currentTransaction.value = response.data.data;
    } catch (error) {
        console.error('Error fetching transaction:', error);
    } finally {
        loading.value = false;
    }
};

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

const triggerDeleteConfirmation = () => {
    emit('update:currentVisibilityConfirmationDelete', true);
};

const cetakDetailPengajuan = (code) => {
    window.open(route('cetakan-detail-pengajuan-user', code), '_blank');
};

const openImagePreview = (imageUrl) => {
    selectedImage.value = imageUrl;
    imagePreviewVisible.value = true;
};

const imagePreviewVisible = ref(false);
const selectedImage = ref('');

</script>

<template>
    <Dialog v-model:visible="visible" modal header="Detail Pengajuan" :style="{ width: '60rem', MaxHeight: '85%' }"
        @hide="closeDialog">
        <div v-if="loading" class="flex justify-center items-center h-40">
            <i class="pi pi-spin pi-spinner text-4xl"></i>
        </div>
        <div v-else-if="currentTransaction" class="dialog-wrappers">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="column">
                                <label class="font-semibold">Kode</label>
                                <p>{{ currentTransaction.unique_id }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold">Departemen Tujuan</label>
                                <p>{{ currentTransaction.namadepartement }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold">Kuantitas</label>
                                <p>{{ currentTransaction.quantity }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold">Estimasi</label>
                                <p>{{ currentTransaction.estimasi || 'Belum Diketahui' }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold">Tgl Pengajuan</label>
                                <p>{{ currentTransaction.created_at }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold">Status</label>
                                <p class="px-3 py-[.8px] rounded-full"
                                    :class="getStatusClass(currentTransaction.status_name)">
                                    {{ currentTransaction.status_name }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="column mt-2">
                <label class="font-semibold">Keterangan</label>
                <p>{{ currentTransaction.keterangan }}</p>
            </div>

            <div class="barangs mt-4">
                <label for="username" class="font-semibold w-full">Daftar Barang Diajukan</label>
                <div class="w-full max-h-[14rem] overflow-y-scroll scrollbar-thin mt-2">
                    <div class="card-wrappers flex flex-col gap-y-3 my-4 pr-4">
                        <div v-if="!currentTransaction.transaksi?.length"
                            class="emptybarang flex flex-col items-center">
                            <p>Tidak ada barang yang diajukan</p>
                        </div>
                        <Card v-for="(transaksi, index) in currentTransaction.transaksi" :key="index"
                            class="border-[1.3px]">
                            <template #content>
                                <div class="twrappers flex items-start justify-between gap-x-3">
                                    <div class="twrapper-left flex gap-x-3">
                                        <div v-if="!transaksi.gambar_pendukung"
                                            class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                            <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                                        </div>
                                        <div v-else class="gambar-pendukung h-24 w-24 relative group">
                                            <img :src="transaksi.gambar_pendukung" alt="Gambar Pendukung"
                                                class="zoomable h-full w-full object-cover rounded-md group-hover:brightness-75" />
                                            <i @click="openImagePreview(transaksi.gambar_pendukung)"
                                                class="pi pi-eye !hidden group-hover:!block text-white !text-2xl cursor-pointer"
                                                style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"></i>
                                        </div>
                                        <div class="tblnbtn flex justify-between items-start w-full">
                                            <table class="w-fit">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-left">Nama Barang</th>
                                                        <td class="text-left px-3">{{ transaksi.namabarang }}</td>
                                                        <div v-if="transaksi.harga_satuan" class="satuan-price">
                                                            <th class="text-left">Harga Satuan</th>
                                                            <td class="text-left px-3">{{
                                                                formatRupiah(transaksi.harga_satuan) }}</td>
                                                        </div>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Jumlah</th>
                                                        <td class="text-left px-3">{{ ` ${transaksi.quantity}
                                                            ${transaksi.satuan}` }}</td>
                                                        <div v-if="transaksi.harga_satuan" class="satuan-price">
                                                            <th class="text-left">Harga Total</th>
                                                            <td class="text-left px-3">{{
                                                                formatRupiah(transaksi.harga_total) }}</td>
                                                        </div>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Keterangan</th>
                                                        <td class="text-left px-3">{{ transaksi.keterangan }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="twrapper-right">
                                        <div :class="getStatusClass(transaksi.status)"
                                            class="rounded-full px-3 py-[.8px]">
                                            {{ transaksi.status }}
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
        </div>
        <div class="tools mt-4 flex gap-3">
            <Button icon="pi pi-times" @click="triggerDeleteConfirmation" label="Batalkan Pengajuan" severity="danger"
                outlined rounded />
            <Button icon="pi pi-print" @click="cetakDetailPengajuan(currentTransaction.unique_id)" label="Cetak"
                severity="success" rounded />
        </div>
        <Dialog v-model:visible="imagePreviewVisible" modal header="Image Preview" :style="{ width: '50rem' }"
            @hide="imagePreviewVisible = false">
            <img :src="selectedImage" alt="Preview" class="w-full h-auto" />
        </Dialog>
    </Dialog>
</template>
