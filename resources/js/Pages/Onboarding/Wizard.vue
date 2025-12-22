<script setup>
import { ref } from 'vue';
import { useForm, Head, usePage } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    interests: Array,
    user: Object,
});

const step = ref(1);

// Step 2 Form: Profile
const profileForm = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    avatar: null,
    default_avatar: null,
});

const fileInput = ref(null);
const avatarPreview = ref(props.user.avatar_path ? (props.user.avatar_path.startsWith('http') ? props.user.avatar_path : `/storage/${props.user.avatar_path}`) : null);

const defaultAvatars = [
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Zoe',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Jack',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Sam',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Milo',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Lily',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo',
];

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        profileForm.avatar = file;
        profileForm.default_avatar = null; // Clear default selection
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const selectDefaultAvatar = (url) => {
    profileForm.default_avatar = url;
    profileForm.avatar = null; // Clear file upload
    avatarPreview.value = url;
     // Reset file input if needed, though not strictly necessary as form.avatar is null
};

const submitProfile = () => {
    profileForm.post(route('onboarding.profile'), {
        onSuccess: () => step.value = 3,
        preserveScroll: true,
    });
};

// Step 3 Form: Interests
const interestsForm = useForm({
    interests: [],
});

const toggleInterest = (id) => {
    if (interestsForm.interests.includes(id)) {
        interestsForm.interests = interestsForm.interests.filter(i => i !== id);
    } else {
        interestsForm.interests.push(id);
    }
};

const submitInterests = () => {
    interestsForm.post(route('onboarding.interests'));
};

const nextStep = () => {
    if (step.value === 1) step.value = 2;
};
</script>

