<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, nextTick, watch } from 'vue';
import axios from 'axios';

const props = defineProps({
    conversations: Array,
});

const page = usePage();
const selectedId = ref(new URLSearchParams(window.location.search).get('selected'));
const activeConversation = ref(null);
const messages = ref([]);
const messageBody = ref('');
const isLoading = ref(false);
const scrollContainer = ref(null);
const showMobileList = ref(true);
let pollingInterval = null;

const selectConversation = async (conversation) => {
    selectedId.value = conversation.id;
    activeConversation.value = conversation;
    isLoading.value = true;
    showMobileList.value = false;
    
    try {
        const response = await axios.get(route('messages.show', conversation.id));
        messages.value = response.data.messages;
        
        // Refresh notifications in layout
        router.reload({ only: ['auth'] });

        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Failed to load messages', error);
    } finally {
        isLoading.value = false;
    }
};

const sendMessage = async () => {
    if (!messageBody.value.trim() || !activeConversation.value) return;

    const body = messageBody.value;
    messageBody.value = '';

    try {
        const response = await axios.post(route('messages.store', activeConversation.value.id), {
            body: body
        });
        messages.value.push(response.data);
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Failed to send message', error);
    }
};

const scrollToBottom = () => {
    if (scrollContainer.value) {
        scrollContainer.value.scrollTop = scrollContainer.value.scrollHeight;
    }
};

const pollMessages = async () => {
    if (!activeConversation.value) return;
    
    try {
        const response = await axios.get(route('messages.show', activeConversation.value.id));
        // Simple update if message count changed
        if (response.data.messages.length > messages.value.length) {
            messages.value = response.data.messages;
            await nextTick();
            scrollToBottom();
        }
    } catch (error) {
        console.error('Polling failed', error);
    }
};

onMounted(() => {
    if (selectedId.value) {
        const conv = props.conversations.find(c => c.id == selectedId.value);
        if (conv) selectConversation(conv);
    }
    
    pollingInterval = setInterval(pollMessages, 5000);
});

onUnmounted(() => {
    if (pollingInterval) clearInterval(pollingInterval);
});

watch(() => props.conversations, (newVal) => {
    if (selectedId.value && activeConversation.value) {
        // Sync active conv data if list updates
        const updated = newVal.find(c => c.id == selectedId.value);
        if (updated) activeConversation.value = updated;
    }
});
</script>

