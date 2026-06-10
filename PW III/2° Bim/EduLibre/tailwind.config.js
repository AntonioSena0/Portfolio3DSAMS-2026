/** @type {import('tailwindcss').Config} */
export default {
    content: ['./resources/**/*.blade.php', './resources/**/*.js'],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eef2ff',
                    100: '#e0e7ff',
                    200: '#c7d2fe',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    900: '#312e81',
                },
                secondary: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    600: '#475569',
                    700: '#334155',
                    900: '#0f172a',
                },
                accent: {
                    400: '#fbbf24',
                    500: '#f59e0b',
                },
            },
            fontFamily: {
                sans:    ['Inter', 'system-ui', 'sans-serif'],
                display: ['Cal Sans', 'Inter', 'sans-serif'],
                mono:    ['JetBrains Mono', 'monospace'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgb(0 0 0 / 0.08), 0 1px 2px -1px rgb(0 0 0 / 0.08)',
                'card-hover': '0 4px 20px -2px rgb(79 70 229 / 0.12)',
                'modal': '0 25px 50px -12px rgb(0 0 0 / 0.25)',
            },
        },
    },
};