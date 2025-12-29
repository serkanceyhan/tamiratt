import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import containerQueries from '@tailwindcss/container-queries';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    plugins: [
        forms,
        containerQueries,
    ],
    darkMode: "class",
    theme: {
        extend: {
            colors: {
                "primary": "#2463eb",
                "secondary": "#16A34A",
                "background-light": "#ffffff",
                "background-dark": "#111621",
                "surface-light": "#f8f9fc",
                "surface-dark": "#1e2430",
            },
            fontFamily: {
                "display": ["Inter", "sans-serif"],
                "body": ["Inter", "sans-serif"],
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                "DEFAULT": "0.5rem",
                "lg": "0.75rem",
                "xl": "1rem",
                "2xl": "1.5rem",
                "full": "9999px"
            },
        },
    },
};
