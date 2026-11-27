import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './Modules/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', '-apple-system', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                pine: '#0D3B2E',
                'pine-deep': '#092B21',
                'pine-text': '#C9E4D8',
                'pine-subtle': '#9FD1BC',
                alabaster: '#F7F6F1',
                'stone-line': '#E4E0D5',
                'stone-soft': '#EFECE3',
                brand: '#0F6B50',
                'brand-hover': '#0B553F',
                'brand-dark': '#094332',
                'brand-soft': '#E7F4EE',
                'brand-ink': '#0D4A38',
                ink: '#18221D',
                'ink-muted': '#495850',
                ochre: '#8A4E08',
                'ochre-bg': '#FDF5E6',
                'ochre-border': '#D99126',
                danger: '#991B1B',
                'danger-bg': '#FEF2F2',
                'danger-border': '#DC2626',
            },
        },
    },

    plugins: [forms],
};
