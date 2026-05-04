/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    black: "#02010a",
                    prussian: "#04052e",
                    twilight: "#140152",
                    navy: "#22007c",
                    electric: "#0d00a4",
                },
            },
        },
    },
    plugins: [],
};
