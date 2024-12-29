<script setup>
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Chart from '@/Components/Chart.vue';
import TableAdmin from './Components/TableAdmin.vue';
import { ref, watch } from 'vue';
import Toast from 'primevue/toast';
import ModalDetailRequestVerif from './Components/ModalDetailRequesVerift.vue';
import { useConfirm } from "primevue/useconfirm";
import axios from 'axios';
import { useToast } from 'primevue/usetoast';
import ConfirmationRejectPerBarang from './Components/ConfirmationRejectPerBarang.vue';
import ConfirmationRejectPerPengajuan from './Components/ConfirmationRejectPerPengajuan.vue';


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
    userRoles: {
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

const rejectionData = ref(null);


function handleRejectionSuccess(data) {
    rejectionData.value = data;
}

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
        const response = await axios.post('/admin/filter-pengajuan', { params: filters });
        currentTransactions.value = response.data.transaksis;
    } catch (error) {
        loadToastMessage('error', 'Error', 'Gagal mengambil data filter.');
        currentTransactions.value = props.transaksis;
        isFiltered.value = false;
    }
};



const modalVisibilityDetailRequest = ref(false);
const currentPengajuanId = ref(null);
const currentBarangId = ref(null);
const modalVisibilityConfirmationReject = ref(false);
const modalVisibilityConfirmationRejectPengajuan = ref(false);

const handleBtn = (typeHandle) => {
    typeHandle == "REQ" ? modalVisibility.value = true : typeHandle == "DETAIL" ? modalVisibilityRequest.value = true : null;
};

const loadToastMessage = (toastSeverity, toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};






</script>

<template>
    <Navbar />
    <div class="container mx-auto py-5 px-20">
        <Chart :username="userData.username" :pengajuanCount="props.requestCount" :ditinjauCount="props.ditinjauCount"
            :approveCount="props.approvedCount" :ditolakCount="props.ditolakCount" />

        <TableAdmin :pilihanStatus="statuses" :transaksidata="currentTransactions" @update:filter="handleFilterChange"
            :isCurrentDetailRequestModalOpen="modalVisibilityDetailRequest"
            @update:isCurrentDetailRequestModalOpen="modalVisibilityDetailRequest = $event"
            :currentPengajuanId="currentPengajuanId" @update:currentPengajuanId="currentPengajuanId = $event" />
    </div>
    <ConfirmationRejectPerBarang :currentVisibility="modalVisibilityConfirmationReject"
        @update:currentVisibility="modalVisibilityConfirmationReject = $event" :currentBarangId="currentBarangId"
        @update:currentBarangId="currentBarangId = $event" :toastMessage="loadToastMessage"
        @rejectionSuccess="handleRejectionSuccess" />


    <ConfirmationRejectPerPengajuan :currentVisibility="modalVisibilityConfirmationRejectPengajuan"
        @update:currentVisibility="modalVisibilityConfirmationRejectPengajuan = $event"
        :currentPengajuanId="currentPengajuanId" @update:currentPengajuanId="currentPengajuanId = $event" />
    <ModalDetailRequestVerif :currentTransactionId="currentPengajuanId"
        :currentVisibility="modalVisibilityDetailRequest"
        @update:currentVisibility="modalVisibilityDetailRequest = $event"
        :currentVisibilityConfirmationReject="modalVisibilityConfirmationReject"
        @update:currentVisibilityConfirmationReject="modalVisibilityConfirmationReject = $event"
        :currentVisibiltyConfirmationRejectPengajuan="modalVisibilityConfirmationRejectPengajuan"
        @update:currentVisibilityConfirmationRejectPengajuan="modalVisibilityConfirmationRejectPengajuan = $event"
        :currentBarangId="currentBarangId" @update:currentBarangId="currentBarangId = $event"
        :rejectionData="rejectionData" @update:rejectionData="rejectionData = $event" :toastMessage="loadToastMessage"
        ref="modalDetailRequestVerif" />
    <Toast position="bottom-right" group="br" />
</template>
