import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Livewire/**/*.php',
    ],

    safelist: [
        // Number Bonds game — teal selected-state classes (dynamically constructed in blade)
        'bg-teal-600', 'border-teal-500', 'text-teal-700', 'text-teal-500',
        'hover:border-teal-300', 'bg-teal-50', 'text-teal-600',
        'bg-teal-100', 'border-teal-400',
        // Spelling puzzle — violet active-state classes used only inside PHP ternaries
        'bg-violet-600', 'border-violet-600',
        // Word definition admin — difficulty radio checked states (dynamic colour names in loop)
        'peer-checked:border-emerald-500', 'peer-checked:bg-emerald-50', 'peer-checked:text-emerald-700',
        'peer-checked:border-amber-500',   'peer-checked:bg-amber-50',   'peer-checked:text-amber-700',
        'peer-checked:border-red-500',     'peer-checked:bg-red-50',     'peer-checked:text-red-700',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
