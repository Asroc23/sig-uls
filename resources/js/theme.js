/**
 * Theme Manager - Handles dark mode functionality
 * Features:
 * - System preference detection (prefers-color-scheme)
 * - localStorage persistence
 * - Smooth transitions
 * - Global state management with Alpine.js
 */

export function themeManager() {
    return {
        darkMode: initializeDarkMode(),

        /**
         * Toggle dark mode and persist preference
         */
        toggleDarkMode() {
            this.darkMode = !this.darkMode;
            this.updateDarkMode(this.darkMode);
        },

        /**
         * Set dark mode to specific value
         */
        setDarkMode(value) {
            this.darkMode = value;
            this.updateDarkMode(value);
        },

        /**
         * Update DOM and localStorage when dark mode changes
         */
        updateDarkMode(isDark) {
            // Update localStorage
            localStorage.setItem('darkMode', isDark);

            // Update HTML class for Tailwind dark mode
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Dispatch custom event for other components
            window.dispatchEvent(
                new CustomEvent('darkModeChanged', { detail: { isDark } })
            );
        },
    };
}

/**
 * Initialize dark mode based on saved preference or system preference
 * Returns true if dark mode should be enabled
 */
function initializeDarkMode() {
    const saved = localStorage.getItem('darkMode');

    // If preference is saved, use it
    if (saved !== null) {
        return saved === 'true';
    }

    // Otherwise, use system preference
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
}

/**
 * Listen for system theme changes
 */
export function watchSystemTheme(callback) {
    const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');

    // Modern browsers
    if (darkModeQuery.addEventListener) {
        darkModeQuery.addEventListener('change', (e) => {
            // Only apply if user hasn't set a preference
            if (localStorage.getItem('darkMode') === null) {
                callback(e.matches);
            }
        });
    }
    // Older browsers
    else if (darkModeQuery.addListener) {
        darkModeQuery.addListener((e) => {
            if (localStorage.getItem('darkMode') === null) {
                callback(e.matches);
            }
        });
    }
}

/**
 * Get current dark mode status
 */
export function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

/**
 * Get current color scheme (respects user preference and system)
 */
export function getColorScheme() {
    return isDarkMode() ? 'dark' : 'light';
}
