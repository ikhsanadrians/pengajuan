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
    currentBarangId: {
        type: String,
    },
    toastMessage: {
        type: Function
    },
    onSuccessReject: {
        type: Function,
        required: true
    }

});

const emit = defineEmits(['update:currentVisibility', 'refreshTransaction']);
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

watch(() => emit('refreshTransaction'), () => {
    fetchCurrentTransaction();
});

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};

watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});

const saveRejectReason = async () => {
    if (keterangan.value.trim() === '') {
        props.toastMessage('error', 'Info', 'Keterangan Wajib diisi!');
        return;
    }
    try {
        loading.value = true;
        const response = await axios.post('admin/reject-per-transaksi', {
            transaksi_id: currentBarangId.value,
            keterangan: keterangan.value
        });

        if (response.data.data == 1) {
            props.toastMessage('success', 'Info', 'Berhasil Menolak Transaksi!');
            closeDialog();
            emit('rejectionSuccess');
        } else {
            props.toastMessage('error', 'Info', 'Gagal Menolak Transaksi');
        }
    } catch (error) {
        props.toastMessage('error', 'Info', error);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Alasan Barang Ditolak" :style="{ width: '50rem', MaxHeight: '90%' }"
        @hide="closeDialog">
        <div class="text-area">
            <label class="font-semibold">Keterangan</label>
            <Textarea v-model="keterangan" placeholder="Masukan Keterangan" class="w-full mt-1" id="over_label" rows="5"
                cols="30" style="resize: none" />
        </div>
        <Button class="mt-3 float-right" icon="pi pi-book" label="Simpan" severity="success" rounded
            @click="saveRejectReason" />
    </Dialog>
</template>
