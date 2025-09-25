(function(){
  function getQueryLang() {
    const params = new URLSearchParams(window.location.search);
    const lang = params.get('lang');
    return (lang === 'ar' || lang === 'fr') ? lang : 'fr';
  }

  async function loadTranslations(lang) {
    try {
      const res = await fetch(`langs/${lang}.json?_=${Date.now()}`);
      if (!res.ok) throw new Error('Failed to load lang file');
      return await res.json();
    } catch (e) {
      console.error('i18n load error:', e);
      return {};
    }
  }

  function applyDirection(meta) {
    if (!meta) return;
    const dir = meta['meta.direction'] || 'ltr';
    const html = document.documentElement;
    html.setAttribute('dir', dir);
    html.setAttribute('lang', meta['meta.language'] || 'fr');
    // Mirror body alignment if RTL
    if (dir === 'rtl') {
      document.body.style.textAlign = 'right';
    } else {
      document.body.style.textAlign = '';
    }
  }

  function translatePage(dict) {
    // Title
    if (dict['map.title']) {
      document.title = dict['map.title'];
    }

    // data-i18n: textContent
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const key = el.getAttribute('data-i18n');
      if (dict[key] != null) {
        el.textContent = dict[key];
      }
    });

    // data-i18n-placeholder: placeholder
    document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
      const key = el.getAttribute('data-i18n-placeholder');
      if (dict[key] != null) {
        el.setAttribute('placeholder', dict[key]);
      }
    });

    // data-i18n-value: value attribute
    document.querySelectorAll('[data-i18n-value]').forEach(el => {
      const key = el.getAttribute('data-i18n-value');
      if (dict[key] != null) {
        el.setAttribute('value', dict[key]);
      }
    });

    // data-i18n-html: innerHTML (use with caution if you need <br> etc.)
    document.querySelectorAll('[data-i18n-html]').forEach(el => {
      const key = el.getAttribute('data-i18n-html');
      if (dict[key] != null) {
        el.innerHTML = dict[key];
      }
    });
  }

  (async function init(){
    const lang = getQueryLang();
    const dict = await loadTranslations(lang);
    applyDirection(dict);
    translatePage(dict);
    // Expose for custom use
    window.__i18n = { lang, dict };
  })();
})();
