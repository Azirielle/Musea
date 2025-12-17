<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    show: Boolean,
});

const emit = defineEmits(['close']);

const currentView = ref('login'); // 'login' or 'register'

// LOGIN FORM
const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

// REGISTER FORM
const registerForm = useForm({
    first_name: '',
    last_name: '',
    email: '',
    address: '',
    contact_number: '',
    password: '',
    password_confirmation: '',
});

const close = () => {
    emit('close');
    // Reset forms and view on close if needed, or keep state.
    // Let's reset view to login for next time
    setTimeout(() => {
        currentView.value = 'login';
        loginForm.reset();
        registerForm.reset();
        loginForm.clearErrors();
        registerForm.clearErrors();
    }, 300);
};

const submitLogin = () => {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
        onSuccess: () => close(),
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
        onSuccess: () => {
             // Depending on backend logic, auto-login might happen.
             // If manual login required, switch to login view. 
             // Assuming Breeze auto-logins:
             close();
        },
    });
};

const switchView = (view) => {
    currentView.value = view;
    loginForm.clearErrors();
    registerForm.clearErrors();
};
</script>

<template>
    <div class="modal-overlay" :class="{ active: show }">
        <!-- LOGIN VIEW -->
        <div v-if="currentView === 'login'" class="modal-content login-content">
            <button class="modal-close" @click="close">×</button>
            <div class="modal-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your Musea account</p>
            </div>
            
            <form @submit.prevent="submitLogin">
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <TextInput 
                        id="login-email" 
                        type="email" 
                        class="form-input" 
                        v-model="loginForm.email" 
                        placeholder="Enter your email" 
                        required 
                        autofocus
                    />
                    <InputError :message="loginForm.errors.email" class="form-error-msg" />
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <TextInput 
                        id="login-password" 
                        type="password" 
                        class="form-input" 
                        v-model="loginForm.password" 
                        placeholder="Enter your password" 
                        required 
                    />
                    <InputError :message="loginForm.errors.password" class="form-error-msg" />
                </div>

                <div class="block mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="loginForm.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="login-btn" :disabled="loginForm.processing">
                    {{ loginForm.processing ? 'Signing in...' : 'Sign In' }}
                </button>
                
                <div class="signup-link">
                    Don't have an account? <a href="#" @click.prevent="switchView('register')">Sign up!</a>
                </div>
            </form>
        </div>

        <!-- REGISTER VIEW -->
        <div v-if="currentView === 'register'" class="modal-content register-content">
            <button class="modal-close" @click="close">×</button>
            <div class="modal-header">
                <h2>Create Account</h2>
                <p>Join Musea and explore handcrafted art</p>
            </div>
            
            <form @submit.prevent="submitRegister">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <TextInput type="text" class="form-input" v-model="registerForm.first_name" placeholder="John" required />
                        <InputError :message="registerForm.errors.first_name" class="form-error-msg" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <TextInput type="text" class="form-input" v-model="registerForm.last_name" placeholder="Doe" required />
                         <InputError :message="registerForm.errors.last_name" class="form-error-msg" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <TextInput type="email" class="form-input" v-model="registerForm.email" placeholder="john.doe@example.com" required />
                    <InputError :message="registerForm.errors.email" class="form-error-msg" />
                </div>

                <div class="form-group">
                    <label class="form-label">Address</label>
                    <TextInput type="text" class="form-input" v-model="registerForm.address" placeholder="123 Main Street, City" required />
                     <InputError :message="registerForm.errors.address" class="form-error-msg" />
                </div>

                <div class="form-group">
                    <label class="form-label">Contact Number</label>
                    <TextInput type="tel" class="form-input" v-model="registerForm.contact_number" placeholder="+1 234 567 8900" required />
                     <InputError :message="registerForm.errors.contact_number" class="form-error-msg" />
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <TextInput type="password" class="form-input" v-model="registerForm.password" placeholder="Min. 6 characters" required />
                         <InputError :message="registerForm.errors.password" class="form-error-msg" />
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <TextInput type="password" class="form-input" v-model="registerForm.password_confirmation" placeholder="Re-enter password" required />
                         <InputError :message="registerForm.errors.password_confirmation" class="form-error-msg" />
                    </div>
                </div>

                <button type="submit" class="login-btn" :disabled="registerForm.processing">
                    {{ registerForm.processing ? 'Creating Account...' : 'Create Account' }}
                </button>
                
                <div class="signup-link">
                    Already have an account? <a href="#" @click.prevent="switchView('login')">Sign in!</a>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
/* PORTED LEGACY CSS */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(26, 26, 26, 0.75);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2000;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.35s;
    pointer-events: none; /* Interact through when active */
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.modal-content {
    background: linear-gradient(135deg, #ffffff 0%, #fdfcfb 100%);
    border-radius: 16px;
    padding: 40px 35px;
    width: 90%;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(203, 163, 92, 0.1);
    position: relative;
    transform: scale(0.9);
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    max-height: 90vh;
    overflow-y: auto;
}

.login-content {
    max-width: 440px;
}

.register-content {
    max-width: 520px;
}

.modal-overlay.active .modal-content {
    transform: scale(1);
}

.modal-close {
    position: absolute;
    top: 18px;
    right: 18px;
    background: rgba(203, 163, 92, 0.1);
    border: none;
    font-size: 22px;
    cursor: pointer;
    color: #8c7a5a;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.25s ease;
}

.modal-close:hover {
    background: #CBA35C;
    color: #fff;
    transform: rotate(90deg);
}

.modal-header {
    text-align: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(203, 163, 92, 0.15);
}

.modal-header h2 {
    margin: 0;
    color: #1A1A1A;
    font-size: 1.85em;
    font-weight: 700;
    letter-spacing: -0.5px;
}

.modal-header p {
    margin: 8px 0 0;
    color: #666;
    font-size: 0.95em;
}

.form-group {
    margin-bottom: 18px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2E2E2E;
    font-size: 0.9em;
    letter-spacing: 0.3px;
}

/* Overriding default TextInput styles if needed, but adding class anyway */
.form-input {
    width: 100%;
    padding: 13px 16px;
    border: 2px solid #E8E8E8;
    border-radius: 10px;
    background: #fff;
    font-size: 0.95em;
    transition: all 0.25s ease;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: #CBA35C;
    background: #FEFDFB;
    box-shadow: 0 0 0 3px rgba(203, 163, 92, 0.1);
}

.login-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, #CBA35C 0%, #B89350 100%);
    color: #fff;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1em;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 10px;
    box-shadow: 0 4px 15px rgba(203, 163, 92, 0.3);
}

.login-btn:hover {
    background: linear-gradient(135deg, #D4AE67 0%, #C29E5B 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(203, 163, 92, 0.4);
}

.login-btn:active {
    transform: translateY(0);
}

.signup-link {
    text-align: center;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(203, 163, 92, 0.15);
    color: #666;
    font-size: 0.95em;
}

.signup-link a {
    color: #CBA35C;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s ease;
}

.signup-link a:hover {
    color: #B89350;
    text-decoration: underline;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-error-msg {
    margin-top: 5px;
    font-size: 0.85em;
    color: #b71c1c;
}

@media (max-width: 600px) {
    .modal-content {
        padding: 30px 25px;
        max-width: 95%;
    }
    .form-row {
        grid-template-columns: 1fr;
        gap: 0;
    }
}
</style>
