document.addEventListener('DOMContentLoaded', () => {
    const scrollToTopButton = document.querySelector('[data-scroll-to-top]');

    if (scrollToTopButton) {
        const updateScrollButton = () => {
            scrollToTopButton.hidden = window.scrollY < 320;
        };

        window.addEventListener('scroll', updateScrollButton, { passive: true });
        updateScrollButton();

        scrollToTopButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
            });
        });
    }

    const menuButton = document.querySelector('.nav-toggle');
    const publicNav = document.querySelector('.public-nav');

    menuButton?.addEventListener('click', () => {
        const isOpen = publicNav.classList.toggle('is-open');
        menuButton.setAttribute('aria-expanded', String(isOpen));
    });

    document.querySelectorAll('form [data-loading-submit]').forEach((button) => {
        button.form?.addEventListener('submit', () => {
            button.disabled = true;
            button.innerHTML = '<span class="submit-spinner" aria-hidden="true"></span> Mengirim pendaftaran...';
        }, { once: true });
    });

    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations()
            .then((registrations) => registrations.forEach((registration) => {
                const workers = [registration.active, registration.waiting, registration.installing].filter(Boolean);
                if (workers.some((worker) => new URL(worker.scriptURL).pathname.endsWith('/service-worker.js'))) {
                    registration.unregister();
                }
            }))
            .catch(() => {});

        if ('caches' in window) caches.delete('ruang-budaya-v1').catch(() => {});
    }
});
