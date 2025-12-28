<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';

defineProps({
    coupons: Array,
});

const showCreateModal = ref(false);

const form = useForm({
    code: '',
    type: 'fixed',
    value: '',
    expires_at: '',
    usage_limit: '',
});

const submit = () => {
    form.post(route('admin.coupons.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
            Swal.fire({
                icon: 'success',
                title: 'Coupon Created',
                showConfirmButton: false,
                timer: 1500
            });
        },
    });
};

const toggleStatus = (id) => {
    useForm({}).post(route('admin.coupons.toggle', id), {
        onSuccess: () => {
             Swal.fire({
                icon: 'success',
                title: 'Status Updated',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        }
    });
};

const deleteCoupon = (id) => {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            useForm({}).delete(route('admin.coupons.destroy', id));
        }
    });
};
</script>

<template>
    <Head title="Manage Coupons" />

    <AdminLayout>
        <template #header>Manage Coupons</template>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <h3 class="text-lg font-medium">Coupon Codes</h3>
                <PrimaryButton @click="showCreateModal = true" class="bg-[#CBA35C] hover:bg-[#B89350] border-none text-gray-900">
                    + Create New Coupon
                </PrimaryButton>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Discount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usage</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="coupons.length === 0">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No coupons found.</td>
                        </tr>
                        <tr v-for="coupon in coupons" :key="coupon.id">
                            <td class="px-6 py-4 whitespace-nowrap font-mono font-bold">{{ coupon.code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span v-if="coupon.type === 'fixed'">₱{{ Number(coupon.value).toLocaleString() }} OFF</span>
                                <span v-else>{{ Number(coupon.value) }}% OFF</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ coupon.used_count }} / {{ coupon.usage_limit || '∞' }}
                            </td>
                             <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    @click="toggleStatus(coupon.id)"
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-colors"
                                    :class="coupon.is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200'"
                                >
                                    {{ coupon.is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="deleteCoupon(coupon.id)" class="text-red-600 hover:text-red-900">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Create New Coupon</h2>
                
                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <InputLabel for="code" value="Coupon Code" />
                        <TextInput id="code" v-model="form.code" class="mt-1 block w-full uppercase" required autofocus placeholder="e.g. WELCOME10" />
                        <InputError class="mt-2" :message="form.errors.code" />
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="type" value="Discount Type" />
                            <select id="type" v-model="form.type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm">
                                <option value="percent">Percentage (%)</option>
                                <option value="fixed">Fixed Amount (₱)</option>
                            </select>
                        </div>
                         <div>
                            <InputLabel for="value" value="Value" />
                            <TextInput id="value" type="number" step="0.01" v-model="form.value" class="mt-1 block w-full" required />
                             <InputError class="mt-2" :message="form.errors.value" />
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <InputLabel for="usage_limit" value="Usage Limit (Optional)" />
                            <TextInput id="usage_limit" type="number" v-model="form.usage_limit" class="mt-1 block w-full" placeholder="Leave empty for unlimited" />
                        </div>
                        <div>
                            <InputLabel for="expires_at" value="Expiry Date (Optional)" />
                            <TextInput id="expires_at" type="date" v-model="form.expires_at" class="mt-1 block w-full" />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <PrimaryButton :disabled="form.processing" class="bg-[#CBA35C] hover:bg-[#B89350] border-none text-gray-900">
                            Create Coupon
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
    </AdminLayout>
</template>
