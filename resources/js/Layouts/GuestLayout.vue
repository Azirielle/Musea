<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FAF7F2] pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <img src="/images/logo/Musea.png" loading="eager" alt="Musea" class="h-24 w-auto drop-shadow-sm" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-lg sm:max-w-md sm:rounded-2xl border border-[#CBA35C]/20"
        >
            <slot />
        </div>
    </div>
</template>
