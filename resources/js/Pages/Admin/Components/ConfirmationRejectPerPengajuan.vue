<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Textarea from 'primevue/textarea';
import FloatLabel from 'primevue/floatlabel';
import Button from 'primevue/button';
import axios from 'axios';

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
    }
});

const emit = defineEmits(['update:currentVisibility']);
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
        const response = await axios.post('admin/reject-per-pengajuan', {
            pengajuan_id: currentPengajuanId.value,
            keterangan: keterangan.value
        });

        if (response.data.data == 1) {
            props.toastMessage('success', 'Info', 'Berhasil Menolak Pengajuan!');
            closeDialog();
        } else {
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
    <Dialog v-model:visible="visible" modal header="Alasan Pengajuan Ditolak" :style="{ width: '50rem', MaxHeight: '90%' }"
        @hide="closeDialog">
        <div class="text-area">
            <label class="font-semibold">Keterangan</label>
            <Textarea v-model="keterangan" placeholder="Masukan Keterangan" class="w-full mt-1" id="over_label" rows="5"
                cols="30" style="resize: none" />
        </div>
        <Button class="mt-3 float-right" icon="pi pi-book" label="Simpan" severity="success" rounded
            @click="saveRejectPengajuanReason" />
    </Dialog>
</template>
