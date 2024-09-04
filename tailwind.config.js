const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                principal: "var(--color-background-principal)",
                textoprincipal: "var(--color-texto-principal)",

                fondoheaderform: "var(--color-fondo-header-form)",
                fondoform: "var(--color-fondo-form)",
                colorform: "var(--color-texto-form)",

                colorlabel: "var(--color-label)",

                fondocard: "var(--color-fondo-card)",
                colorcard: "var(--color-texto-card)",
                colorborder: "var(--color-border-card)",

                bgnavlink: "var(--bg-navlink)",
                colornavlink: "var(--color-navlink)",
                hoverbgnavlink: "var(--hoverbg-navlink)",
                hovercolornavlink: "var(--hovercolor-navlink)",
                ringnavlink: "var(--ring-navlink)",

                fondobotonlg: "var(--color-boton-lg)",
                hoverbotonlg: "var(--color-hoverboton-lg)",
                textobotonlg: "var(--color-textoboton-lg)",
                hovertextobotonlg: "var(--color-fondotextoboton-lg)",

                fondotitulo: "var(--color-fondo-titulo)",
                textotitulo: "var(--color-texto-titulo)",

                fondospan: "var(--color-fondo-span)",
                textospan: "var(--color-texto-span)",

                fondobotondefault: "var(--color-fondo-boton)",
                hoverbotondefault: "var(--color-hover-boton)",
                hovertextobotondefault: "var(--color-hover-textoboton)",
                textobotondefault: "var(--color-texto-boton)",
                ringbotondefault: "var(--color-ring-boton)",

                checkboxdefault: "var(--color-checkbox)",

                bgheadertable: "var(--bg-header-tabla)",
                coloheadertable: "var(--color-header-tabla)",
                bgtable: "var(--bg-fondo-tabla)",
                colortable: "var(--color-texto-tabla)",
                bghovertable: "var(--bg-hover-tabla)",
                colorhovertable: "var(--color-hover-tabla)",

                fondobotoncar: "var(--color-fondo-botoncar)",
                hoverbotoncar: "var(--color-hover-botoncar)",
                textobotoncar: "var(--color-texto-botoncar)",
                ringbotoncar: "var(--color-ring-botoncar)",

                fondoicono: "var(--color-fondo-icono)",
                coloricono: "var(--color-icono)",
                bgshadowicono:"var(--color-shadow-icono)",

                fondoheadermodal: "var(--fondo-header-modal)",
                colorheadermodal: "var(--color-header-modal)",
                fondomodal: "var(--fondo-modal)",
                colormodal: "var(--color-modal)",

            },
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
