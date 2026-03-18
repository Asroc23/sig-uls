import './bootstrap';
import Alpine from 'alpinejs';
import { themeManager, watchSystemTheme } from './theme.js';

window.Alpine = Alpine;

// Register theme manager with Alpine.js
Alpine.data('themeManager', themeManager);

// Listen for system theme changes (when user hasn't set preference)
watchSystemTheme((isDark) => {
    if (document.documentElement.classList.contains('dark') !== isDark) {
        document.documentElement.classList.toggle('dark');
    }
});

Alpine.start();
