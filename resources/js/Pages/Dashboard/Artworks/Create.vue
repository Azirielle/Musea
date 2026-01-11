<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { ref, watch } from 'vue';

const form = useForm({
    title: '',
    description: '',
    category: 'Painting',
    subcategory: '',
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

// Category to Subcategory Mapping
// Ideally this comes from the backend or the Enum, but for frontend responsiveness we define it here.
const subcategories = {
    'Painting': ['Oil', 'Acrylic', 'Watercolor', 'Abstract', 'Portrait'],
    'Digital': ['3D Render', 'Vector', 'AI Art', 'Pixel Art'],
    'Sculpture': ['Metal', 'Wood', 'Resin', 'Ceramic'],
    'Photography': [],
    'Mixed Media': [],
    'Drawing': ['Graphite', 'Charcoal'],
    'Other': []
};

const availableSubcategories = ref(subcategories['Painting']);

watch(() => form.category, (newCategory) => {
    availableSubcategories.value = subcategories[newCategory] || [];
    form.subcategory = ''; // Reset subcategory when category changes
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
                                        <option v-for="(subs, cat) in subcategories" :key="cat" :value="cat">{{ cat }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.category" />
                                </div>

                                <!-- Subcategory -->
                                <div>
                                    <InputLabel for="subcategory" value="Medium / Style" />
                                    <select
                                        id="subcategory"
                                        class="mt-1 block w-full border-gray-300 focus:border-[#CBA35C] focus:ring-[#CBA35C] rounded-md shadow-sm disabled:bg-gray-100"
                                        v-model="form.subcategory"
                                        :disabled="availableSubcategories.length === 0"
                                    >
                                        <option value="" disabled>Select Subcategory</option>
                                        <option v-for="sub in availableSubcategories" :key="sub" :value="sub">{{ sub }}</option>
                                    </select>
                                    <InputError class="mt-2" :message="form.errors.subcategory" />
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
                                <TextInput
                                    id="price"
                                    type="number"
                                    step="0.01"
                                    class="mt-1 block w-full"
                                    v-model="form.price"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.price" />
                                <p class="text-sm text-gray-500 mt-2 bg-yellow-50 p-3 rounded-md border border-yellow-200">
                                    <span class="font-bold text-yellow-800">Note:</span> Musea takes a 10% commission on all sales. You will receive 90% of the list price (approx. ₱{{ (form.price * 0.9).toFixed(2) }}).
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
