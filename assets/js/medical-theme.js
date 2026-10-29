(function () {
    const storageKey = 'carestash-theme';
    const savedTheme = localStorage.getItem(storageKey);
    const initialTheme = savedTheme === 'dark' ? 'dark' : 'light';

    document.documentElement.dataset.theme = initialTheme;

    function addThemeToggle() {
        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'theme-toggle btn';
        button.setAttribute('aria-pressed', String(initialTheme === 'dark'));

        function updateButton(theme) {
            const isDark = theme === 'dark';
            button.textContent = isDark ? '☀ Light mode' : '☾ Dark mode';
            button.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
            button.setAttribute('aria-pressed', String(isDark));
        }

        updateButton(initialTheme);
        button.addEventListener('click', function () {
            const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
            document.documentElement.dataset.theme = nextTheme;
            localStorage.setItem(storageKey, nextTheme);
            updateButton(nextTheme);
        });

        document.body.appendChild(button);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', addThemeToggle, { once: true });
    } else {
        addThemeToggle();
    }
})();
