<script setup>
import { onMounted, ref, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import gsap from 'gsap';

// Placeholder artwork data for the grid
// Ideally this would come from props, but for the hero we want a curated look.
const heroArtworks = [
    { id: 1, src: 'https://images.unsplash.com/photo-1579783902614-a3fb39279c23?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-[3/4]', title: 'Abstract Harmony' },
    { id: 2, src: 'https://images.unsplash.com/photo-1549887552-93f964db3309?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-square', title: 'Mountain Vista' },
    { id: 3, src: 'https://images.unsplash.com/photo-1578301978018-3528b291a27e?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-[3/4]', title: 'Golden Hour' },
    { id: 4, src: 'https://images.unsplash.com/photo-1582201942988-13e60e4556ee?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-[4/3]', title: 'Urban Geometry' },
    { id: 5, src: 'https://images.unsplash.com/photo-1576769267415-9642010aa962?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-square', title: 'Detailed Texture' },
    { id: 6, src: 'https://images.unsplash.com/photo-1536924940846-227afb31e2a5?auto=format&fit=crop&q=80&w=600', aspect: 'aspect-[3/4]', title: 'Color Splash' },
];

const gridRef = ref(null);
let ctx;

onMounted(() => {
    // Use GSAP Context for proper cleanup
    ctx = gsap.context(() => {
        // Subtle float animation for grid items
        const cards = gridRef.value.querySelectorAll('.art-card');
        
        gsap.fromTo(cards, 
            { 
                y: 50, 
                opacity: 0 
            },
            {
                y: 0,
                opacity: 1,
                duration: 1.2,
                stagger: 0.1,
                ease: "power3.out",
                onComplete: () => {
                    // Continuous floating motion
                    cards.forEach((card, i) => {
                        gsap.to(card, {
                            y: i % 2 === 0 ? -15 : 15,
                            duration: 3 + i * 0.5,
                            yoyo: true,
                            repeat: -1,
                            ease: "sine.inOut"
                        });
                    });
                }
            }
        );
    }, gridRef.value);
});

onUnmounted(() => {
    if (ctx) ctx.revert();
});
</script>

<template>
    <section class="relative w-full min-h-[90vh] bg-canvas text-ink flex flex-col md:flex-row items-center border-b border-divider overflow-hidden">
        
        <!-- Left Content (Text & CTA) -->
        <div class="w-full md:w-1/2 p-6 md:p-16 lg:p-24 z-10 flex flex-col items-start justify-center text-left">
            <h1 class="font-serif text-5xl md:text-6xl lg:text-7xl leading-tight font-bold mb-6 text-ink">
                Discover & Collect <br />
                <span class="text-accent italic">Unique Art</span>
            </h1>
            
            <p class="text-ink-light text-lg md:text-xl max-w-lg mb-10 leading-relaxed">
                Explore a curated marketplace of masterpieces from verified independent artists. Find the perfect piece that speaks to you.
            </p>
            
            <div class="flex flex-wrap items-center gap-4 mb-12">
                <Link 
                    href="/shop" 
                    class="px-8 py-4 bg-ink text-white rounded-full font-medium text-sm tracking-wide hover:bg-ink-light transition-all transform hover:-translate-y-1 shadow-lg shadow-ink/20"
                >
                    Start Collecting
                </Link>
                <Link 
                    href="/register" 
                    class="px-8 py-4 bg-transparent border border-ink/20 text-ink rounded-full font-medium text-sm tracking-wide hover:border-ink hover:bg-white transition-all"
                >
                    Sell Your Art
                </Link>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img class="w-10 h-10 rounded-full border-2 border-canvas object-cover" src="https://ui-avatars.com/api/?name=Alex+R&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-canvas object-cover" src="https://ui-avatars.com/api/?name=Sarah+M&background=random" alt="User">
                    <img class="w-10 h-10 rounded-full border-2 border-canvas object-cover" src="https://ui-avatars.com/api/?name=James+L&background=random" alt="User">
                    <div class="w-10 h-10 rounded-full border-2 border-canvas bg-gray-100 flex items-center justify-center text-xs font-bold text-ink-light">
                        10k+
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="flex text-yellow-500 text-sm">
                        ★★★★★
                    </div>
                    <span class="text-xs font-medium text-ink-light">Trusted by collectors & artists</span>
                </div>
            </div>
        </div>

        <!-- Right Content (Dynamic Art Grid) -->
        <div class="w-full md:w-1/2 h-[50vh] md:h-screen relative bg-gray-50/50">
            <!-- Background Blob -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-accent/5 rounded-full blur-[100px]"></div>

            <!-- Tilted Grid Container -->
            <div ref="gridRef" class="w-full h-full p-8 grid grid-cols-2 md:grid-cols-3 gap-4 md:gap-6 content-center transform rotate-0 scale-100 md:-rotate-6 md:scale-110 origin-center opacity-90 hover:opacity-100 transition-opacity duration-700">
                <div 
                    v-for="art in heroArtworks" 
                    :key="art.id" 
                    class="art-card relative overflow-hidden rounded-xl shadow-xl transition-transform hover:scale-105 hover:z-20 cursor-default"
                    :class="art.aspect"
                >
                    <img 
                        :src="art.src" 
                        :alt="art.title" 
                        class="w-full h-full object-cover"
                    />
                    <!-- Overlay on hover -->
                    <div class="absolute inset-0 bg-black/40 opacity-0 hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <span class="text-white text-xs font-bold tracking-widest uppercase border border-white/50 px-3 py-1 rounded-full backdrop-blur-sm">View</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Optional: Adding some noise texture to the background for that premium paper feel */
.bg-canvas {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.03'/%3E%3C/svg%3E");
    background-blend-mode: overlay;
}
</style>
