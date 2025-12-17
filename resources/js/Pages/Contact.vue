<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import { ref } from 'vue';

const form = useForm({
    full_name: '',
    email: '',
    phone: '',
    subject: '',
    reason: '',
    order_number: '',
    preferred_contact: 'email',
    message: '',
    consent: false,
});

const formStatus = ref(null); // { type: 'success' | 'error', message: '' }

const submit = () => {
    // Simulate submission for now, as backend endpoint might not be ready or we are just doing UI migration
    // In a real app, this would be form.post(route('contact.store'), ...)
    
    // Basic client-side validation check (Inertia useForm usually handles this with server errors, 
    // but legacy had immediate JS validation. We'll rely on HTML5 required attrs + simple check)
    if (!form.full_name || !form.email || !form.subject || !form.reason || !form.message || !form.consent) {
         // Should be caught by HTML5 validation, but just in case
        return;
    }

    // Simulate network delay
    setTimeout(() => {
        formStatus.value = { type: 'success', message: 'Thank you! Your message was sent. Reference ID: ' + Math.floor(Math.random() * 10000) };
        form.reset();
    }, 800);
};

// FAQ State
const faqs = ref([
    { q: 'When will I receive a response?', a: 'We reply within 1 business day. During peak seasons, replies may take up to 48 hours.', open: false },
    { q: 'How do I modify or cancel an order?', a: 'Choose "Order issue" as the reason and include your order number; we’ll help right away.', open: false },
    { q: 'Do you ship internationally?', a: 'Yes, we ship to many countries. Shipping options and costs are shown at checkout.', open: false },
]);

const toggleFaq = (index) => {
    faqs.value[index].open = !faqs.value[index].open;
};
</script>

