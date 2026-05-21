/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        burgundy: {
          500: '#800020',
        },
        maroon: '#800000',
      },
    },
  },
  plugins: [
    require('flowbite/plugin')
  ],
}