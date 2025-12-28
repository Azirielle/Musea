<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <footer class="bg-accent text-white pt-16 pb-8 border-t border-zinc-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand Section -->
                <div class="col-span-1 md:col-span-1">
                    <Link href="/" class="flex items-center gap-2 mb-6 group">
                        <!-- Using filter to invert the logo if it is black text, assuming it is compatible -->
                        <img src="/images/logo/Musea.png" alt="Musea" class="h-12 w-auto brightness-0 invert opacity-90 group-hover:opacity-100 transition" />
                    </Link>
                    <p class="text-zinc-400 text-sm leading-relaxed mb-6 max-w-xs">
                        Connecting passionate creators with those who appreciate the beauty of handmade craftsmanship.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400 hover:bg-white hover:text-accent transition duration-300">
                            <span class="sr-only">Instagram</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400 hover:bg-white hover:text-accent transition duration-300">
                             <span class="sr-only">Twitter</span>
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-400 hover:bg-white hover:text-accent transition duration-300">
                             <span class="sr-only">Facebook</span>
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: Explore -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Explore</h4>
                    <ul class="space-y-4 text-zinc-400">
                        <li><Link href="/shop" class="hover:text-white transition">Shop All</Link></li>
                        <li><Link href="/artists" class="hover:text-white transition">Featured Artists</Link></li>
                        <li><Link href="/shop?category=canvas" class="hover:text-white transition">Canvas Prints</Link></li>
                        <li><Link href="/shop?category=sculpture" class="hover:text-white transition">Sculptures</Link></li>
                    </ul>
                </div>

                <!-- Column 3: Company -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Company</h4>
                    <ul class="space-y-4 text-zinc-400">
                        <li><Link href="/about" class="hover:text-white transition">About Us</Link></li>
                        <li><Link href="/contact" class="hover:text-white transition">Contact</Link></li>
                        <li><Link href="/contact#faq" class="hover:text-white transition">FAQ</Link></li>
                        <li><Link href="/about#terms" class="hover:text-white transition">Terms of Service</Link></li>
                         <li><Link href="/about#privacy" class="hover:text-white transition">Privacy Policy</Link></li>
                    </ul>
                </div>

                <!-- Column 4: Newsletter -->
                <div>
                    <h4 class="text-white font-bold text-lg mb-6">Stay Inspired</h4>
                    <p class="text-zinc-400 text-sm mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
                    <form @submit.prevent class="flex flex-col gap-2">
                         <input type="email" placeholder="Enter your email" class="w-full bg-zinc-900 border border-zinc-700 rounded-lg px-4 py-2.5 text-white placeholder-zinc-500 focus:outline-none focus:border-white transition" />
                         <button type="submit" class="w-full bg-white text-accent font-bold py-2.5 rounded-lg hover:bg-zinc-200 transition">Subscribe</button>
                    </form>
                </div>
            </div>

            <div class="border-t border-zinc-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-zinc-500">
                <p>&copy; 2024 Musea. All rights reserved.</p>
                 <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="hover:text-white transition">Privacy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                    <a href="#" class="hover:text-white transition">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>
</template>
