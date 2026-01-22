<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { ref, watch } from 'vue';

import { categories } from '@/Constants/Categories';
import { computed } from 'vue';

const form = useForm({
    title: '',
    description: '',
    category: 'Paintings', // Default to first valid category key
    style: '',
    subject: '',
    medium: '',
    subcategory: '', // Legacy/Optional
    ready_to_hang: false,
    framing: 'Unframed',
    width: '',
    height: '',
    depth: '',
    unit: 'cm',
    price: '',
    stock: 1,
    image: null,
});

const availableOptions = computed(() => {
    return categories[form.category] || null;
});

// Watch category to reset fields
watch(() => form.category, () => {
    form.style = '';
    form.subject = '';
    form.medium = '';
});

const submit = () => {
    form.post(route('dashboard.artworks.store'), {
        onSuccess: () => form.reset(),
    });
};

const handleImageUpload = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <Head title="Upload Artwork" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upload New Artwork</h2>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="form.hasErrors" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Whoops!</strong>
                            <span class="block sm:inline"> Please fix the errors below to continue.</span>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Title -->
                            <div>
                                <InputLabel for="title" value="Artwork Title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Category -->
                                <div>
                                    <InputLabel for="category" value="Category" />
                                    <select
                                        id="category"
                                        class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                        v-model="form.category"
                                        required
                                    >
                                        <option v-for="(opts, cat) in categories" :key="cat" :value="cat">{{ cat }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.category" />
                                </div>

                                <!-- Dynamic Fields based on Category -->
                                <div class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4" v-if="availableOptions">
                                    
                                    <!-- Style -->
                                    <div v-if="availableOptions.Style">
                                        <InputLabel for="style" value="Style" />
                                        <select
                                            id="style"
                                            class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                            v-model="form.style"
                                            required
                                        >
                                            <option value="" disabled>Select Style</option>
                                            <option v-for="opt in availableOptions.Style" :key="opt" :value="opt">{{ opt }}</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.style" />
                                    </div>

                                    <!-- Subject -->
                                    <div v-if="availableOptions.Subject">
                                        <InputLabel for="subject" value="Subject" />
                                        <select
                                            id="subject"
                                            class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                            v-model="form.subject"
                                            required
                                        >
                                            <option value="" disabled>Select Subject</option>
                                            <option v-for="opt in availableOptions.Subject" :key="opt" :value="opt">{{ opt }}</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.subject" />
                                    </div>

                                    <!-- Medium/Method -->
                                    <div v-if="availableOptions.Medium || availableOptions.Method">
                                        <InputLabel for="medium" :value="form.category === 'Sculpture' ? 'Method' : 'Medium'" />
                                        <select
                                            id="medium"
                                            class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                            v-model="form.medium"
                                            required
                                        >
                                            <option value="" disabled>Select {{ form.category === 'Sculpture' ? 'Method' : 'Medium' }}</option>
                                            <option v-for="opt in (availableOptions.Medium || availableOptions.Method)" :key="opt" :value="opt">{{ opt }}</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.medium" />
                                    </div>
                                </div>

                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Framing -->
                                <div>
                                    <InputLabel for="framing" value="Framing Status" />
                                    <select
                                        id="framing"
                                        class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                        v-model="form.framing"
                                    >
                                        <option>Unframed</option>
                                        <option>Framed</option>
                                        <option>Gallery Wrap</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.framing" />
                                </div>

                                <!-- Ready to Hang -->
                                <div class="flex items-center pt-8">
                                    <label class="flex items-center">
                                        <Checkbox name="ready_to_hang" v-model:checked="form.ready_to_hang" />
                                        <span class="ml-2 text-sm text-gray-600">Ready to Hang?</span>
                                    </label>
                                    <InputError class="mt-2" :message="form.errors.ready_to_hang" />
                                </div>
                            </div>

                            <!-- Dimensions -->
                            <div>
                                <h3 class="font-bold text-gray-700 text-sm mb-3 uppercase tracking-wider">Dimensions</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div>
                                        <InputLabel for="width" value="Width" />
                                        <TextInput id="width" type="number" step="0.1" class="mt-1 block w-full" v-model="form.width" placeholder="Width" required />
                                        <InputError class="mt-2" :message="form.errors.width" />
                                    </div>
                                    <div>
                                        <InputLabel for="height" value="Height" />
                                        <TextInput id="height" type="number" step="0.1" class="mt-1 block w-full" v-model="form.height" placeholder="Height" required />
                                        <InputError class="mt-2" :message="form.errors.height" />
                                    </div>
                                    <div>
                                        <InputLabel for="depth" value="Depth (Optional)" />
                                        <TextInput id="depth" type="number" step="0.1" class="mt-1 block w-full" v-model="form.depth" placeholder="Depth" />
                                        <InputError class="mt-2" :message="form.errors.depth" />
                                    </div>
                                    <div>
                                        <InputLabel for="unit" value="Unit" />
                                        <select id="unit" class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm" v-model="form.unit">
                                            <option value="cm">cm</option>
                                            <option value="in">inches</option>
                                        </select>
                                        <InputError class="mt-2" :message="form.errors.unit" />
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price -->
                             <div>
                                <InputLabel for="price" value="Price (₱)" />
                                <input
                                    id="price"
                                    type="text"
                                    class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                    :value="form.price ? form.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',') : ''"
                                    @input="(e) => {
                                        let val = e.target.value.replace(/,/g, '').replace(/[^0-9.]/g, '');
                                        // Prevent multiple dots
                                        if ((val.match(/\./g) || []).length > 1) {
                                            const parts = val.split('.');
                                            val = parts[0] + '.' + parts.slice(1).join('');
                                        }
                                        form.price = val;
                                        // Force re-format immediately for display
                                        e.target.value = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                    }"
                                    required
                                    placeholder="0.00"
                                />
                                <InputError class="mt-2" :message="form.errors.price" />
                                <p class="text-sm text-gray-500 mt-2 bg-yellow-50 p-3 rounded-md border border-yellow-200">
                                    <span class="font-bold text-yellow-800">Note:</span> Musea takes a 10% commission on all sales. You will receive 90% of the list price (approx. ₱{{ (form.price * 0.9).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}).
                                </p>
                            </div>

                             <!-- Stock (Hidden or default 1 usually for unique art, but let's keep it simple) -->
                            <!-- We assume unique art for now, but keeping field in case -->
                            
                            <!-- Description -->
                            <div>
                                <InputLabel for="description" value="Description" />
                                <textarea
                                    id="description"
                                    class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm"
                                    v-model="form.description"
                                    rows="4"
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>

                            <!-- Image Upload -->
                            <div>
                                <InputLabel for="image" value="Artwork Image" />
                                <input
                                    id="image"
                                    type="file"
                                    @change="handleImageUpload"
                                    accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-[#FFF9F0] file:text-[#CBA35C]
                                        hover:file:bg-[#FDFBF7]
                                    "
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.image" />
                            </div>

                            <div class="flex items-center justify-end">
                                <PrimaryButton :disabled="form.processing" class="bg-[#CBA35C] hover:bg-[#B89350] border-none text-gray-900">
                                    Submit for Review
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
