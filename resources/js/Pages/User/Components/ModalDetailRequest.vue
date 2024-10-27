<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import InputNumber from 'primevue/inputnumber';
import Button from 'primevue/button';
import Card from 'primevue/card';
import { router } from '@inertiajs/vue3';




const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits(['update:currentVisibility']);

const visible = ref(props.currentVisibility);

watch(() => props.currentVisibility, (newValue) => {
    visible.value = newValue;
});

const closeDialog = () => {
    visible.value = false;
    emit('update:currentVisibility', false);
};


</script>
<template>
    <Dialog v-model:visible="visible" modal header="Buat Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog" class="overflow-thin">

        <div class="flex justify-end gap-2 mt-4">
            <Button
                type="button"
                label="Batal"
                severity="secondary"
            >
            </Button>
            <Button
                icon="pi pi-save"
                type="button"
                label="Simpan"
            >
            </Button>
        </div>
    </Dialog>
</template>
