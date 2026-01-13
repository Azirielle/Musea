<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    posts: Object,
});

const deletePost = (post) => {
    if (confirm('Are you sure you want to delete this journal post?')) {
        router.delete(route('admin.journals.destroy', post.id));
    }
};
</script>

<template>
    <Head title="Journal Management" />

    <AdminLayout>
        <template #header>Journal Management</template>

        <div class="mb-6 flex justify-between items-center">
            <Link :href="route('admin.dashboard')" class="text-indigo-600 hover:text-indigo-900 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to Dashboard
            </Link>

            <Link :href="route('admin.journals.create')" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-bold hover:bg-indigo-700 transition">
                Create New Post
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Title</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Author</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Published</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-200 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="post in posts.data" :key="post.id">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0 mr-4" v-if="post.image_url">
                                            <img class="h-10 w-10 rounded-md object-cover" :src="post.image_url" alt="" />
                                        </div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ post.title }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500 dark:text-gray-300">
                                        {{ post.author ? post.author.first_name + ' ' + post.author.last_name : 'Unknown' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="post.published_at" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        {{ new Date(post.published_at).toLocaleDateString() }}
                                    </span>
                                    <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Draft
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('admin.journals.edit', post.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</Link>
                                    <button @click="deletePost(post)" class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                            <tr v-if="posts.data.length === 0">
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    No journal posts found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                 <div class="mt-6">
                    <Pagination :links="posts.links" />
                 </div>
            </div>
        </div>
    </AdminLayout>
</template>
