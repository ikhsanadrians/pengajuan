<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>

        <Head title="Pengajuan Barang | PT Intertama Trikencana Bersinar" />
        <Banner />
        <div class="min-h-screen bg-gray-100">
            <main>
                <slot />
            </main>PT Global (Paramarta Cardiovascular Hospital)
        </div>
    </div>
</template>
