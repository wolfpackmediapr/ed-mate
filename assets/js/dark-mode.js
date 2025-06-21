// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    const darkModeToggle = document.getElementById('darkModeToggle');
    const icon = darkModeToggle.querySelector('i');
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark-mode');
        icon.classList.remove('ph-moon-stars');
        icon.classList.add('ph-sun');
    }

    // Toggle dark mode
    darkModeToggle.addEventListener('click', function() {
        if (document.documentElement.classList.contains('dark-mode')) {
            // Switch to light mode
            document.documentElement.classList.remove('dark-mode');
            localStorage.setItem('theme', 'light');
            icon.classList.remove('ph-sun');
            icon.classList.add('ph-moon-stars');
        } else {
            // Switch to dark mode
            document.documentElement.classList.add('dark-mode');
            localStorage.setItem('theme', 'dark');
            icon.classList.remove('ph-moon-stars');
            icon.classList.add('ph-sun');
        }
    });
}); 