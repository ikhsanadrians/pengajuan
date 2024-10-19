<!-- ModalDialog.vue -->
<script setup>
import { ref, watch, defineEmits } from 'vue';
import Dialog from 'primevue/dialog';

// Menerima prop dari parent
const props = defineProps({
  currentVisibility: {
    type: Boolean,
    required: true,
  }
});

// Event untuk menginformasikan ke parent tentang perubahan visibility
const emit = defineEmits(['update:currentVisibility']);

// Membuat ref internal untuk sinkronisasi visibility
const visible = ref(props.currentVisibility);

// Watcher untuk memonitor perubahan prop dari parent
watch(() => props.currentVisibility, (newValue) => {
  visible.value = newValue;
});

// Emit event ketika dialog ditutup
const closeDialog = () => {
  visible.value = false;
  emit('update:currentVisibility', false); // Mengirim event ke parent
};
</script>

<template>
  <Dialog v-model:visible="visible" modal header="Edit Profile" :style="{ width: '25rem' }" @hide="closeDialog">
    <span class="text-surface-500 dark:text-surface-400 block mb-8">Update your information.</span>
    <div class="flex items-center gap-4 mb-4">
      <label for="username" class="font-semibold w-24">Username</label>
      <InputText id="username" class="flex-auto" autocomplete="off" />
    </div>
    <div class="flex items-center gap-4 mb-8">
      <label for="email" class="font-semibold w-24">Email</label>
      <InputText id="email" class="flex-auto" autocomplete="off" />
    </div>
    <div class="flex justify-end gap-2">
      <Button type="button" label="Cancel" severity="secondary" @click="closeDialog"></Button>
      <Button type="button" label="Save" @click="closeDialog"></Button>
    </div>
  </Dialog>
</template>