<template>
    <Head title="Welcome to Musea" />
    <div class="min-h-screen bg-[#FDFBF7] flex flex-col items-center justify-center p-6 font-sans text-[#1A1A1A]">
        
        <!-- Progress Indicators -->
        <div class="flex gap-2 mb-8">
            <div 
                v-for="s in 3" 
                :key="s"
                class="h-2 w-12 rounded-full transition-all duration-500"
                :class="s <= step ? 'bg-[#CBA35C]' : 'bg-gray-200'"
            ></div>
        </div>

        <div class="w-full max-w-2xl bg-white shadow-xl rounded-2xl p-8 md:p-12 overflow-hidden relative">
            
            <!-- Step 1: Welcome -->
            <transition
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="opacity-0 translate-x-10"
                enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition duration-300 ease-in absolute top-0 left-0 w-full"
                leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 -translate-x-10"
            >
                <div v-if="step === 1" class="text-center w-full">
                    <div class="flex justify-center mb-6">
                         <!-- Logo Placeholder or Icon -->
                        <div class="w-20 h-20 bg-[#CBA35C] rounded-full flex items-center justify-center text-white text-3xl font-serif font-bold">
                            M
                        </div>
                    </div>
                    <h1 class="text-4xl font-bold font-serif mb-4 tracking-tight">Welcome to Musea</h1>
                    <p class="text-gray-500 text-lg mb-8 max-w-md mx-auto">
                        Discover a world where art meets passion. Let's personalize your journey to find pieces that speak to your soul.
                    </p>
                    <PrimaryButton @click="nextStep" class="px-8 py-3 text-lg bg-[#CBA35C] hover:bg-[#B89350] rounded-full">
                        Get Started
                    </PrimaryButton>
                </div>
            </transition>

            <!-- Step 2: Profile -->
            <transition
                enter-active-class="transition duration-500 ease-out delay-200"
                enter-from-class="opacity-0 translate-x-10"
                enter-to-class="opacity-100 translate-x-0"
                leave-active-class="transition duration-300 ease-in absolute top-0 left-0 w-full"
                leave-from-class="opacity-100 translate-x-0"
                leave-to-class="opacity-0 -translate-x-10"
            >
                <div v-if="step === 2" class="w-full">
                    <h2 class="text-3xl font-bold font-serif mb-2 text-center">Tell us about you</h2>
                    <p class="text-gray-500 text-center mb-8">Upload a photo and confirm your name.</p>
                    
                    <form @submit.prevent="submitProfile" class="flex flex-col items-center">
                        <!-- Avatar Upload -->
                        <div class="relative group mb-8 cursor-pointer" @click="$refs.fileInput.click()">
                            <div class="w-28 h-28 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-dashed border-[#CBA35C] group-hover:border-solid transition-all">
                                <img 
                                    v-if="avatarPreview" 
                                    :src="avatarPreview" 
                                    class="w-full h-full object-cover" 
                                    alt="Avatar Preview"
                                />
                                <span v-else class="text-gray-400 text-4xl">+</span>
                            </div>
                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white text-xs font-bold">UPLOAD</span>
                            </div>
                            <input 
                                type="file" 
                                ref="fileInput" 
                                class="hidden" 
                                accept="image/*"
                                @change="handleFileChange"
                            />
                        </div>

                        <!-- Default Avatars Selection -->
                        <div class="w-full mb-8">
                             <p class="text-center text-sm text-gray-400 mb-3">Or choose a default avatar</p>
                             <div class="flex flex-wrap justify-center gap-3">
                                <div 
                                    v-for="(avatar, index) in defaultAvatars" 
                                    :key="index"
                                    @click="selectDefaultAvatar(avatar)"
                                    class="w-12 h-12 rounded-full cursor-pointer border-2 transition-all hover:scale-110"
                                    :class="profileForm.default_avatar === avatar ? 'border-[#CBA35C] ring-2 ring-[#CBA35C] ring-offset-2' : 'border-transparent hover:border-gray-200'"
                                >
                                    <img :src="avatar" alt="Default Avatar" class="w-full h-full rounded-full" />
                                </div>
                             </div>
                        </div>

                        <div class="w-full grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <InputLabel for="first_name" value="First Name" />
                                <TextInput
                                    id="first_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="profileForm.first_name"
                                    required
                                />
                                <InputError class="mt-2" :message="profileForm.errors.first_name" />
                            </div>
                             <div>
                                <InputLabel for="last_name" value="Last Name" />
                                <TextInput
                                    id="last_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="profileForm.last_name"
                                    required
                                />
                                <InputError class="mt-2" :message="profileForm.errors.last_name" />
                            </div>
                        </div>

                        <PrimaryButton 
                            class="w-full justify-center py-3 bg-[#CBA35C] hover:bg-[#B89350]"
                            :class="{ 'opacity-25': profileForm.processing }"
                            :disabled="profileForm.processing"
                        >
                            Continue
                        </PrimaryButton>
                    </form>
                </div>
            </transition>

            <!-- Step 3: Interests -->
            <transition
                enter-active-class="transition duration-500 ease-out delay-200"
                enter-from-class="opacity-0 translate-x-10"
                enter-to-class="opacity-100 translate-x-0"
               
            >
                <div v-if="step === 3" class="w-full">
                    <h2 class="text-3xl font-bold font-serif mb-2 text-center">Curate your feed</h2>
                    <p class="text-gray-500 text-center mb-8">What kind of art moves you?</p>

                    <form @submit.prevent="submitInterests">
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                            <div 
                                v-for="interest in interests" 
                                :key="interest.id"
                                @click="toggleInterest(interest.id)"
                                class="cursor-pointer rounded-xl border-2 p-4 flex flex-col items-center justify-center transition-all duration-200 h-32"
                                :class="interestsForm.interests.includes(interest.id) 
                                    ? 'border-[#CBA35C] bg-[#CBA35C]/5 shadow-md scale-105' 
                                    : 'border-gray-100 bg-gray-50 hover:border-gray-300'"
                            >
                                <span class="text-lg font-medium" :class="interestsForm.interests.includes(interest.id) ? 'text-[#CBA35C]' : 'text-gray-600'">
                                    {{ interest.name }}
                                </span>
                            </div>
                        </div>

                         <PrimaryButton 
                            class="w-full justify-center py-3 bg-[#CBA35C] hover:bg-[#B89350]"
                            :class="{ 'opacity-25': interestsForm.processing }"
                            :disabled="interestsForm.processing"
                        >
                            Finish Setup
                        </PrimaryButton>
                        <InputError class="mt-2 text-center" :message="interestsForm.errors.interests" />
                    </form>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
/* Add any specific transitions if needed, using standard Vue transition classes above */
</style>
