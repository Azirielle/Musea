<script setup>
import { ref } from 'vue';
import UserBadge from '@/Components/UserBadge.vue';
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
    bio: user.bio,
});



const avatarPreview = ref(user.avatar);

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
        onError: (errors) => {
            console.error('Update failed:', errors);
        },
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
            <!-- DEBUG: Show Cloudinary Error if exists -->
            <div v-if="$page.props.flash.cloudinary_debug" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Cloudinary Error:</strong>
                <span class="block sm:inline">{{ $page.props.flash.cloudinary_debug }}</span>
            </div>

            <!-- Avatar Section -->
            <div class="space-y-4">
                <InputLabel value="Selected Identity" />
                <div class="flex items-center gap-6">
                    <div class="relative group">
                        <div class="w-24 h-24 rounded-full bg-gray-50 flex items-center justify-center overflow-hidden border-2 border-dashed border-divider shadow-sm">
                            <img 
                                v-if="avatarPreview" 
                                :src="avatarPreview" 
                                class="w-full h-full object-cover" 
                                alt="Avatar Preview"
                            />
                            <span v-else class="text-gray-400 text-3xl">?</span>
                        </div>
                        <!-- Verification Badge -->
                        <div class="absolute -bottom-1 -right-1 z-20 bg-white rounded-full p-1 shadow-sm border border-gray-100" v-if="user.role !== 'member' || user.is_verified">
                             <UserBadge :role="user.role" :is-verified="!!user.is_verified" />
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-ink mb-1">Your Identity</p>
                        <p class="text-[11px] text-ink-light leading-relaxed">Select from our curated presets below.</p>
                    </div>
                </div>

                <div class="pt-4 border-t border-divider">
                    <p class="text-[11px] font-bold tracking-widest text-ink-light uppercase mb-4">Preset Identities</p>
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-12 gap-3 max-h-48 overflow-y-auto p-2 scrollbar-hide bg-zinc-50 rounded-xl border border-divider">
                        <div 
                            v-for="(avatar, index) in defaultAvatars" 
                            :key="index"
                            @click="selectDefaultAvatar(avatar)"
                            class="relative aspect-square rounded-full cursor-pointer ring-offset-2 transition-all hover:scale-110 active:scale-95 group"
                        >
                            <img :src="avatar" alt="Default" class="w-full h-full rounded-full border border-divider group-hover:border-accent" />
                            <div 
                                v-if="form.default_avatar === avatar"
                                class="absolute -top-1 -right-1 bg-accent text-white w-5 h-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm"
                            >
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <InputError class="mt-2" :message="form.errors.avatar" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
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

            <div class="pt-2">
                <InputLabel for="email" value="Email Address" />
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

            <div class="pt-2">
                <div class="flex justify-between">
                    <InputLabel for="bio" value="Bio" />
                    <span class="text-[10px] font-bold text-ink-light" :class="{'text-red-500': form.bio?.length > 1000}">
                        {{ form.bio?.length || 0 }} / 1000
                    </span>
                </div>
                <textarea
                    id="bio"
                    class="mt-1 block w-full border-divider focus:border-accent focus:ring-accent rounded-xl shadow-sm text-sm"
                    v-model="form.bio"
                    rows="4"
                    placeholder="Tell us about yourself and your art..."
                ></textarea>
                <InputError class="mt-2" :message="form.errors.bio" />
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
