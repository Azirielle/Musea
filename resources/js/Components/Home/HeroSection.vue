<script setup>
import { onMounted, ref, onUnmounted } from 'vue';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const heroContainer = ref(null);
const heroText = ref(null);
const heroSubject = ref(null);
const circleRef = ref(null);

let mouseX = 0;
let mouseY = 0;

const handleMouseMove = (e) => {
    const { innerWidth, innerHeight } = window;
    mouseX = (e.clientX / innerWidth) * 2 - 1;
    mouseY = (e.clientY / innerHeight) * 2 - 1;
};

let ctx;

onMounted(() => {
    ctx = gsap.context(() => {
        // Initial Entrance
        const tl = gsap.timeline();
        
        tl.from(heroText.value, {
            y: 100,
            opacity: 0,
            duration: 1.5,
            ease: "power4.out",
            skewY: 5
        })
        .from(heroSubject.value, {
            y: 50,
            opacity: 0,
            duration: 1.5,
            ease: "power3.out"
        }, "-=1.2")
        .from('.hero-meta', {
            opacity: 0,
            y: 20,
            duration: 1,
            stagger: 0.1,
            ease: "power2.out"
        }, "-=1");

        // Mouse Parallax Loop
        gsap.ticker.add(() => {
            // Text moves slightly opposite to mouse
            gsap.to(heroText.value, {
                x: -mouseX * 20,
                y: -mouseY * 20,
                duration: 1,
                ease: "power2.out",
                overwrite: "auto"
            });

            // Subject moves more (Foreground feel)
            gsap.to(heroSubject.value, {
                x: -mouseX * 40,
                y: -mouseY * 40,
                duration: 1.2,
                ease: "power2.out",
                overwrite: "auto"
            });
            
             // Geometric accents move differently
            gsap.to(circleRef.value, {
                x: mouseX * 60,
                y: mouseY * 60,
                rotation: mouseX * 10,
                duration: 1.5,
                ease: "power2.out",
                overwrite: "auto"
            });
        });

        // Scroll Parallax
        gsap.to(heroSubject.value, {
            yPercent: 20,
            ease: "none",
            scrollTrigger: {
                trigger: heroContainer.value,
                start: "top top",
                end: "bottom top",
                scrub: true
            }
        });
        
         gsap.to(heroText.value, {
            yPercent: -10,
            scale: 1.05,
            opacity: 0,
            ease: "none",
            scrollTrigger: {
                trigger: heroContainer.value,
                start: "top top",
                end: "bottom center",
                scrub: true
            }
        });

    }, heroContainer.value);

    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    if (ctx) ctx.revert();
});
</script>

<template>
    <section 
        ref="heroContainer" 
        class="relative w-full h-screen overflow-hidden bg-[#050505] text-white flex items-center justify-center"
    >
        <!-- Background Ambient Glow -->
        <div class="absolute inset-0 z-0">
             <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80vw] h-[80vw] bg-red-900/10 rounded-full blur-[100px] mix-blend-screen opacity-60"></div>
        </div>
        
        <!-- Geometric Accents (Floating Circles/Lines) -->
         <div ref="circleRef" class="absolute inset-0 z-0 pointer-events-none opacity-20">
             <!-- Left Circles -->
             <div class="absolute bottom-20 left-10 md:left-20 flex gap-4">
                 <div class="w-12 h-12 rounded-full border border-red-500/30 hero-meta"></div>
                 <div class="w-12 h-12 rounded-full border border-red-500/30 hero-meta"></div>
                 <div class="w-12 h-12 rounded-full border border-red-500/30 hero-meta"></div>
                 <div class="w-12 h-12 rounded-full border border-white/10 hero-meta"></div>
             </div>
             
             <!-- Right Lines -->
             <div class="absolute top-40 right-10 md:right-20 flex flex-col gap-2 hero-meta">
                 <div class="w-32 h-[1px] bg-red-500/50"></div>
                 <div class="w-20 h-[1px] bg-white/20 self-end"></div>
             </div>
         </div>

        <!-- Typography Layer (Check z-index, should be behind subject but visible) -->
        <div 
            ref="heroText"
            class="absolute z-10 text-center select-none mix-blend-overlay md:mix-blend-normal"
        >
            <h1 class="font-serif text-[15vw] leading-[0.8] text-white/10 font-bold tracking-tighter shimmer-text">
                MUSEA
            </h1>
        </div>

        <!-- Hero Subject Layer -->
        <!-- Image should contain the artist w/ transparent bg -->
        <div class="relative z-20 h-[90vh] w-auto flex items-end justify-center pointer-events-none mb-[-5vh]">
            <div ref="heroSubject" class="h-full w-auto">
                <img 
                    src="/images/hero-artist.png" 
                    alt="The Artist" 
                    class="h-full w-auto object-contain drop-shadow-[0_20px_50px_rgba(0,0,0,0.8)]"
                />
            </div>
        </div>

        <!-- Foreground Content / CTA -->
        <div class="absolute bottom-12 z-30 w-full flex flex-col items-center justify-center gap-4 hero-meta">
            <p class="text-xs md:text-sm font-mono tracking-[0.3em] uppercase text-white/50">
                The Digital Renaissance
            </p>
            
             <button class="group relative px-8 py-3 overflow-hidden rounded-full border border-white/20 hover:border-red-500/50 transition-colors duration-300">
                <span class="absolute inset-0 bg-gradient-to-r from-red-900/20 to-black opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                <span class="relative font-mono text-sm tracking-widest uppercase group-hover:text-red-300 transition-colors">
                    Enter Gallery
                </span>
            </button>
        </div>
        
    </section>
</template>

<style scoped>
/* 
  Since we want that "Spiderman" cinematic text feel, 
  we might want to use a specific font. 
  "shimmer-text" could be a subtle gradient loop.
*/

.font-serif {
    font-family: 'Playfair Display', serif;
}

.shimmer-text {
    /* Optional: subtle texture solely on the big text */
    background: linear-gradient(
        to right, 
        rgba(255,255,255,0.1) 0%, 
        rgba(255,255,255,0.3) 50%, 
        rgba(255,255,255,0.1) 100%
    );
    background-size: 200% auto;
    color: transparent;
    -webkit-background-clip: text;
    background-clip: text;
    animation: shimmer 5s infinite linear;
}

@keyframes shimmer {
    to {
        background-position: 200% center;
    }
}
</style>
