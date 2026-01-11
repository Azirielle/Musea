<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import NotificationBell from '@/Components/NotificationBell.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div>
        <div class="min-h-screen bg-canvas">
            <nav
                class="border-b border-divider bg-paper"
            >
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-ink"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                                <NavLink
                                    :href="route('home')"
                                    :active="route().current('home')"
                                >
                                    Dashboard
                                </NavLink>
                                <NavLink
                                    :href="route('dashboard.artworks.index')"
                                    :active="route().current('dashboard.artworks.*')"
                                >
                                    My Artworks
                                </NavLink>
                                <NavLink
                                    :href="route('dashboard.wallet')"
                                    :active="route().current('dashboard.wallet')"
                                >
                                    My Wallet
                                </NavLink>
                                <NavLink
                                    :href="route('messages.index')"
                                    :active="route().current('messages.*')"
                                >
                                    Messages
                                    <span v-if="$page.props.auth.unreadMessagesCount > 0" class="ml-1 px-1.5 py-0.5 text-[10px] bg-accent text-white rounded-full">
                                        {{ $page.props.auth.unreadMessagesCount }}
                                    </span>
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Notification Bell -->
                            <!-- Notification Bell Removed -->

                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-paper px-3 py-2 text-sm font-medium leading-4 text-ink-light transition duration-150 ease-in-out hover:text-ink focus:outline-none"
                                            >
                                            <div class="relative h-8 w-8 me-2">
                                                <img
                                                    class="h-8 w-8 rounded-full object-cover"
                                                    :src="$page.props.auth.user.avatar_path || 'https://ui-avatars.com/api/?name=' + $page.props.auth.user.first_name + '+' + $page.props.auth.user.last_name + '&color=7F9CF5&background=EBF4FF'"
                                                    :alt="$page.props.auth.user.first_name"
                                                />
                                                <div v-if="$page.props.auth.unreadCount > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center ring-2 ring-white">
                                                    {{ $page.props.auth.unreadCount }}
                                                </div>
                                            </div>
                                            {{ $page.props.auth.user.first_name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Profile
                                        </DropdownLink>
                                        <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                        <div class="px-4 py-2 text-xs text-gray-400">
                                            Notifications
                                        </div>
                                         <div v-if="$page.props.auth.notifications.length === 0" class="px-4 py-2 text-sm text-gray-500 italic">
                                            No new notifications
                                        </div>
                                        <template v-else>
                                            <DropdownLink 
                                                v-for="notification in $page.props.auth.notifications" 
                                                :key="notification.id"
                                                :href="route('notifications.read', notification.id)"
                                                method="post"
                                                :data="{ redirect_to: notification.data.action_url }"
                                                as="button"
                                                class="flex flex-col gap-1 border-b border-gray-50 last:border-0 text-left w-full"
                                            >
                                                <span class="font-bold text-xs">{{ notification.data.title }}</span>
                                                <span class="text-[10px] opacity-80 truncate">{{ notification.data.message }}</span>
                                            </DropdownLink>
                                        </template>
                                        <div class="border-t border-gray-100 dark:border-gray-600"></div>
                                        
                                        <DropdownLink
                                            :href="route('dashboard.artworks.index')"
                                        >
                                            My Artworks
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('dashboard.wallet')"
                                        >
                                            My Wallet
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('messages.index')"
                                        >
                                            Messages
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-ink-light transition duration-150 ease-in-out hover:bg-canvas hover:text-ink focus:bg-canvas focus:text-ink focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('home')"
                            :active="route().current('home')"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('dashboard.wallet')"
                            :active="route().current('dashboard.wallet')"
                        >
                            My Wallet
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('messages.index')"
                            :active="route().current('messages.*')"
                        >
                            Messages
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-divider pb-1 pt-4"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-ink"
                            >
                                {{ $page.props.auth.user.first_name }}
                            </div>
                            <div class="text-sm font-medium text-ink-light">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                class="bg-paper shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
