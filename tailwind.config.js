/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js}",
    "./backend/**/*.php",
    "./index.php",
  ],
  theme: {
    backgroundImage: {
      "blue-wave": "url('../../assets/images/bg.png')",
      "perpustakaan": "url('../../assets/images/perpus.jpg')",
    },
    extend: {},
  },
  plugins: [],
}