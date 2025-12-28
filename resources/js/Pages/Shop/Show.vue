<script setup>
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';

const props = defineProps({
    artwork: Object,
    isFollowing: Boolean,
    isLiked: Boolean,
    errors: Object, // for flash messages or errors
});

import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Swal from 'sweetalert2';

// Local state for social interactions to optimize UI responsiveness
const following = ref(props.isFollowing);
const liked = ref(props.isLiked);

const toggleFollow = () => {
    if (!props.artwork.artist) return;
    
    const method = following.value ? 'post' : 'post'; // Both are post but different endpoints
    const url = following.value 
        ? route('artists.unfollow', props.artwork.artist.id) 
        : route('artists.follow', props.artwork.artist.id);

    router.visit(url, {
        method: 'post',
        preserveScroll: true,
        onSuccess: () => {
            following.value = !following.value;
        }
    });
};

const toggleLike = () => {
    const url = liked.value 
        ? route('artworks.unlike', props.artwork.id)
        : route('artworks.like', props.artwork.id);

    router.visit(url, {
        method: 'post',
        preserveScroll: true,
        onSuccess: () => {
            liked.value = !liked.value;
        }
    });
};

// Reviews Logic
const reviewForm = ref({
    rating: 5,
    comment: '',
    image: null
});

const submitReview = () => {
    const form = new FormData();
    form.append('rating', reviewForm.value.rating);
    form.append('comment', reviewForm.value.comment);
    if (reviewForm.value.image) {
        form.append('image', reviewForm.value.image);
    }

    router.post(route('artworks.review', props.artwork.id), form, {
        onSuccess: () => {
            reviewForm.value = { rating: 5, comment: '', image: null };
            Swal.fire('Success', 'Review submitted!', 'success');
        },
        onError: (errors) => {
            Swal.fire('Error', errors.message || 'Validation failed', 'error');
        }
    });
};

const handleReviewImage = (e) => {
    reviewForm.value.image = e.target.files[0];
};

import { useCart } from '@/composables/useCart';
const { addToCart } = useCart();
</script>

