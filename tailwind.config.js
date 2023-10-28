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
            xl: '1.5rem',
            '2xl': '1.6rem',
            '3xl': '2rem',
            '4xl': '2.5rem',
            '5xl': '3rem',
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
