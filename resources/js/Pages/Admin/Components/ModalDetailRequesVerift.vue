<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Card from 'primevue/card';
import axios from 'axios';
import InputText from 'primevue/inputtext';
import { getStatusClass } from '../Helpers/adminHelpers';
import IconField from 'primevue/iconfield';
import InputIcon from 'primevue/inputicon';
import DatePicker from 'primevue/datepicker';
import { format } from 'date-fns';
import { router } from '@inertiajs/vue3';
import { checkIfVerifBtn } from '../Helpers/adminHelpers';



const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentTransactionId: {
        type: String,
    },
    currentVisibilityConfirmationReject: {
        type: Boolean,
    },
    currentVisibilityConfirmationRejectPengajuan: {
        type: Boolean,
    },
    currentBarangId: {
        type: String,
        required: true,
    },
    isTransactionLoaded: {
        type: String,
        required: true
    },
    toastMessage: {
        type: Function
    }
});

const emit = defineEmits(['update:currentVisibility', 'update:currentVisibilityConfirmationReject', 'update:currentBarangId']);
const visible = ref(props.currentVisibility);
const loading = ref(false);

const currentEstimationDate = ref(null)
const keteranganVerifikasi = ref(null)

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
    if (newValue) {
        fetchCurrentTransaction();
    }
});


const currentTransaction = ref({});

const fetchCurrentTransaction = async () => {
    loading.value = true;
    try {
        const response = await axios.post('admin/get-detail-pengajuan-admin', {
            pengajuanId: props.currentTransactionId
        });
        currentTransaction.value = response.data.data;
    } catch (error) {
        console.error('Error fetching transaction:', error);
    } finally {
        loading.value = false;
    }
}

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

const triggerConfirmationReject = (currentBarangId) => {
    let transaction = currentTransaction.value.transaksi;

    const shouldEmit = !transaction.some((transaction) => {
        return transaction.id === currentBarangId && transaction.status_id == 4;
    });

    if (shouldEmit) {
        emit('update:currentVisibilityConfirmationReject', true);
        emit('update:currentBarangId', currentBarangId);
    }
};


const triggerConfirmationRejectPengajuan = (currentPengajuanId) => {

    emit('update:currentVisibilityConfirmationRejectPengajuan', true);
    emit('update:currentTransactionId', currentTransactionId);

};



const simpanVerifPengajuan = async () => {

    if (!keteranganVerifikasi.value) {
        props.toastMessage('warn', 'Peringatan', 'Keterangan Verifikasi harus diisi.');
        return;
    }

    if (!currentEstimationDate.value) {
        props.toastMessage('warn', 'Peringatan', 'Estimasi tanggal harus diisi.');
        return;
    }

    const formattedEstimationDate = format(new Date(currentEstimationDate.value), 'yyyy-MM-dd HH:mm:ss');


    try {
        const response = await axios.post('admin/simpan-verif-pengajuan', {
            pengajuanId: props.currentTransactionId,
            keterangan: keteranganVerifikasi.value,
            estimasi: formattedEstimationDate

        });


        if (response.data.success) {
            props.toastMessage('success', 'Info', 'Berhasil Melakukan Verifikasi');
            currentTransaction.value = response.data.data;
            router.reload();
            closeDialog();
            currentEstimationDate.value = null
            keteranganVerifikasi.value = null
        }


    } catch (error) {
        props.toastMessage('error', 'Info', 'Something Went Wrong!');
    } finally {
        loading.value = false;
    }
}

const cetakDetailPengajuan = (code) => {
    window.open(route('cetakan-detail-pengajuan-user', code), '_blank');
}


watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});

</script>

