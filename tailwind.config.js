const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#DDD0C8',
                fontPrimary: '#323232',
                orange: '#F9B872',
                white: '#FFF',
                darkGray: '#757575',
                lightGray: '#E0E0E0',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
