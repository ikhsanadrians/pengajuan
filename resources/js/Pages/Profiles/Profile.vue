<template>
    <div class="container mx-auto py-5">
        <h1 class="text-2xl font-bold mb-4">Profile</h1>
        <Card>
            <h2 class="text-xl font-semibold">User Information</h2>
            <div class="flex flex-col">
                <div class="mb-2">
                    <strong>Username:</strong> {{ userData.username }}
                </div>
                <div class="mb-2">
                    <strong>Email:</strong> {{ userData.email }}
                </div>
                <div class="mb-2">
                    <strong>Role:</strong> {{ userData.role }}
                </div>
            </div>
        </Card>
        <Button label="Edit Profile" icon="pi pi-pencil" @click="editProfile" class="mt-4" />
        <Button label="Change Password" icon="pi pi-lock" @click="showPasswordModal" class="mt-4" />
        
        <Dialog v-model:visible="isPasswordModalVisible" header="Change Password" modal>
            <template #footer>
                <Button label="Cancel" icon="pi pi-times" @click="isPasswordModalVisible = false" />
                <Button label="Save" icon="pi pi-check" @click="changePassword" />
            </template>
            <div class="flex flex-col">
                <InputText v-model="newPassword" placeholder="New Password" type="password" class="mb-2" />
                <InputText v-model="confirmPassword" placeholder="Confirm Password" type="password" />
            </div>
        </Dialog>
    </div>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import Card from 'primevue/card';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import InputText from 'primevue/inputtext';

const page = usePage();
const userData = ref(page.props.auth.user);
const isPasswordModalVisible = ref(false);
const newPassword = ref('');
const confirmPassword = ref('');

const editProfile = () => {
    // Logic to edit profile goes here
    console.log('Edit Profile clicked');
};

const showPasswordModal = () => {
    isPasswordModalVisible.value = true;
};

const changePassword = async () => {
    if (newPassword.value === confirmPassword.value) {
        try {
            const response = await axios.post('/api/change-password', {
                password: newPassword.value
            });
            console.log('Password changed successfully:', response.data);
            isPasswordModalVisible.value = false;
        } catch (error) {
            console.error('Error changing password:', error.response.data);
        }
    } else {
        console.error('Passwords do not match');
    }
};
</script>

<style scoped>
.container {
    max-width: 600px;
}
</style>