<template>
    <Head title="Messages" />

    <AuthenticatedLayout>
        <template #header>
            Messages
        </template>

        <div class="py-12 px-4 sm:px-6 lg:px-8 bg-canvas min-h-[calc(100vh-64px)]">
            <div class="max-w-6xl mx-auto md:h-[700px] flex flex-col md:flex-row bg-paper rounded-3xl border border-divider shadow-2xl overflow-hidden">
                
                <!-- Sidebar -->
                <div
                    class="w-full md:w-1/3 md:border-r border-divider flex flex-col bg-white/50 backdrop-blur-sm"
                    :class="showMobileList ? 'flex' : 'hidden md:flex'"
                >
                    <div class="p-6 border-b border-divider">
                        <h3 class="text-xl font-serif font-bold italic text-ink">Inquiries</h3>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto">
                        <div v-if="conversations.length === 0" class="p-8 text-center text-ink-light italic text-sm">
                            No conversations yet.
                        </div>
                        
                        <div 
                            v-for="conv in conversations" 
                            :key="conv.id" 
                            @click="selectConversation(conv)"
                            class="p-4 flex items-center gap-4 cursor-pointer hover:bg-canvas transition-colors border-b border-divider/50"
                            :class="{ 'bg-canvas border-l-4 border-l-accent': selectedId == conv.id }"
                        >
                            <div class="relative">
                                <Link :href="route('artists.show', conv.other_user.id)" @click.stop>
                                    <img :src="conv.other_user.avatar" class="w-12 h-12 rounded-full object-cover border border-divider hover:opacity-80 transition-opacity">
                                </Link>
                                <div v-if="conv.unread_count > 0" class="absolute -top-1 -right-1 bg-accent text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center ring-2 ring-white">
                                    {{ conv.unread_count }}
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <Link :href="route('artists.show', conv.other_user.id)" @click.stop class="hover:underline">
                                        <h4 class="text-sm font-bold text-ink truncate">{{ conv.other_user.name }}</h4>
                                    </Link>
                                    <span class="text-[10px] text-ink-light whitespace-nowrap">{{ conv.last_message?.time }}</span>
                                </div>
                                <p class="text-xs text-ink-light truncate opacity-70">
                                    <span v-if="conv.artwork" class="font-bold text-accent mr-1">[{{ conv.artwork.title }}]</span>
                                    {{ conv.last_message?.body || 'Start a conversation...' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chat Area -->
                <div
                    class="flex-1 flex flex-col bg-white"
                    :class="showMobileList ? 'hidden md:flex' : 'flex'"
                >
                    <template v-if="activeConversation">
                        <!-- Chat Header -->
                        <div class="p-4 border-b border-divider flex items-center justify-between bg-canvas/30">
                            <div class="flex items-center gap-3">
                                <button
                                    type="button"
                                    class="md:hidden p-2 -ml-2 rounded-lg hover:bg-white/60 text-ink-light"
                                    @click="showMobileList = true"
                                    aria-label="Back to conversations"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <Link :href="route('artists.show', activeConversation.other_user.id)">
                                    <img :src="activeConversation.other_user.avatar" class="w-10 h-10 rounded-full object-cover hover:opacity-80 transition-opacity">
                                </Link>
                                <div>
                                    <Link :href="route('artists.show', activeConversation.other_user.id)" class="hover:underline">
                                        <h4 class="text-sm font-bold text-ink">{{ activeConversation.other_user.name }}</h4>
                                    </Link>
                                    <p class="text-[10px] text-ink-light flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active
                                    </p>
                                </div>
                            </div>
                            
                            <div v-if="activeConversation.artwork" class="flex items-center gap-3 bg-white p-2 rounded-xl border border-divider shadow-sm">
                                <img :src="activeConversation.artwork.image" class="w-10 h-10 rounded-lg object-cover">
                                <div class="text-left">
                                    <p class="text-[10px] font-bold text-ink-light uppercase">Regarding</p>
                                    <p class="text-xs font-bold text-ink truncate max-w-[120px]">{{ activeConversation.artwork.title }}</p>
                                </div>
                                <Link :href="route('shop.show', { artwork: activeConversation.artwork.id, from: 'messages' })" class="p-2 hover:bg-canvas rounded-lg text-ink-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </Link>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div
                            ref="scrollContainer"
                            class="flex-1 overflow-y-auto p-6 space-y-4 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-fixed"
                        >
                            <div v-if="isLoading" class="flex justify-center py-10">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-accent"></div>
                            </div>
                            
                            <template v-else>
                                <div v-if="messages.length === 0" class="text-center py-20">
                                    <div class="w-16 h-16 bg-canvas rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-ink-light opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                                    </div>
                                    <p class="text-ink-light italic">No messages yet. Start the conversation!</p>
                                </div>

                                <div 
                                    v-for="msg in messages" 
                                    :key="msg.id" 
                                    class="flex flex-col"
                                    :class="msg.is_mine ? 'items-end' : 'items-start'"
                                >
                                    <div class="flex items-end gap-2 max-w-[80%]">
                                        <div 
                                            class="px-4 py-3 rounded-2xl text-sm shadow-sm"
                                            :class="msg.is_mine 
                                                ? 'bg-ink text-white rounded-br-none' 
                                                : 'bg-canvas text-ink rounded-bl-none border border-divider'"
                                        >
                                            {{ msg.body }}
                                        </div>
                                    </div>
                                    <span class="text-[9px] text-ink-light mt-1 opacity-60 uppercase font-bold tracking-tighter">{{ msg.time }}</span>
                                </div>
                            </template>
                        </div>

                        <!-- Chat Input -->
                        <div class="p-4 border-t border-divider bg-white">
                            <form @submit.prevent="sendMessage" class="flex items-center gap-3">
                                <input 
                                    v-model="messageBody" 
                                    type="text" 
                                    placeholder="Type your message..." 
                                    class="flex-1 bg-canvas border-divider rounded-full px-6 py-3 text-sm focus:ring-accent focus:border-accent text-ink"
                                />
                                <button type="submit" :disabled="!messageBody.trim()" class="bg-accent text-white p-3 rounded-full hover:opacity-90 transition disabled:opacity-50 disabled:grayscale">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transform rotate-90" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </button>
                            </form>
                        </div>
                    </template>

                    <div v-else class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-canvas/10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg mb-6 border border-divider">
                             <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-accent opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                        </div>
                        <h3 class="text-2xl font-serif font-bold italic text-ink mb-2">Your Conversations</h3>
                        <p class="text-ink-light max-w-md mx-auto">Select a chat from the sidebar to view messages or start an inquiry from any artwork page.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,700&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
</style>