<template>
    <Head title="Contact Us" />
    <MainLayout>
        <!-- Hero Section matches legacy gradient -->
        <div class="bg-gradient-to-br from-[#F5E9DC] to-[#EAEAEA] py-16 px-6 text-center">
             <h1 class="text-4xl md:text-5xl font-bold text-[#1A1A1A] mb-3">Contact Us</h1>
             <p class="text-xl text-[#2E2E2E]">Get in touch with our team</p>
        </div>

        <div class="max-w-7xl mx-auto px-6 py-12">
            <div class="contact-layout grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
                
                <!-- Contact Info -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Get in Touch</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex items-start gap-2">
                             <strong class="text-[#CBA35C] w-20 shrink-0">Email:</strong>
                             <span class="text-gray-700">museaofficial@gmail.com</span>
                        </div>
                        <div class="flex items-start gap-2">
                             <strong class="text-[#CBA35C] w-20 shrink-0">Phone:</strong>
                             <span class="text-gray-700">+1 (555) 123-4567</span>
                        </div>
                        <div class="flex items-start gap-2">
                             <strong class="text-[#CBA35C] w-20 shrink-0">Address:</strong>
                             <span class="text-gray-700">456 Artisan Lane, Creativity City, CA 90210</span>
                        </div>
                        <div class="flex items-start gap-2">
                             <strong class="text-[#CBA35C] w-20 shrink-0">Hours:</strong>
                             <span class="text-gray-700">Mon–Fri 9:00–18:00 PST, Sat 10:00–16:00</span>
                        </div>
                    </div>
                    
                    <p class="text-sm text-gray-500 mb-6 italic">Typical response time: within 1 business day.</p>
                    
                    <div class="border-t border-dashed border-gray-200 pt-6 space-y-2 mb-6">
                        <div class="text-sm"><strong class="text-gray-800">Customer Support:</strong> support@musea.example</div>
                        <div class="text-sm"><strong class="text-gray-800">Wholesale:</strong> wholesale@musea.example</div>
                        <div class="text-sm"><strong class="text-gray-800">Press:</strong> press@musea.example</div>
                    </div>

                    <div class="flex gap-3">
                         <a href="#" class="px-4 py-2 bg-[#EAEAEA] rounded-full text-sm font-bold text-[#1A1A1A] hover:bg-[#dcdcdc] transition">Instagram</a>
                         <a href="#" class="px-4 py-2 bg-[#EAEAEA] rounded-full text-sm font-bold text-[#1A1A1A] hover:bg-[#dcdcdc] transition">Facebook</a>
                         <a href="#" class="px-4 py-2 bg-[#EAEAEA] rounded-full text-sm font-bold text-[#1A1A1A] hover:bg-[#dcdcdc] transition">Twitter/X</a>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-[#1A1A1A] mb-6">Send us a Message</h2>
                    
                    <!-- Status Banner -->
                    <div v-if="formStatus" :class="['mb-4 p-3 rounded-lg border font-semibold text-sm', formStatus.type === 'success' ? 'bg-green-50 text-green-800 border-green-200' : 'bg-red-50 text-red-800 border-red-200']">
                        {{ formStatus.message }}
                    </div>

                    <form @submit.prevent="submit" class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Full Name</label>
                                <input v-model="form.full_name" type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Email</label>
                                <input v-model="form.email" type="email" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Phone (optional)</label>
                                <input v-model="form.phone" type="tel" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                                <p class="text-xs text-gray-400 mt-1">Include country code if outside US.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Subject</label>
                                <input v-model="form.subject" type="text" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Reason for contact</label>
                                <select v-model="form.reason" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 bg-white focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                                    <option value="" disabled>Select a reason</option>
                                    <option value="general">General question</option>
                                    <option value="order">Order issue</option>
                                    <option value="wholesale">Wholesale inquiry</option>
                                    <option value="press">Press/Media</option>
                                    <option value="feedback">Feedback</option>
                                </select>
                            </div>
                            <div v-show="form.reason === 'order'">
                                <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Order Number</label>
                                <input v-model="form.order_number" type="text" placeholder="#12345" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#1A1A1A] mb-2">Preferred contact</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="form.preferred_contact" value="email" class="text-[#CBA35C] focus:ring-[#CBA35C]">
                                    <span class="text-gray-700">Email</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" v-model="form.preferred_contact" value="phone" class="text-[#CBA35C] focus:ring-[#CBA35C]">
                                    <span class="text-gray-700">Phone</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-[#1A1A1A] mb-1">Message</label>
                            <textarea v-model="form.message" rows="4" maxlength="1500" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 focus:border-[#CBA35C] focus:ring-1 focus:ring-[#CBA35C] outline-none transition"></textarea>
                            <div class="text-right text-xs text-gray-400 mt-1">{{ form.message.length }} / 1500</div>
                        </div>

                        <div>
                            <label class="flex items-start gap-2 cursor-pointer">
                                <input v-model="form.consent" type="checkbox" required class="mt-1 rounded text-[#CBA35C] focus:ring-[#CBA35C]">
                                <span class="text-sm text-gray-600">I agree to be contacted about my inquiry.</span>
                            </label>
                        </div>

                        <button type="submit" :disabled="form.processing" class="w-full bg-[#CBA35C] text-white py-3 rounded-lg font-bold hover:bg-[#b8944a] transition disabled:opacity-70">
                            {{ form.processing ? 'Sending...' : 'Send Message' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Map Section -->
            <div class="mt-12 bg-white rounded-xl shadow-lg overflow-hidden h-[340px]">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.086991310631!2d-122.419415!3d37.774929!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8085809c5c5c5c5c%3AMockMusea!2sMusea%20Gallery!5e0!3m2!1sen!2sus!4v0000000000"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>

            <!-- FAQ Section -->
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-4">Frequently Asked Questions</h2>
                <div class="space-y-3">
                    <div v-for="(faq, index) in faqs" :key="index" class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <button @click="toggleFaq(index)" class="w-full flex justify-between items-center p-4 text-left font-bold text-[#1A1A1A] hover:bg-gray-50 transition">
                            {{ faq.q }}
                            <span class="text-xl">{{ faq.open ? '−' : '+' }}</span>
                        </button>
                        <div v-show="faq.open" class="px-4 pb-4 text-gray-600 text-sm md:text-base">
                            {{ faq.a }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </MainLayout>
</template>
