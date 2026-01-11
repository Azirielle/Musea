<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

const props = defineProps({
    balance: String,
    payout_details: Object,
    withdrawal_requests: Array,
});

const payoutForm = useForm({
    payout_method: props.payout_details.gcash_number ? 'gcash' : 'bank',
    gcash_number: props.payout_details.gcash_number || '',
    bank_details: props.payout_details.bank_details || '',
});

const withdrawForm = useForm({
    amount: '',
    payout_method: props.payout_details.gcash_number ? 'gcash' : 'bank',
});

const updatePayout = () => {
    payoutForm.post(route('dashboard.wallet.payout-details'), {
        preserveScroll: true,
        onSuccess: () => Swal.fire('Updated', 'Payout details saved.', 'success'),
    });
};

const requestWithdrawal = () => {
    withdrawForm.post(route('dashboard.wallet.withdraw'), {
        preserveScroll: true,
        onSuccess: () => {
            withdrawForm.reset('amount');
            Swal.fire('Submitted', 'Withdrawal request sent for review.', 'success');
        },
        onError: (errors) => {
            Swal.fire('Error', Object.values(errors)[0], 'error');
        }
    });
};

const getStatusClass = (status) => {
    switch (status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Artist Wallet" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-ink leading-tight">My Wallet</h2>
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 bg-canvas min-h-screen">
            <div class="max-w-7xl mx-auto space-y-8">
                
                <!-- Balance & Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-paper p-8 rounded-2xl border border-divider shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-bold uppercase tracking-wider text-ink-light opacity-60 mb-2">Total Balance</p>
                        <h3 class="text-4xl font-serif text-ink italic">₱{{ balance }}</h3>
                        <p class="text-xs text-ink-light mt-4">Available for withdrawal</p>
                    </div>

                    <div class="md:col-span-2 bg-paper p-8 rounded-2xl border border-divider shadow-sm">
                        <h3 class="text-lg font-bold text-ink mb-6">Payout Destination</h3>
                        <form @submit.prevent="updatePayout" class="space-y-6">
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" v-model="payoutForm.payout_method" value="gcash" class="text-accent focus:ring-accent border-divider">
                                    <span class="text-sm font-medium text-ink-light group-hover:text-ink">GCash</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" v-model="payoutForm.payout_method" value="bank" class="text-accent focus:ring-accent border-divider">
                                    <span class="text-sm font-medium text-ink-light group-hover:text-ink">Bank Transfer</span>
                                </label>
                            </div>

                            <div v-if="payoutForm.payout_method === 'gcash'" class="animate-in fade-in slide-in-from-top-2 duration-300">
                                <label class="block text-xs font-bold uppercase tracking-tighter text-ink-light mb-2">GCash Number</label>
                                <input v-model="payoutForm.gcash_number" type="text" placeholder="09XX XXX XXXX" class="w-full bg-canvas border-divider rounded-xl focus:border-accent focus:ring-accent text-ink" />
                            </div>

                            <div v-if="payoutForm.payout_method === 'bank'" class="animate-in fade-in slide-in-from-top-2 duration-300">
                                <label class="block text-xs font-bold uppercase tracking-tighter text-ink-light mb-2">Bank Details (Account Name, Number, Bank)</label>
                                <textarea v-model="payoutForm.bank_details" rows="2" placeholder="e.g. Juan Dela Cruz, 1234 5678 90, BDO" class="w-full bg-canvas border-divider rounded-xl focus:border-accent focus:ring-accent text-ink"></textarea>
                            </div>

                            <button :disabled="payoutForm.processing" class="bg-ink text-white px-6 py-2 rounded-full text-sm font-bold hover:bg-ink/90 transition disabled:opacity-50">
                                Save Details
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Withdrawal & History -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Withdrawal Request Form -->
                    <div class="bg-paper p-8 rounded-2xl border border-divider shadow-sm h-fit">
                        <h3 class="text-lg font-bold text-ink mb-6">Request Payout</h3>
                        <form @submit.prevent="requestWithdrawal" class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-tighter text-ink-light mb-2">Amount (PHP)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-ink-light">₱</span>
                                    <input v-model="withdrawForm.amount" type="number" step="0.01" min="100" :max="balance" class="w-full pl-8 bg-canvas border-divider rounded-xl focus:border-accent focus:ring-accent text-ink" placeholder="0.00" />
                                </div>
                                <p class="text-[10px] text-ink-light mt-2">Minimum withdrawal: ₱100.00</p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold uppercase tracking-tighter text-ink-light mb-2">Payout Method</label>
                                <select v-model="withdrawForm.payout_method" class="w-full bg-canvas border-divider rounded-xl focus:border-accent focus:ring-accent text-ink">
                                    <option value="gcash">GCash</option>
                                    <option value="bank">Bank Transfer</option>
                                </select>
                            </div>

                            <button :disabled="withdrawForm.processing || !balance || balance < 100" class="w-full bg-accent text-white py-4 rounded-xl font-bold hover:opacity-90 transition transform active:scale-95 disabled:opacity-50 disabled:grayscale">
                                Submit Request
                            </button>
                        </form>
                    </div>

                    <!-- History Table -->
                    <div class="lg:col-span-2 bg-paper p-8 rounded-2xl border border-divider shadow-sm">
                        <h3 class="text-lg font-bold text-ink mb-6">Recent Requests</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="text-[10px] uppercase tracking-widest text-ink-light border-b border-divider">
                                    <tr>
                                        <th class="pb-4">Date</th>
                                        <th class="pb-4">Amount</th>
                                        <th class="pb-4">Method</th>
                                        <th class="pb-4">Status</th>
                                        <th class="pb-4">Notes</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-divider">
                                    <tr v-if="withdrawal_requests.length === 0">
                                        <td colspan="5" class="py-8 text-center text-ink-light italic">No withdrawal requests yet.</td>
                                    </tr>
                                    <tr v-for="request in withdrawal_requests" :key="request.id" class="group hover:bg-canvas/50 transition-colors">
                                        <td class="py-4 text-ink-light">{{ new Date(request.created_at).toLocaleDateString() }}</td>
                                        <td class="py-4 font-bold text-ink">₱{{ request.amount }}</td>
                                        <td class="py-4 text-ink-light capitalize">{{ request.payout_method }}</td>
                                        <td class="py-4">
                                            <span :class="getStatusClass(request.status)" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                                {{ request.status }}
                                            </span>
                                        </td>
                                        <td class="py-4 text-xs text-ink-light italic max-w-xs truncate" :title="request.admin_notes">
                                            {{ request.admin_notes || '-' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
