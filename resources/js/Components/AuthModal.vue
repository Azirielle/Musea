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
    <Modal 
        :show="show" 
        @close="close" 
        maxWidth="md"
        containerClass="backdrop-blur-xl bg-white/70 dark:bg-[#1A1A1A]/90 border border-white/20 shadow-2xl"
    >
        <div class="p-8"> <!-- Increased padding for breathing room -->
            <Transition
                mode="out-in"
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="transform translate-y-2 opacity-0"
                enter-to-class="transform translate-y-0 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform translate-y-0 opacity-100"
                leave-to-class="transform translate-y-2 opacity-0"
            >
                <LoginForm 
                    v-if="currentView === 'login'" 
                    :canResetPassword="true" 
                    @switchToRegister="currentView = 'register'"
                    @success="close"
                />
                <RegisterForm 
                    v-else-if="currentView === 'register'" 
                    @switchToLogin="currentView = 'login'"
                    @success="close"
                />
            </Transition>
        </div>
    </Modal>
</template>
