import type { Config } from "tailwindcss";

export default {
  content: ["./index.html", "./src/**/*.{ts,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: {
          50: "#f2f7ff",
          100: "#e5efff",
          200: "#bfd7ff",
          300: "#99bfff",
          400: "#4d8fff",
          500: "#005fff",
          600: "#0056e6",
          700: "#0044b3",
          800: "#003280",
          900: "#00214d",
        },
      },
    },
  },
  plugins: [],
} satisfies Config;
