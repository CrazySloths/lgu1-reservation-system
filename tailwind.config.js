/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                // LGU Brand Colors - South Caloocan City General Services
                'lgu': {
                    'bg': '#f2f7f5',
                    'headline': '#00473e', 
                    'paragraph': '#475d5b',
                    'button': '#faae2b',
                    'button-text': '#00473e',
                    'stroke': '#00332c',
                    'main': '#f2f7f5',
                    'highlight': '#faae2b',
                    'secondary': '#ffa8ba',
                    'tertiary': '#fa5246'
                },
            },
            fontFamily: {
                'sans': ['Instrument Sans', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-in-out',
                'slide-in': 'slideIn 0.3s ease-out',
                'bounce-gentle': 'bounceGentle 2s infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                bounceGentle: {
                    '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0)' },
                    '40%': { transform: 'translateY(-3px)' },
                    '60%': { transform: 'translateY(-2px)' },
                },
            },
            boxShadow: {
                'lgu': '0 10px 25px -3px rgba(0, 71, 62, 0.1), 0 4px 6px -2px rgba(0, 71, 62, 0.05)',
                'lgu-lg': '0 20px 25px -5px rgba(0, 71, 62, 0.1), 0 10px 10px -5px rgba(0, 71, 62, 0.04)',
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
