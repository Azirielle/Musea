<script setup>
const categories = [
    {
        id: 1,
        name: 'Painting',
        image: 'https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?q=80&w=1000&auto=format&fit=crop',
        link: '#'
    },
    {
        id: 2,
        name: 'Sculpture',
        image: 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?q=80&w=1000&auto=format&fit=crop',
        link: '#'
    },
    {
        id: 3,
        name: 'Photography',
        image: 'https://images.unsplash.com/photo-1552168324-d612d77725e3?q=80&w=1000&auto=format&fit=crop',
        link: '#'
    },
    {
        id: 4,
        name: 'Digital Art',
        image: 'https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=1000&auto=format&fit=crop',
        link: '#'
    }
];
</script>

<template>
    <section class="w-full max-w-7xl mx-auto px-4 py-16">
        <h2 class="text-3xl md:text-4xl font-serif font-bold text-gray-900 mb-10 text-center">Browse by Category</h2>
        
        <!-- Grid Container with 'group' to manage the spotlight effect across siblings -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 h-[600px] group">
            
            <a 
                v-for="category in categories" 
                :key="category.id"
                :href="category.link"
                class="relative h-full overflow-hidden rounded-2xl cursor-pointer transition-all duration-700 ease-out
                       /* Default State */
                       opacity-100 scale-100 filter-none
                       /* Sibling Hover State (managed by parent .group:hover) */
                       group-hover:opacity-50 group-hover:blur-[2px] group-hover:scale-95
                       /* Self Hover State (Overriding sibling state) */
                       hover:!opacity-100 hover:!blur-none hover:!scale-105 hover:!z-10 hover:shadow-2xl"
            >
                <!-- Background Image -->
                <img 
                    :src="category.image" 
                    :alt="category.name" 
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-700"
                />

                <!-- Gradient Overlay for Text Readability -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-80 transition-opacity duration-300"></div>

                <!-- Text Content -->
                <div class="absolute bottom-0 left-0 p-8 w-full transform transition-transform duration-500 hover:translate-x-2">
                    <h3 class="text-3xl md:text-4xl font-serif text-white tracking-wide">
                        {{ category.name }}
                    </h3>
                    <p class="mt-2 text-indigo-300 font-medium tracking-wider text-sm uppercase opacity-0 transform translate-y-4 transition-all duration-500 group-hover:opacity-100 group-hover:translate-y-0 delay-100">
                        View Collection &rarr;
                    </p>
                </div>
            </a>

        </div>
    </section>
</template>

<style scoped>
/* Optional: If you want a specific aspect ratio instead of fixed height on mobile */
@media (max-width: 768px) {
    .grid {
        height: auto;
    }
    .grid > a {
        height: 300px; /* Tall cards on mobile too */
    }
}
</style>
