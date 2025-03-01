import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

var themeToggleDarkIcon = document.getElementById("theme-toggle-dark-icon");
var themeToggleLightIcon = document.getElementById("theme-toggle-light-icon");

// Criar um <style> dinâmico para o tema escuro
const darkModeStyles = document.createElement("style");
darkModeStyles.id = "dark-mode-styles";
darkModeStyles.innerHTML = `
    .datatable {
        color: white !important;
    }
    .dataTables_wrapper {
        color: white !important;
    }
`;

// Função para aplicar/remover o tema
function applyTheme(theme) {
    if (theme === "dark") {
        document.documentElement.classList.add("dark");
        document.head.appendChild(darkModeStyles); // Adiciona os estilos da DataTable
        localStorage.setItem("color-theme", "dark");
    } else {
        document.documentElement.classList.remove("dark");
        const existingStyle = document.getElementById("dark-mode-styles");
        if (existingStyle) existingStyle.remove(); // Remove os estilos da DataTable
        localStorage.setItem("color-theme", "light");
    }
}

// Detectar o tema salvo no localStorage ou seguir o sistema
if (
    localStorage.getItem("color-theme") === "dark" ||
    (!("color-theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches)
) {
    themeToggleLightIcon.classList.remove("hidden");
    applyTheme("dark");
} else {
    themeToggleDarkIcon.classList.remove("hidden");
    applyTheme("light");
}

var themeToggleBtn = document.getElementById("theme-toggle");

themeToggleBtn.addEventListener("click", function () {
    themeToggleDarkIcon.classList.toggle("hidden");
    themeToggleLightIcon.classList.toggle("hidden");

    const currentTheme = localStorage.getItem("color-theme") === "dark" ? "light" : "dark";
    applyTheme(currentTheme);
});
