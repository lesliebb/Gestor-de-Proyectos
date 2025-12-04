import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Light Mode
                light: {
                    background: '#ffffff',
                    surface: '#f5f5f5',
                    text: '#010d23',
                    primary: '#038bbb',
                    secondary: '#e19f41',
                    accent: '#fccb6f',
                },
                // Dark Mode
                dark: {
                    background: '#010d23',
                    surface: '#03223f',
                    text: '#ffffff',
                    primary: '#038bbb',
                    secondary: '#fccb6f',
                    accent: '#e19f41',
                },
                // Colores individuales
                midnight: {
                    900: '#010d23',
                    700: '#03223f',
                },
                ocean: {
                    500: '#038bbb',
                },
                sunset: {
                    300: '#fccb6f',
                    500: '#e19f41',
                },
            },
            animation: {
                'book-open': 'bookOpen 1.5s ease-out forwards',
                'book-close': 'bookClose 1.2s ease-in forwards',
                'page-turn': 'pageTurn 1s ease-in-out',
                'spin-slow': 'spin 3s linear infinite',
            },
            keyframes: {
                bookOpen: {
                    '0%': { transform: 'rotateY(0) translateX(0)' },
                    '100%': { transform: 'rotateY(-170deg) translateX(-100px)' }
                },
                bookClose: {
                    '0%': { transform: 'rotateY(-170deg) translateX(-100px)' },
                    '100%': { transform: 'rotateY(0) translateX(0)' }
                },
                pageTurn: {
                    '0%': { transform: 'rotateY(0)' },
                    '100%': { transform: 'rotateY(-10deg)' }
                }
            },
            perspective: {
                '1000': '1000px',
            },
            transformStyle: {
                '3d': 'preserve-3d',
            },
            backfaceVisibility: {
                'hidden': 'hidden',
            }
        },
    },

    plugins: [
        forms,
        function ({ addUtilities }) {
            addUtilities({
                '.backface-visible': {
                    'backface-visibility': 'visible',
                },
                '.backface-hidden': {
                    'backface-visibility': 'hidden',
                },
                '.preserve-3d': {
                    'transform-style': 'preserve-3d',
                },
            })
        }
    ],
}
