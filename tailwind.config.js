import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', 'sans-serif'],
                'display-decorative': ['"Space Grotesk"', 'sans-serif'],
                body: ['"Plus Jakarta Sans"', 'sans-serif'],
                mono: ['"Space Mono"', 'monospace'],
            },
            colors: {
                paper:       'var(--color-paper)',
                'paper-warm': 'var(--color-paper-warm)',
                ink:         'var(--color-ink)',
                'ink-soft':  'var(--color-ink-soft)',
                ember:       'var(--color-ember)',
                'ember-dark': 'var(--color-ember-dark)',
                gold:        'var(--color-gold)',
                'gold-soft':  'var(--color-gold-soft)',
                line:        'var(--color-line)',
            },
            screens: {
                '3xl': '1920px',
            },
        },
    },

    plugins: [forms],
};
