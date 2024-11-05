<script setup>
import { Link, router } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import Avatar from 'primevue/avatar';
import Popover from 'primevue/popover';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Button from 'primevue/button';
import MegaMenu from 'primevue/megamenu';


const op = ref();
const opNav = ref();

const toggle = (event) => {
    op.value.toggle(event);
}

const toggleNav = (event) => {
    opNav.value.toggle(event)
}

const page = usePage();
const userData = page.props.auth.user;

const logout = () => {
    router.post('/logout');
}

</script>
<template>
    <div class="bg-white h-16 shadow-sm border-b-[1.5px] border-gray-300 sticky top-0 z-30">
        <div class="container mx-auto px-8 flex w-full items-center h-full">
            <div class="w-full flex justify-between items-center py-8">
                <Link class="flex items-center font-bold" href="/">
                <div class="flex flex-col">
                    <h1>PT Intertama Trikencana Bersinar</h1>
                    <p class="text-gray-600 font-light">Pengajuan Barang</p>
                </div>
                </Link>
                <div v-if="userData.role_id == 1" class="admin-dropdown">
                    <div @click="toggleNav"
                        class="text-[13px] flex flex-col items-center cursor-pointer hover:bg-gray-100 py-2 px-4 rounded-md">
                        <i class="pi pi-bars"></i>
                        <p class="text-gray-600">
                            Navigation
                        </p>
                    </div>
                </div>
                <div class="right-navbar gap-x-4 flex items-center">
                    <span class="relative">
                        <i class="pi pi-search absolute top-2/4 -mt-2 left-3 text-surface-400 dark:text-surface-600" />
                        <InputText v-model="value1" placeholder="Cari Pengajuan" class="!pl-10 w-64" size="small" />
                    </span>
                    <Avatar @click="toggle" label="AH" class="mr-2 cursor-pointer" size="small"
                        style="background-color: #ece9fc; color: #2a1261" shape="circle" />
                </div>
            </div>

        </div>
    </div>
    <Popover ref="op" class="w-[15rem] sticky top-2">
        <div class="flex flex-col gap-4">
            <div class="profile flex items-center">
                <Avatar label="AH" class="mr-2 cursor-pointer" size="small"
                    style="background-color: #ece9fc; color: #2a1261" shape="circle" />
                <p class="font-semibold">{{ userData.username }}</p>
            </div>
            <div class="tools flex flex-col gap-y-2">
                <div class="profile hover:bg-slate-100 p-2 rounded-md cursor-pointer flex items-center gap-2">
                    <i class="pi pi-user" style="font-size: 1.2rem"></i>
                    <p class="font-semibold text-sm mt-1">Profile</p>
                </div>
                <div class="settings hover:bg-slate-100 p-2  rounded-md cursor-pointer flex items-center gap-2">
                    <i class="pi pi-cog" style="font-size: 1.2rem"></i>
                    <p class="font-semibold text-sm mt-1">Settings</p>
                </div>
                <div class="logout rounded-md cursor-pointer flex justify-center items-center gap-2">
                    <Button icon="pi pi-sign-out" class="w-full" style="font-size: 13px;" label="Log Out"
                        @click="logout"></Button>
                </div>
            </div>
        </div>
    </Popover>
    <Popover ref="opNav" class="w-[20rem] sticky top-2">
        <div class="navigation flex flex-col gap-4">
            <div class="menu flex items-center text-[14px] cursor-pointer hover:text-emerald-500 text-gray-500">
                <i class="pi pi-angle-right"></i>
                <p>Tambah User</p>
            </div>
            <div class="menu flex items-center text-[14px] cursor-pointer hover:text-emerald-500 text-gray-500">
                <i class="pi pi-angle-right"></i>
                <p>Tambah Barang</p>
            </div>
            <div class="menu flex items-center text-[14px] cursor-pointer hover:text-emerald-500 text-gray-500">
                <i class="pi pi-angle-right"></i>
                <p>Tambah Kategori</p>
            </div>
            <div class="menu flex items-center text-[14px] cursor-pointer hover:text-emerald-500 text-gray-500">
                <i class="pi pi-angle-right"></i>
                <p>Tambah Departement</p>
            </div>
        </div>
    </Popover>
</template>
