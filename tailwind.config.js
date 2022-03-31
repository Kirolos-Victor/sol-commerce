const defaultTheme = require('tailwindcss/defaultTheme');

// let containerScreens = Object.assign({}, defaultTheme.screens);
// delete containerScreens['2xl'];

module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        screens: {
			...defaultTheme.screens,
			'2xl': '1480px'
		},
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                serif: ['ArgentCF', ...defaultTheme.fontFamily.serif],
            },
			colors: {
				primary: '#AA947C',
				secondary: '#A2AC9A',
				light: '#F9F5F1',
				lightbox: '#F2ECE6',
				darkbox: '#E7DFD6',
				accent: '#A3AC99',
				accentdark: '#888E82',
			},
            width: {
                '1/8': '12.5%',
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
