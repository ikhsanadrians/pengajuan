<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Navbar from '@/Components/Navbar.vue';
import Chart from '@/Components/Chart.vue';
import TableApprovalRequest from '@/Components/TableApprovalRequest.vue';
import AddRequestBtn from '@/Pages/User/Components/AddRequestBtn.vue';
import ModalDialog from './Components/ModalDialog.vue';
import { ref } from 'vue';

// Data dari page
const page = usePage();
const userData = page.props.auth.user;
const modalVisibility = ref(false); // Mengontrol visibility modal

// Function untuk membuka modal
const handleAddRequest = () => {
  modalVisibility.value = true;
};
</script>
b
<template>
  <Navbar />
  <div class="container mx-auto py-5 px-20">
    <Chart :username="userData.username" />
    <TableApprovalRequest :transaksidata="transaksis" />
  </div>
  <!-- Button untuk membuka modal -->
  <AddRequestBtn @click="handleAddRequest" />
  <!-- Menggunakan v-model untuk two-way binding antara parent dan child -->
  <ModalDialog v-model:currentVisibility="modalVisibility" />
</template>
