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
                canvas: '#FAFAFA',
                paper: '#FFFFFF',
                ink: {
                    DEFAULT: '#27272A', // Zinc 800
                    light: '#71717A',   // Zinc 500
                },
                accent: {
                    DEFAULT: '#18181B', // Zinc 900
                    hover: '#27272A',
                },
                grape: {
                    DEFAULT: '#7209B7',
                    hover: '#5a0791',
                },
                charcoal: '#1A1C22',
                divider: '#E4E4E7', // Zinc 200
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
