<button id="theme-toggle"
        type="button"
        aria-label="Toggle color theme"
        aria-pressed="false"
        class="inline-flex items-center justify-center p-2 rounded focus:outline-none"
>
    <!-- Sun (light) -->
    <svg id="icon-sun" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <path d="M6.76 4.84l-1.8-1.79L3.17 4.84l1.79 1.79 1.8-1.79zM1 13h3v-2H1v2zm10 9h2v-3h-2v3zm8.83-2.05l-1.79-1.8-1.8 1.79 1.79 1.8 1.8-1.79zM17.24 4.84l1.79-1.79 1.8 1.79-1.8 1.79-1.79-1.79zM20 11v2h3v-2h-3zM12 6a6 6 0 100 12 6 6 0 000-12zM6.76 19.16l-1.79 1.79 1.8 1.8 1.79-1.79-1.8-1.8z"/>
    </svg>

    <!-- Moon (dark) -->
    <svg id="icon-moon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="display:none">
        <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
    </svg>
</button>

<script>
(function () {
  const storageKey = 'theme-preference';
  const className = 'dark';
  const htmlEl = document.documentElement;

  function getStoredTheme() {
    try { return localStorage.getItem(storageKey); } catch (e) { return null; }
  }
  function setStoredTheme(value) {
    try { localStorage.setItem(storageKey, value); } catch (e) {}
  }
  function systemPrefersDark() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  }

  function applyThemeToElements(theme, toggle, sunIcon, moonIcon) {
    const isDark = theme === 'dark';
    if (isDark) {
      htmlEl.classList.add(className);
      toggle.setAttribute('aria-pressed', 'true');
    } else {
      htmlEl.classList.remove(className);
      toggle.setAttribute('aria-pressed', 'false');
    }
    if (sunIcon && moonIcon) {
      sunIcon.style.display = isDark ? 'none' : 'inline';
      moonIcon.style.display = isDark ? 'inline' : 'none';
    }
  }

  function init() {
    const toggle = document.getElementById('theme-toggle');
    if (!toggle) return;

    const sunIcon = document.getElementById('icon-sun');
    const moonIcon = document.getElementById('icon-moon');

    const stored = getStoredTheme();
    const initial = stored ? stored : (systemPrefersDark() ? 'dark' : 'light');
    applyThemeToElements(initial, toggle, sunIcon, moonIcon);

    toggle.addEventListener('click', () => {
      const nowDark = htmlEl.classList.contains(className);
      const next = nowDark ? 'light' : 'dark';
      applyThemeToElements(next, toggle, sunIcon, moonIcon);
      setStoredTheme(next);
    });

    // Keep toggle in sync if system preference changes and user has not chosen explicitly
    if (window.matchMedia) {
      const mq = window.matchMedia('(prefers-color-scheme: dark)');
      mq.addEventListener?.('change', (e) => {
        if (!getStoredTheme()) {
          const next = e.matches ? 'dark' : 'light';
          applyThemeToElements(next, toggle, sunIcon, moonIcon);
        }
      });
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
</script>