<template>
    <Dialog v-model:visible="visible" modal header="Verifikasi Detail Pengajuan"
        :style="{ width: '60rem', MaxHeight: '90%' }" @hide="closeDialog">
        <div v-if="loading" class="flex justify-center items-center h-40">
            <i class="pi pi-spin pi-spinner text-4xl"></i>
        </div>
        <div v-else-if="currentTransaction" class="dialog-wrappers">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <div class="column">
                                <label class="font-semibold" for="">Kode</label>
                                <p>{{ currentTransaction.unique_id }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold" for="">Dept Tujuan</label>
                                <p>{{ currentTransaction.namadepartement }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold" for="">Kuantitas</label>
                                <p>{{ currentTransaction.quantity }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold" for="">Estimasi</label>
                                <p>{{ currentTransaction.estimasi || 'Belum Diketahui' }}</p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold" for="">Tgl Pengajuan</label>
                                <p>
                                    {{ currentTransaction.created_at }}
                                </p>
                            </div>
                        </td>
                        <td class="pl-8">
                            <div class="column">
                                <label class="font-semibold" for="">Status</label>
                                <p class="px-3 py-[.8px] rounded-full"
                                    :class="getStatusClass(currentTransaction.status_name)">{{
                                        currentTransaction.status_name }}</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="column mt-2">
                <label class="font-semibold" for="">Keterangan</label>
                <p>{{ currentTransaction.keterangan }}</p>
            </div>
            <div class="column mt-2">
                <label class="font-semibold" for="">Diajukan Oleh</label>
                <p>{{ currentTransaction.user }}</p>
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
                                        <div
                                            class="icons-bg bg-emerald-100 w-fit h-fit p-4 grid place-items-center rounded-full">
                                            <i class="pi pi-box text-emerald-600" style="font-size: 20px"></i>
                                        </div>
                                        <div class="tblnbtn flex justify-between items-start w-full">
                                            <table class="w-fit">
                                                <tbody>
                                                    <tr>
                                                        <th class="text-left">Nama Barang</th>
                                                        <td class="text-left px-3">{{ transaksi.namabarang }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Jumlah</th>
                                                        <td class="text-left px-3">{{ transaksi.quantity }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-left">Keterangan</th>
                                                        <td class="text-left px-3">{{ transaksi.keterangan }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="twrapper-right flex flex-col justify-end items-end gap-y-2">
                                        <div :class="getStatusClass(transaksi.status)"
                                            class="rounded-full px-3 py-[.8px]">
                                            {{ transaksi.status }}
                                        </div>
                                        <div class="btn-group flex gap-1">
                                            <Button icon="pi pi-pencil" severity="info" rounded
                                                style="font-size: .8rem">

                                            </Button>
                                            <Button @click="triggerConfirmationReject(transaksi.id)" icon="pi pi-trash"
                                                severity="danger" rounded style="font-size: .8rem">
                                            </Button>

                                        </div>

                                    </div>

                                </div>
                            </template>
                        </Card>
                    </div>
                </div>
            </div>
            <div class="verif-input pt-4 grid grid-cols-2 gap-x-3">
                <div class="keterangan-tambahan flex flex-col w-full">
                    <label class="font-semibold mb-1" for="">Keterangan Verifikasi</label>
                    <IconField class="w-full">
                        <InputIcon class="pi pi-book" />

                        <InputText class="w-full" v-model="keteranganVerifikasi"
                            :value="currentTransaction.keterangan_approved ? currentTransaction.keterangan_approved : keteranganVerifikasi"
                            :disabled="currentTransaction.keterangan_approved ? true : false"
                            placeholder="Masukan Keterangan Verifikasi" />
                    </IconField>
                </div>
                <div class="tanggal-tambahan flex flex-col">
                    <label class="font-semibold mb-1" for="">Estimasi Barang Diterima</label>
                    <DatePicker v-model="currentEstimationDate"
                        :value="currentTransaction.estimasi ? new Date(currentTransaction.estimasi) : currentEstimationDate"
                        dateFormat="d MM yy" showTime hourFormat="24" placeholder="Masukan Estimasi Tanggal" />
                </div>
            </div>
        </div>
        <div class="tools mt-4 flex justify-between">
            <div class="button-left flex gap-3">
                <Button icon="pi pi-times" @click="triggerConfirmationRejectPengajuan(currentTransaction.unique_id)"
                    label="Tolak Pengajuan" severity="danger" outlined rounded />
                <Button icon="pi pi-print" @click="cetakDetailPengajuan(currentTransaction.unique_id)" label="Cetak"
                    severity="info" rounded />
            </div>
            <div class="button-right">
                <Button
                    :disabled="checkIfVerifBtn(currentTransaction.status_name).btnDisabled === 'true' ? true : false"
                    :icon="checkIfVerifBtn(currentTransaction.status_name).icon" @click="simpanVerifPengajuan()"
                    :label="checkIfVerifBtn(currentTransaction.status_name).text" severity="success" rounded />
            </div>

        </div>
    </Dialog>
</template>
