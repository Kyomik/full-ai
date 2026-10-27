document.addEventListener('DOMContentLoaded', () => {
    const targets = [
        '.home-hero',
        '.announcement-hero',
        '.event-detail-heading',
        '.about-heading',
        '.home-section',
        '.announcement-strip',
        '.page-heading',
        '.event-list-grid > *',
        '.news-grid > *',
        '.photo-grid > *',
        '.album-grid > *',
        '.content-sidebar > *',
        '.calendar-main',
        '.calendar-toolbar',
        '.calendar-agenda-day',
        '.calendar-sidebar > *',
        '.announcement-sidebar',
        '.gallery-tabs',
        '.gallery-section',
        '.event-detail-section',
        '.event-detail-sidebar',
        '.announcement-list > *',
        '.about-intro > *',
        '.values-grid > *',
        '.contact-layout > *',
        '.detail-layout > *',
        '.agency-contact',
        '.home-event-grid > *',
        '.category-grid > *',
    ].join(',');
    const elements = [...document.querySelectorAll(targets)];

    if (
        !elements.length ||
        !('IntersectionObserver' in window) ||
        window.matchMedia('(prefers-reduced-motion: reduce)').matches
    ) return;

    const siblingIndexes = new Map();

    elements.forEach((element) => {
        const parent = element.parentElement;
        const siblingIndex = siblingIndexes.get(parent) ?? 0;
        siblingIndexes.set(parent, siblingIndex + 1);
        element.style.setProperty('--scroll-reveal-delay', `${Math.min(siblingIndex, 4) * 65}ms`);
        element.classList.add('scroll-reveal');
    });

    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            entry.target.classList.add('is-visible');
            currentObserver.unobserve(entry.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -32px 0px' });

    elements.forEach((element) => observer.observe(element));
});
