<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Navbar from '@/Components/Navbar.vue';
import Chart from '@/Components/Chart.vue';
import TableUser from './Components/TableUser.vue';
import AddRequestBtn from '@/Pages/User/Components/AddRequestBtn.vue';
import ModalDialog from './Components/ModalDialog.vue';
import { ref, watch } from 'vue';
import Toast from 'primevue/toast';
import ModalDetailRequest from './Components/ModalDetailRequest.vue';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import axios from 'axios';
import { useToast } from 'primevue/usetoast';

const confirm = useConfirm();
const toast = useToast();
const page = usePage();
const userData = page.props.auth.user;

const currentTransactions = ref([]);
const isFiltered = ref(false);

const props = defineProps({
    barangs: {
        type: Array,
        default: () => [],
    },
    departements: {
        type: Array,
        default: () => [],
    },
    transaksis: {
        type: Array,
        default: () => []
    },
    statuses: {
        type: Array,
        default: () => []
    },
    satuans: {
        type: Array,
        default: () => []
    },
    requestCount: {
        type: Number,
        default: 0
    },
    ditinjauCount: {
        type: Number,
        default: 0
    },
    approvedCount: {
        type: Number,
        default: 0
    },
    ditolakCount: {
        type: Number,
        default: 0
    }
});

watch(() => props.transaksis, (newValue) => {
    if (!isFiltered.value) {
        currentTransactions.value = newValue;
    }
}, { immediate: true });

const handleFilterChange = async (filters) => {
    const areAllFiltersEmpty = Object.values(filters).every(value => !value);

    if (areAllFiltersEmpty) {
        isFiltered.value = false;
        currentTransactions.value = props.transaksis;
        return;
    }

    try {
        isFiltered.value = true;
        const response = await axios.post('/user/filter-pengajuan', { params: filters });
        currentTransactions.value = response.data.transaksis;
    } catch (error) {
        loadToastMessage('error', 'Error', 'Gagal mengambil data filter.');
        currentTransactions.value = props.transaksis;
        isFiltered.value = false;
    }
};

const modalVisibility = ref(false);
const modalVisibilityDetailRequest = ref(false);
const currentPengajuanId = ref(null);
const modalVisibilityConfirmationDelete = ref(false);

const handleBtn = (typeHandle) => {
    typeHandle == "REQ" ? modalVisibility.value = true : typeHandle == "DETAIL" ? modalVisibilityRequest.value = true : null;
};

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

watch(modalVisibilityConfirmationDelete, (newValue) => {
    if (newValue) {
        showConfirmationDialog();
        modalVisibilityConfirmationDelete.value = false;
    }
});

const deletePengajuan = async () => {
    try {
        const response = await axios.delete('/user/delete-pengajuan', { data: { pengajuanId: currentPengajuanId.value } });
        if (response) {
            modalVisibilityDetailRequest.value = false;
            loadToastMessage('success', 'Info', 'Berhasil Menghapus Pengajuan');
            await router.reload();
        }
    } catch (error) {
        loadToastMessage('error', 'Info', 'Gagal Menghapus Pengajuan');
    }
};

const showConfirmationDialog = () => {
    confirm.require({
        message: 'Apakah Anda Yakin Ingin Menghapus?',
        header: 'Konfirmasi',
        icon: 'pi pi-info-circle',
        acceptLabel: 'Yes',
        rejectLabel: 'Cancel',
        accept: deletePengajuan,
        reject: () => {
            toast.add({ severity: 'warn', summary: 'Cancelled', detail: 'Request not cancelled', life: 3000 });
        }
    });
};
</script>

<template>
    <Navbar />
    <div class="container mx-auto py-5 px-20">
        <Chart :username="userData.username" :pengajuanCount="props.requestCount" :ditinjauCount="props.ditinjauCount"
            :approveCount="props.approvedCount" :ditolakCount="props.ditolakCount" />
        <TableUser :pilihanStatus="statuses" :transaksidata="currentTransactions" @update:filter="handleFilterChange"
            :isCurrentDetailRequestModalOpen="modalVisibilityDetailRequest"
            @update:isCurrentDetailRequestModalOpen="modalVisibilityDetailRequest = $event"
            :currentPengajuanId="currentPengajuanId" @update:currentPengajuanId="currentPengajuanId = $event" />
    </div>
    <AddRequestBtn @click="handleBtn('REQ')" />
    <ModalDialog v-model:currentVisibility="modalVisibility" :barangdata="barangs" :departementData="departements"
        :toastMessage="loadToastMessage" :satuanData="satuans" />
    <ModalDetailRequest :currentTransactionId="currentPengajuanId" :currentVisibility="modalVisibilityDetailRequest"
        @update:currentVisibility="modalVisibilityDetailRequest = $event"
        :currentVisibilityConfirmationDelete="modalVisibilityConfirmationDelete"
        @update:currentVisibilityConfirmationDelete="modalVisibilityConfirmationDelete = $event" />
    <Toast position="bottom-right" group="br" />
    <ConfirmDialog position="top" />
</template>
