<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { normalizeSameOriginUrl } from '@/utils/urls';

defineProps({
    artworks: Object
});
</script>

<template>
    <Head title="My Artworks" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Artworks</h2>
                <Link :href="route('dashboard.artworks.create')" class="bg-[#CBA35C] hover:bg-[#B89350] text-white px-4 py-2 rounded-md text-sm font-medium transition">
                    Upload New Artwork
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div v-if="artworks.data.length === 0" class="text-center py-12 text-gray-500">
                            You haven't uploaded any artworks yet.
                        </div>

                        <div v-else class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artwork</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Actions</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="artwork in artworks.data" :key="artwork.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex-shrink-0 h-16 w-16">
                                                <img class="h-16 w-16 rounded object-cover" :src="artwork.image_url" alt="">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 text-wrap">{{ artwork.title }}</div>
                                            <div class="text-sm text-gray-500">{{ artwork.category }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">₱{{ artwork.price }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div v-if="artwork.stock <= 0">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-black text-white">
                                                    Sold Out
                                                </span>
                                            </div>
                                            <div v-else>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                                    :class="{
                                                        'bg-green-100 text-green-800': artwork.status === 'active',
                                                        'bg-yellow-100 text-yellow-800': artwork.status === 'pending',
                                                        'bg-red-100 text-red-800': artwork.status === 'declined',
                                                        'bg-gray-100 text-gray-800': artwork.status === 'archived'
                                                    }">
                                                    {{ artwork.status.charAt(0).toUpperCase() + artwork.status.slice(1) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(artwork.created_at).toLocaleDateString() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <Link :href="route('dashboard.artworks.destroy', artwork.id)" method="delete" as="button" class="text-red-600 hover:text-red-900 ml-4">Delete</Link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                         <!-- Pagination -->
                        <div class="mt-6 flex justify-center gap-2" v-if="artworks.links.length > 3">
                             <template v-for="(link, k) in artworks.links" :key="k">
                                <Link 
                                    v-if="link.url" 
                                    :href="normalizeSameOriginUrl(link.url)" 
                                    v-html="link.label"
                                    class="px-4 py-2 rounded-lg border text-sm font-medium transition"
                                    :class="{'bg-[#CBA35C] text-white border-[#CBA35C]': link.active, 'bg-white text-gray-700 hover:bg-gray-50 border-gray-300': !link.active}"
                                />
                                <span v-else v-html="link.label" class="px-4 py-2 text-gray-400 text-sm"></span>
                             </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
