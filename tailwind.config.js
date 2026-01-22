import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                canvas: '#FDFCFB', // Soft parchment base
                paper: '#FFFFFF',
                ink: {
                    DEFAULT: '#1A1A1A', // Deep Charcoal
                    light: '#626262',   // Muted Grey
                },
                accent: {
                    DEFAULT: '#8B7355', // Muted Bronze
                    hover: '#725E45',
                },
                divider: '#E8E4E1', // Warm divider
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            keyframes: {
                scroll: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                }
            },
            animation: {
                scroll: 'scroll 30s linear infinite',
            },
        },
    },

    plugins: [forms],
};
