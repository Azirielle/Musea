<script setup>
import Modal from '@/Components/Modal.vue';
import LoginForm from '@/Pages/Auth/LoginForm.vue';
import RegisterForm from '@/Pages/Auth/RegisterForm.vue';
import { ref, watch } from 'vue';

const props = defineProps({
    show: Boolean,
    initialView: {
        type: String,
        default: 'login' // 'login' or 'register'
    }
});

const emit = defineEmits(['close']);

const currentView = ref(props.initialView);

watch(() => props.initialView, (newVal) => {
    currentView.value = newVal;
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="close" maxWidth="md">
        <div class="p-6">
            <LoginForm 
                v-if="currentView === 'login'" 
                :canResetPassword="true" 
                @switchToRegister="currentView = 'register'"
                @success="close"
            />
            <RegisterForm 
                v-if="currentView === 'register'" 
                @switchToLogin="currentView = 'login'"
                @success="close"
            />
        </div>
    </Modal>
</template>
