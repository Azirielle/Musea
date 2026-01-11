<script setup>
import { computed } from 'vue';

const props = defineProps({
    role: {
        type: String,
        default: 'member'
    },
    isVerified: {
        type: Boolean,
        default: false
    }
});

const isArtist = computed(() => props.role === 'artist');
const isVerifiedMember = computed(() => props.role === 'verified_member' || (props.isVerified && !isArtist.value));

</script>

<template>
    <span v-if="isArtist" class="inline-flex items-center justify-center ml-1" title="Verified Artist">
        <!-- Gold Check Badge -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-amber-500">
            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm4.45 6.45l-3.25 3.5a.75.75 0 01-1.1 0l-1.05-1.125a.75.75 0 111.085-1.066l.488.523 2.73-2.937a.75.75 0 111.1 1.02z" clip-rule="evenodd" />
        </svg>
    </span>
    <span v-else-if="isVerifiedMember" class="inline-flex items-center justify-center ml-1" title="Verified Member">
        <!-- Silver/Blue Check Badge -->
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-blue-500">
            <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 00-1.032 0 11.209 11.209 0 01-7.877 3.08.75.75 0 00-.722.515A12.74 12.74 0 002.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 00.374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.752-.403-3.401-1.134-4.87a.75.75 0 00-.722-.515 11.208 11.208 0 01-7.877-3.08zM12 4.04c1.72 1.047 3.509 1.714 5.393 1.938.82 2.122 1.232 4.395 1.232 6.649 0 3.75-2.005 7.027-4.998 8.784-2.992-1.757-4.998-5.035-4.998-8.784 0-2.254.411-4.527 1.232-6.65A12.71 12.71 0 0112 4.039zm-1.28 9.39l1.458 1.458a.75.75 0 001.06-1.06l-2.029-2.029a.75.75 0 00-1.061 0l-1.01 1.01a.75.75 0 101.061 1.06L9.66 12.37l1.06 1.06z" clip-rule="evenodd" />
        </svg>
    </span>
</template>
