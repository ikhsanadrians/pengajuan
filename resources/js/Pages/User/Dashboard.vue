<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Navbar from '@/Components/Navbar.vue';
import Chart from '@/Components/Chart.vue';
import TableUser from './Components/TableUser.vue';
import AddRequestBtn from '@/Pages/User/Components/AddRequestBtn.vue';
import ModalDialog from './Components/ModalDialog.vue';
import { ref } from 'vue';
import Toast from 'primevue/toast';


import { useToast } from "primevue/usetoast";
const toast = useToast();

const page = usePage();
const userData = page.props.auth.user;
const modalVisibility = ref(false);

const handleAddRequest = () => {
  modalVisibility.value = true;
};

defineProps({

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
   }
});


const loadToastMessage  = (toastSeverity,toastSummary, toastMessageDetail) => {
    toast.add({ severity: toastSeverity, summary: toastSummary, detail: toastMessageDetail, group: 'br', life: 3000 });
};

</script>

<template>
  <Navbar />
  <div class="container mx-auto py-5 px-20">
    <Chart :username="userData.username" />
    <TableUser :pilihanStatus="statuses" :transaksidata="transaksis" />
  </div>
  <AddRequestBtn @click="handleAddRequest" />
  <ModalDialog v-model:currentVisibility="modalVisibility" :barangdata="barangs" :departementData="departements" :toastMessage="loadToastMessage" />
  <Toast position="bottom-right" group="br" />

</template>
