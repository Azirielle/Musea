<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Swal from 'sweetalert2';

const user = usePage().props.auth.user;

const form = useForm({
    role_requested: 'artist',
    portfolio_url: user.portfolio_url || '',
    bio: user.bio || '',
});

const submit = () => {
    form.post(route('verification.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire({
                title: 'Application Submitted!',
                text: 'Your request for verification has been sent successfully. We will review it shortly.',
                icon: 'success',
                confirmButtonColor: '#1A1A1A'
            });
        },
        onError: () => {
            Swal.fire({
                title: 'Submission Failed',
                text: 'Please check the form for errors and try again.',
                icon: 'error',
                confirmButtonColor: '#1A1A1A'
            });
        }
    });
};

const status = computed(() => user.verification_status);
const isVerified = computed(() => user.is_verified);
const currentRole = computed(() => user.role);

</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 font-serif">Artist Application</h2>
            <p class="mt-1 text-sm text-gray-600">
                Apply to become a verified artist and start selling your creations on Musea.
            </p>
        </header>

        <div class="mt-6 space-y-6">
            <!-- Approved State -->
            <div v-if="currentRole === 'artist'" class="p-4 bg-green-50 rounded-xl border border-green-100 flex items-center gap-3">
                <div class="p-2 bg-green-100 rounded-full text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm4.45 6.45l-3.25 3.5a.75.75 0 01-1.1 0l-1.05-1.125a.75.75 0 111.085-1.066l.488.523 2.73-2.937a.75.75 0 111.1 1.02z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-green-800">You are an Artist!</h3>
                    <p class="text-sm text-green-700">You can now upload and sell your artwork.</p>
                </div>
            </div>

            <!-- Pending State -->
            <div v-else-if="status === 'pending'" class="p-4 bg-orange-50 rounded-xl border border-orange-100 flex items-center gap-3">
                <div class="p-2 bg-orange-100 rounded-full text-orange-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-orange-800">Artist Application Under Review</h3>
                    <p class="text-sm text-orange-700">Our team is reviewing your profile. Please check back later.</p>
                </div>
            </div>

            <!-- Form State (Status: none or rejected) -->
            <form v-else @submit.prevent="submit" class="space-y-6">
                 
                <div v-if="status === 'rejected'" class="p-4 bg-red-50 rounded-xl border border-red-100 mb-4">
                     <h3 class="font-bold text-red-800 text-sm">Previous Application Rejected</h3>
                     <p class="text-xs text-red-700">You can update your details and try again.</p>
                </div>

                <!-- Hidden Role Input (Always Artist) -->
                <input type="hidden" v-model="form.role_requested">

                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 text-blue-800 text-sm mb-4">
                    <p class="font-bold mb-1">Apply to become a Musea Artist</p>
                    <p>Verified artists can list artworks, manage sales, and build a following.</p>
                </div>

                <!-- Portfolio URL -->
                <div>
                    <label for="verification_portfolio_url" class="block text-sm font-medium text-gray-700">Portfolio URL (Optional)</label>
                    <input 
                        id="verification_portfolio_url" 
                        type="url" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        v-model="form.portfolio_url"
                        placeholder="https://instagram.com/yourprofile"
                    >
                    <div v-if="form.errors.portfolio_url" class="text-red-500 text-xs mt-1">{{ form.errors.portfolio_url }}</div>
                </div>

                <!-- Bio -->
                <div>
                    <label for="verification_bio" class="block text-sm font-medium text-gray-700">Artist Bio</label>
                    <textarea 
                        id="verification_bio" 
                        rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        v-model="form.bio"
                        placeholder="Tell us about yourself and your art..."
                    ></textarea>
                    <div v-if="form.errors.bio" class="text-red-500 text-xs mt-1">{{ form.errors.bio }}</div>
                </div>

                <div class="flex justify-end">
                    <button 
                        type="submit" 
                        :disabled="form.processing" 
                        class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                    >
                        Submit Artist Application
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>
