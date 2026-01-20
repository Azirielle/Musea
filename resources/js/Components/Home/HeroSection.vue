<script setup>
import { onMounted, ref, onUnmounted } from 'vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const props = defineProps({
    featured: Object
});

const heroContainer = ref(null);
const gridContainer = ref(null);
const subjectRef = ref(null);
const portalMask = ref(null);

// Placeholder artworks for the kinetic grid
const artworksSource = [
    'https://images.unsplash.com/photo-1579783902614-a3fb39279c65?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1549490349-8643362247b5?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1555445054-01aaa6096162?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1515405295579-ba7f9f92f413?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1569172122301-bc5008bc09c5?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1561214115-f2f134cc4912?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1578301978693-85fa9c0320b9?auto=format&fit=crop&w=500&q=80',
    'https://images.unsplash.com/photo-1629310629471-ee364303794e?auto=format&fit=crop&w=500&q=80',
];

// Double the artworks for seamless scrolling
const artworks = [...artworksSource, ...artworksSource];

const subjectImage = 'https://png.pngtree.com/png-vector/20230906/ourmid/pngtree-punk-boy-character-illustration-png-image_9961642.png'; 

let mouseX = 0;
let mouseY = 0;

const handleMouseMove = (e) => {
    const { innerWidth, innerHeight } = window;
    mouseX = (e.clientX / innerWidth) * 2 - 1;
    mouseY = (e.clientY / innerHeight) * 2 - 1;
};

// Animation Context for easy cleanup
let ctx;

onMounted(() => {
    // Enable "Animating on Twos" (approx 12-15fps) for that hand-drawn feel
    gsap.ticker.fps(15);
    
    ctx = gsap.context(() => {
        // 1. Initial 3D Tilt Setup
        gsap.set(gridContainer.value, {
            rotationX: 20,
            rotationY: -10,
            rotationZ: 5,
            scale: 1.5,
            transformPerspective: 1000,
            transformOrigin: "center center"
        });

        // 2. Infinite Loop Animation for rows
        const rows = gsap.utils.toArray('.grid-row');
        rows.forEach((row, i) => {
            const direction = i % 2 === 0 ? 1 : -1;
            
            // Allow GSAP to handle the infinite scroll logic
            // Since we doubled the content in the template, we just move -50%
            gsap.to(row, {
                xPercent: direction * -50,
                ease: "none",
                duration: 20,
                repeat: -1
            });
        });

        // 3. Mouse-Track Parallax (driven by ticker for shared FPS)
        gsap.ticker.add(() => {
            // Smoothly interpolate current values to target mouse values
            // We use standard lerp or just gsap.to logic, but since we have a low FPS ticker, 
            // direct assignment or simple easing works well.
            
            gsap.to(gridContainer.value, {
                rotationY: -10 + (mouseX * 5),
                rotationX: 20 - (mouseY * 5),
                x: -mouseX * 30,
                y: -mouseY * 30,
                duration: 1, // longer duration for smoothness even at low FPS
                overwrite: 'auto',
                ease: "power2.out"
            });

            gsap.to(subjectRef.value, {
                x: mouseX * 15,
                y: mouseY * 15,
                duration: 1,
                overwrite: 'auto',
                ease: "power2.out"
            });
        });

        // 4. Portal Transition (Dynamic Mask)
        gsap.fromTo(portalMask.value, 
            { clipPath: 'circle(35% at 50% 45%)' },
            {
                clipPath: 'circle(150% at 50% 50%)',
                scrollTrigger: {
                    trigger: heroContainer.value,
                    start: "top top",
                    end: "bottom top",
                    scrub: 1,
                    pin: true
                },
                ease: "none"
            }
        );
    }, heroContainer.value);

    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    gsap.ticker.fps(null); // Restore default FPS (60hz/Screen hz)
    if (ctx) ctx.revert(); // Cleanup GSAP animations/ScrollTriggers
});
</script>

<template>
    <section 
        ref="heroContainer" 
        class="relative w-full h-screen overflow-hidden bg-ink text-white perspective-container"
    >
        <!-- The Portal/Masked Container -->
        <!-- This container holds the chaotic background and is masked -->
        <div ref="portalMask" class="absolute inset-0 z-0 overflow-hidden bg-black portal-layer">
            
            <!-- RGB Split / Chromatic Aberration / Glitch Container -->
            <!-- We can simulate this by having the grid inside, and maybe a pseudo-element or filter -->
            <!-- For high energy, reusing the grid or using a CSS filter -->
            
            <div ref="gridContainer" class="absolute inset-[-50%] w-[200%] h-[200%] grid-container flex flex-col justify-center gap-8 opacity-80 decoration-slice">
                <!-- Rows of Artworks -->
                <div v-for="i in 5" :key="i" class="grid-row flex gap-8 whitespace-nowrap will-change-transform">
                    <!-- Artwork Cards -->
                    <div 
                        v-for="(art, index) in artworks" 
                        :key="index"
                        class="relative w-64 h-80 md:w-80 md:h-96 flex-shrink-0 rounded-lg overflow-hidden border-2 border-white/20 transform hover:scale-105 transition-transform duration-300 card-glitch"
                    >
                        <img :src="art" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" />
                        <div class="absolute inset-0 bg-accent/20 mix-blend-overlay"></div>
                    </div>
                </div>
            </div>

            <!-- Overlay Gradient for depth -->
            <div class="absolute inset-0 bg-radial-gradient from-transparent to-black/80 pointer-events-none"></div>
        </div>

        <!-- Static Foreground Subject -->
        <div class="absolute inset-0 z-10 flex items-center justify-center pointer-events-none">
            <div ref="subjectRef" class="relative w-[90%] h-[90%] md:w-[60%] md:h-[90%] flex items-end justify-center">
                 <!-- Subject Image -->
                 <img 
                    :src="subjectImage" 
                    alt="Hero Subject" 
                    class="h-full w-auto object-contain drop-shadow-[0_0_50px_rgba(255,255,255,0.2)]"
                 />
                 
                 <!-- Noise/Grain Overlay on Subject for texture -->
                 <div class="absolute inset-0 bg-noise mix-blend-overlay opacity-50"></div>
            </div>
        </div>

        <!-- Hero Content / Text (Overlaying everything) -->
        <div class="absolute inset-0 z-20 flex flex-col items-center justify-center text-center pointer-events-none mix-blend-difference">
            <h1 class="text-8xl md:text-[10rem] font-black tracking-tighter uppercase italic leading-[0.8]">
                <span class="block text-transparent stroke-text">Enter</span>
                <span class="block text-white">Musea</span>
            </h1>
            <p class="mt-6 text-xl md:text-2xl font-mono tracking-widest uppercase">The Digital Renaissance</p>
        </div>

        <!-- Glitch Overlay -->
        <div class="absolute inset-0 z-30 pointer-events-none bg-scanlines opacity-10"></div>
    </section>
</template>

<style scoped>
.perspective-container {
    perspective: 2000px;
}

.grid-container {
    transform-style: preserve-3d;
}

/* Chromatic Aberration on Cards */
.card-glitch {
    box-shadow: -2px 0 0 rgba(255,0,0,0.5), 2px 0 0 rgba(0,255,255,0.5);
}

/* Text Stroke Effect */
.stroke-text {
    -webkit-text-stroke: 2px white;
}

.bg-radial-gradient {
    background: radial-gradient(circle at center, transparent 20%, #000 100%);
}

.bg-noise {
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}

.bg-scanlines {
    background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2));
    background-size: 100% 4px;
}
</style>