<template>
    <Head :title="artwork.title" />
    <MainLayout>
        <div class="pt-24 pb-12 px-6 bg-canvas min-h-screen flex items-center">
            <div class="max-w-6xl mx-auto w-full bg-paper rounded-2xl shadow-sm border border-divider overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="bg-zinc-50 relative overflow-hidden group">
                         <img 
                            :src="artwork.image_url || '/images/placeholder-art.jpg'" 
                            :alt="artwork.title" 
                            class="w-full h-full object-cover min-h-[500px]"
                        >
                        <!-- Like Button -->
                        <button 
                            @click="toggleLike" 
                            class="absolute top-4 right-4 p-3 rounded-full shadow-lg transition-transform hover:scale-110 active:scale-90"
                            :class="liked ? 'bg-red-500 text-white' : 'bg-white text-gray-400 hover:text-red-500'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" :fill="liked ? 'currentColor' : 'none'" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-8 md:p-12 flex flex-col justify-center relative">
                        <div class="flex items-center gap-3 mb-4">
                             <span class="text-xs font-bold tracking-wider uppercase bg-accent text-white px-3 py-1 rounded-full">{{ artwork.category }}</span>
                             <span :class="artwork.stock > 0 ? 'text-green-600' : 'text-red-500'" class="text-sm font-medium">
                                {{ artwork.stock > 0 ? 'In Stock' : 'Out of Stock' }}
                             </span>
                        </div>
                        
                        <h1 class="text-4xl md:text-5xl font-bold text-[#1A1A1A] mb-4">{{ artwork.title }}</h1>
                        
                        <!-- Semi-Blurred Details Section -->
                        <div class="relative mb-8">
                            <div :class="{ 'blur-sm opacity-50 select-none': !$page.props.auth.user }">
                                <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100">
                                     <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-lg font-bold text-gray-500">
                                        {{ artwork.artist.first_name[0] }}
                                     </div>
                                     <div>
                                        <p class="text-sm text-gray-500">Created by</p>
                                        <p class="font-bold text-[#1A1A1A]">{{ artwork.artist.first_name }} {{ artwork.artist.last_name }}</p>
                                     </div>
                                     <button 
                                        v-if="$page.props.auth.user && $page.props.auth.user.id !== artwork.artist.id"
                                        @click="toggleFollow" 
                                        class="ml-auto px-4 py-1.5 rounded-full text-xs font-bold border transition-colors"
                                        :class="following ? 'bg-gray-100 text-gray-800 border-gray-300' : 'bg-black text-white border-black hover:bg-gray-800'"
                                    >
                                        {{ following ? 'Following' : 'Follow' }}
                                    </button>
                                </div>

                                <p class="text-gray-600 leading-relaxed text-lg">
                                    {{ artwork.description || 'No description available for this masterpiece.' }}
                                </p>
                            </div>
                            
                            <!-- Overlay just for the description/artist part -->
                            <div v-if="!$page.props.auth.user" class="absolute inset-0 flex items-center justify-center z-10">
                                <div class="bg-white/80 backdrop-blur-md px-6 py-3 rounded-full shadow-lg border border-gray-100 text-center">
                                    <p class="text-sm font-bold text-[#1A1A1A] mb-1">Description Hidden</p>
                                    <Link :href="route('login')" class="text-xs text-accent font-bold hover:underline">Login to read full story</Link>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-auto border-t pt-6">
                            <div class="text-3xl font-bold text-[#1A1A1A]">₱{{ artwork.price }}</div>
                            <button 
                                v-if="artwork.stock > 0"
                                @click="addToCart(artwork)"
                                class="bg-[#1A1A1A] text-white px-8 py-4 rounded-xl font-bold hover:bg-[#333] transition transform hover:-translate-y-1 shadow-lg"
                            >
                                Add to Cart
                            </button>
                            <button 
                                v-else 
                                disabled
                                class="bg-gray-300 text-gray-500 px-8 py-4 rounded-xl font-bold cursor-not-allowed"
                            >
                                Sold Out
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="max-w-6xl mx-auto px-6 pb-20">
            <h2 class="text-2xl font-bold mb-8">Community Reviews</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Write Review -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm h-fit">
                    <h3 class="text-lg font-bold mb-4">Leave a Review</h3>
                    <form @submit.prevent="submitReview" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                            <div class="flex gap-2">
                                <button type="button" v-for="i in 5" :key="i" @click="reviewForm.rating = i" class="text-2xl focus:outline-none transition-colors" :class="i <= reviewForm.rating ? 'text-yellow-400' : 'text-gray-300'">
                                    ★
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Show us your art setup!</label>
                             <input type="file" @change="handleReviewImage" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Comment</label>
                            <textarea v-model="reviewForm.comment" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-black focus:ring-black" placeholder="How was your experience?"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-black text-white py-2 rounded-lg font-bold hover:bg-gray-800">Submit Review</button>
                    </form>
                </div>

                <!-- Reviews List -->
                <div class="space-y-6">
                    <div v-if="artwork.reviews.length === 0" class="text-gray-500 italic">No reviews yet. Be the first!</div>
                    <div v-for="review in artwork.reviews" :key="review.id" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm flex gap-4">
                        <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center font-bold text-gray-600 flex-shrink-0">
                            {{ review.user.first_name[0] }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-bold">{{ review.user.first_name }}</span>
                                <div class="text-yellow-400 text-sm">
                                    <span v-for="n in 5" :key="n">{{ n <= review.rating ? '★' : '☆' }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mb-3">{{ review.comment }}</p>
                            <img v-if="review.image" :src="'/storage/' + review.image" class="h-24 rounded-lg object-cover border border-gray-200">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
