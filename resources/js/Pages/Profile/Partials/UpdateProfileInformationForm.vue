<script setup>
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
    avatar: null,
    default_avatar: null,
});

const fileInput = ref(null);
const avatarPreview = ref(user.avatar_path ? (user.avatar_path.startsWith('http') ? user.avatar_path : `/storage/${user.avatar_path}`) : null);

const defaultAvatars = [
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Felix',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Aneka',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Zoe',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Jack',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Sam',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Milo',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Lily',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Leo',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Bella',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Max',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Sophie',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Charlie',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Luna',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Oscar',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Ruby',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Toby',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Daisy',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Oliver',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Chloe',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Noah',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Maya',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Ethan',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Ava',
    'https://api.dicebear.com/7.x/avataaars/svg?seed=Lucas',
];

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.avatar = file;
        form.default_avatar = null;
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const selectDefaultAvatar = (url) => {
    form.default_avatar = url;
    form.avatar = null;
    avatarPreview.value = url;
};

const submit = () => {
    // When uploading files via Inertia to a PUT/PATCH route, we must use POST
    // and spoof the method using _method field in the data.
    form.transform((data) => ({
        ...data,
        _method: 'PATCH',
    })).post(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
             // Optional: handle success visuals
        }
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            @submit.prevent="submit"
            class="mt-6 space-y-6"
        >
            <!-- Avatar Section -->
            <div class="flex flex-col items-center mb-6">
                 <div class="relative group mb-4 cursor-pointer" @click="$refs.fileInput.click()">
                    <div class="w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-dashed border-[#CBA35C] group-hover:border-solid transition-all">
                        <img 
                            v-if="avatarPreview" 
                            :src="avatarPreview" 
                            class="w-full h-full object-cover" 
                            alt="Avatar Preview"
                        />
                        <span v-else class="text-gray-400 text-3xl">+</span>
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white text-[10px] font-bold">UPLOAD</span>
                    </div>
                    <input 
                        type="file" 
                        ref="fileInput" 
                        class="hidden" 
                        accept="image/*"
                        @change="handleFileChange"
                    />
                </div>
                 <!-- Default Avatars -->
                 <div class="flex flex-wrap justify-center gap-2 mb-4">
                    <div 
                        v-for="(avatar, index) in defaultAvatars" 
                        :key="index"
                        @click="selectDefaultAvatar(avatar)"
                        class="w-8 h-8 rounded-full cursor-pointer border hover:scale-110 transition-transform"
                        :class="form.default_avatar === avatar ? 'border-[#CBA35C] ring-1 ring-[#CBA35C]' : 'border-transparent'"
                    >
                        <img :src="avatar" alt="Default" class="w-full h-full rounded-full" />
                    </div>
                 </div>
                 <InputError class="mt-2" :message="form.errors.avatar" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <InputLabel for="first_name" value="First Name" />
                    <TextInput
                        id="first_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="given-name"
                    />
                    <InputError class="mt-2" :message="form.errors.first_name" />
                </div>

                 <div>
                    <InputLabel for="last_name" value="Last Name" />
                    <TextInput
                        id="last_name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.last_name"
                        required
                        autocomplete="family-name"
                    />
                    <InputError class="mt-2" :message="form.errors.last_name" />
                </div>
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing" class="bg-[#CBA35C] hover:bg-[#B89350] text-gray-900 border-none">Save Changes</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
