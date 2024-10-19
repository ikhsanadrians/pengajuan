<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Navbar from '@/Components/Navbar.vue';
import Chart from '@/Components/Chart.vue';
import TableApprovalRequest from '@/Components/TableApprovalRequest.vue';

defineProps({
//   transaksis: {
    // type: Array,
    // default: () => [],
//   },
});

const page = usePage();

// const userData = page.props.auth.user;



</script>
<template>
   <Navbar/>
   <p>Admin</p>
   <div class="container mx-auto py-5 px-20">
    <Chart :username="userData.name" />
   {{ user }}
    <!-- <TableApprovalRequest :transaksidata="transaksis"/> -->
   </div>



</template>
