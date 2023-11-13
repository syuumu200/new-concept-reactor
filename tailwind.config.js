const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        fontSize: {
            sm: '1rem',
            base: '1.3rem',
            lg: '1.5rem',
            xl: '1.7rem',
            '2xl': '2rem',
            '3xl': '2.5rem',
            '4xl': '3rem',
            '5xl': '4rem',
            '6xl': '5rem',
            '7xl': '6rem',
            '8xl': '7rem',
        },
        extend: {
            fontFamily: {
                sans: ['Zen Maru Gothic'],
            },
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography')
    ],
};
