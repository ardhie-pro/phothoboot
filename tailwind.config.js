/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        ramadan: {
          primary: "#D4AF37",   // User's Header Gold
          secondary: "#63392E", // Maroon/Brown
          cream: "#FFFDF5",     // Background
          gold: "#C9A227",      // User's Ramadan Kareem Gold
          green: "#2D5A27",     // Ramadan Green
          light: "#8B4513",     
        },
      },
    },
  },
  plugins: [],
};
