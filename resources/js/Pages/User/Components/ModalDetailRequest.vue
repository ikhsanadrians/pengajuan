<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Card from 'primevue/card';
import axios from 'axios';
import { getStatusClass } from '../Helpers/userHelpers';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentTransactionId: {
        type: String,
        required: true,
    }
});

const emit = defineEmits(['update:currentVisibility']);
const visible = ref(props.currentVisibility);

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
    if (newValue) {
        fetchCurrentTransaction();
    }
});

const currentTransaction = ref({});

const fetchCurrentTransaction = async () => {
    try {
        const response = await axios.post('user/get-detail-pengajuan', {
            pengajuanId: props.currentTransactionId
        });
        currentTransaction.value = response.data.data;
        console.log(response.data.data)
    } catch (error) {
        console.error('Error fetching transaction:', error);
    }
}

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});

</script>

<template>
    <Dialog v-model:visible="visible" modal header="Detail Pengajuan" :style="{ width: '60rem', MaxHeight: '85%' }"
        @hide="closeDialog">
        <div v-if="currentTransaction" class="dialog-wrappers">
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
                                <label class="font-semibold" for="">Departemen Tujuan</label>
                                <p>{{ currentTransaction.departemen_id }}</p>
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
                                <div class="twrappers flex items-start justify-start gap-x-3">
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
                            </template>
                        </Card>
                    </div>
                </div>
            </div>

        </div>
        <div class="tools mt-4 flex gap-3">
            <Button icon="pi pi-times" label="Batalkan Pengajuan" severity="danger" outlined rounded />
            <Button icon="pi pi-print" label="Cetak" severity="success" rounded />
        </div>
    </Dialog>
</template>
