<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';

const props = defineProps({
    currentVisibility: {
        type: Boolean,
        required: true,
    }
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

watch(visible, (newValue) => {
    emit('update:currentVisibility', newValue);
});
</script>

<template>
    <Dialog v-model:visible="visible" modal header="Detail Pengajuan" :style="{ width: '60rem' }" @hide="closeDialog">
        <p>Detail Pengajuan</p>
        <div class="flex justify-end gap-2 mt-4">
            <Button type="button" label="Batal" severity="secondary" @click="closeDialog" />
        </div>
    </Dialog>
</template>
