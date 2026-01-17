<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
    post: Object,
});

const form = useForm({
    _method: 'PUT',
    title: props.post.title || '',
    excerpt: props.post.excerpt || '',
    content: props.post.content || '',
    image: null,
    published_at: !!props.post.published_at,
});

const submit = () => {
    form.post(route('admin.journals.update', props.post.id), {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Journal Post" />

    <AdminLayout>
        <template #header>Edit Journal Post</template>

        <div class="max-w-3xl mx-auto">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                        <input 
                            id="title" 
                            v-model="form.title" 
                            type="text" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300" 
                            required 
                        />
                        <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">{{ form.errors.title }}</div>
                    </div>

                    <!-- Excerpt -->
                    <div>
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Excerpt</label>
                        <textarea 
                            id="excerpt" 
                            v-model="form.excerpt" 
                            rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300"
                        ></textarea>
                        <div v-if="form.errors.excerpt" class="text-red-500 text-xs mt-1">{{ form.errors.excerpt }}</div>
                    </div>

                    <!-- Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content</label>
                        <textarea 
                            id="content" 
                            v-model="form.content" 
                            rows="10" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-900 dark:border-gray-600 dark:text-gray-300" 
                            required
                        ></textarea>
                        <div v-if="form.errors.content" class="text-red-500 text-xs mt-1">{{ form.errors.content }}</div>
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cover Image</label>
                        <div v-if="post.image_url" class="mb-2">
                             <img :src="post.image_url" alt="Current Cover" class="h-32 w-auto object-cover rounded-md border border-gray-200" />
                             <p class="text-xs text-gray-500 mt-1">Current Image</p>
                        </div>
                        <input 
                            id="image" 
                            type="file" 
                            @input="form.image = $event.target.files[0]"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-300" 
                            accept="image/*"
                        />
                        <div v-if="form.errors.image" class="text-red-500 text-xs mt-1">{{ form.errors.image }}</div>
                    </div>

                    <!-- Publish -->
                    <div class="flex items-center">
                        <input 
                            id="published_at" 
                            v-model="form.published_at" 
                            type="checkbox" 
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" 
                        />
                        <label for="published_at" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Published
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                        <Link :href="route('admin.journals.index')" class="text-gray-600 hover:text-gray-900 dark:text-gray-400">Cancel</Link>
                        <button 
                            type="submit" 
                            :disabled="form.processing" 
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Update Post' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
