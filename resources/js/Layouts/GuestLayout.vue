<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-canvas pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <img src="/images/logo/Musea.png" loading="eager" alt="Musea" class="h-24 w-auto drop-shadow-sm opacity-90" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-paper px-6 py-4 shadow-sm sm:max-w-md sm:rounded-2xl border border-divider"
        >
            <slot />
        </div>
    </div>
</template>
