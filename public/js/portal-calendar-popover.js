document.addEventListener('DOMContentLoaded', () => {
    const closePopover = (popover) => {
        const cell = popover.closest('.month-day');
        const toggle = cell?.querySelector('[data-calendar-more-toggle]');

        popover.hidden = true;
        cell?.classList.remove('is-popover-open', 'popover-above');
        toggle?.setAttribute('aria-expanded', 'false');
    };

    document.querySelectorAll('[data-calendar-more-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const cell = toggle.closest('.month-day');
            const popover = cell?.querySelector('.calendar-event-popover');

            if (!cell || !popover) return;

            const shouldOpen = popover.hidden;
            document.querySelectorAll('.calendar-event-popover:not([hidden])').forEach(closePopover);

            if (!shouldOpen) return;

            popover.hidden = false;
            cell.classList.add('is-popover-open');
            toggle.setAttribute('aria-expanded', 'true');

            const popoverBottom = cell.getBoundingClientRect().bottom + popover.offsetHeight + 8;
            if (popoverBottom > window.innerHeight) {
                cell.classList.add('popover-above');
            }
        });
    });

    document.querySelectorAll('[data-calendar-popover-close]').forEach((button) => {
        button.addEventListener('click', () => {
            const popover = button.closest('.calendar-event-popover');
            const toggle = popover?.closest('.month-day')?.querySelector('[data-calendar-more-toggle]');

            if (!popover) return;

            closePopover(popover);
            toggle?.focus();
        });
    });

    document.addEventListener('click', (event) => {
        if (event.target.closest('[data-calendar-more-toggle], .calendar-event-popover')) return;

        document.querySelectorAll('.calendar-event-popover:not([hidden])').forEach(closePopover);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') return;

        const openPopover = document.querySelector('.calendar-event-popover:not([hidden])');
        if (!openPopover) return;

        const toggle = openPopover.closest('.month-day')?.querySelector('[data-calendar-more-toggle]');
        closePopover(openPopover);
        toggle?.focus();
    });
});
