/**
 * Prelander Links - Pass URL params to CTA links
 *
 * Automatically appends URL parameters (affId, c1, etc.) to all CTA links
 * so tracking is preserved when users click through.
 */
(function() {
  'use strict';

  // Get current URL params
  const urlParams = new URLSearchParams(window.location.search);

  // Params to pass through
  const paramsToPass = ['affId', 'c1', 'c2', 'c3', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term'];

  // Build query string from existing params
  const passedParams = new URLSearchParams();
  paramsToPass.forEach(param => {
    const value = urlParams.get(param);
    if (value) {
      passedParams.set(param, value);
    }
  });

  const paramString = passedParams.toString();
  if (!paramString) return;

  // Update all CTA links
  document.addEventListener('DOMContentLoaded', function() {
    const ctaLinks = document.querySelectorAll('a.cta-btn, a.header-cta, a.end-cta-button');

    ctaLinks.forEach(link => {
      const href = link.getAttribute('href');
      if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

      try {
        const url = new URL(href, window.location.origin);

        // Merge params (don't override existing)
        passedParams.forEach((value, key) => {
          if (!url.searchParams.has(key)) {
            url.searchParams.set(key, value);
          }
        });

        link.setAttribute('href', url.toString());
      } catch (e) {
        // Invalid URL, skip
      }
    });
  });
})();
