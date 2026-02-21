/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/views/**/*.php",
    "./resources/components/**/*.php",
    "./resources/assets/js/**/*.js",
    "./resources/assets/js/**/*.ts",
    "./app/Modules/**/*.php",
    "./functions.php",
  ],

  theme: {
    extend: {
      container: {
        center: true,
        padding: "1rem",
      },
      colors: {
        primary: "#000000",
        secondary: "#f5f5f5",
      }
    },
  },

  plugins: [],
}