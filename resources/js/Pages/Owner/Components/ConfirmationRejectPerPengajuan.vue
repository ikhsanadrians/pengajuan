<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import FloatLabel from 'primevue/floatlabel';
import Button from 'primevue/button';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
    currentPengajuanId: {
        type: String,
    },
    toastMessage: {
        type: Function
    },
    isCurrentDetailRequestModalOpen: {
        type: Boolean
    }
});

const emit = defineEmits(['update:currentVisibility', 'update:isCurrentDetailRequestModalOpen']);
const visible = ref(props.currentVisibility);
const loading = ref(false);
const currentBarangId = ref(props.currentBarangId);
const keterangan = ref('');

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

watch(() => props.currentBarangId, (newValue) => {
    currentBarangId.value = newValue;
});

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
    emit('update:isCurrentDetailRequestModalOpen', false)
};


watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});

const saveRejectPengajuanReason = async () => {
    if (keterangan.value.trim() === '') {
        props.toastMessage('error', 'Info', 'Keterangan Wajib diisi!');
        return;
    }
    try {
        loading.value = true;
        const response = await axios.post('owner/reject-per-pengajuan', {
            pengajuanId: props.currentPengajuanId,
            keteranganRejected: keterangan.value
        });


        if (response.data.payload) {
            props.toastMessage('success', 'Info', 'Berhasil Menolak Pengajuan!');
            closeDialog();
            router.reload();
        } else {
            console.log(response.data)
            props.toastMessage('error', 'Info', 'Gagal Menolak Pengajuan');
        }
    } catch (error) {
        props.toastMessage('error', 'Info', error);
    } finally {
        loading.value = false;
    }
};

</script>

<template>
    <Dialog v-model:visible="visible" modal header="Alasan Pengajuan Ditolak"
        :style="{ width: '50rem', MaxHeight: '90%' }" @hide="closeDialog">
        <div class="text-area">
            <label class="font-semibold">Keterangan</label>
            <Textarea v-model="keterangan" placeholder="Masukan Keterangan" class="w-full mt-1" id="over_label" rows="5"
                cols="30" style="resize: none" />
        </div>
        <Button class="mt-3 float-right" icon="pi pi-book" label="Simpan" severity="success" rounded
            @click="saveRejectPengajuanReason" />
    </Dialog>
</template>
