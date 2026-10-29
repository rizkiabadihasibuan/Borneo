import './bootstrap';

/**
 * Borneo Siaga - Interactive UI Foundation
 * Handles mobile drawer toggle, active state handling, and accessible interaction.
 */

document.addEventListener('DOMContentLoaded', () => {
    const mobileToggleBtn = document.getElementById('mobileNavToggle');
    const mobileDrawer = document.getElementById('mobileNavDrawer');

    if (mobileToggleBtn && mobileDrawer) {
        const toggleMenu = (open) => {
            const isCurrentlyOpen = mobileDrawer.classList.contains('open');
            const shouldOpen = typeof open === 'boolean' ? open : !isCurrentlyOpen;

            if (shouldOpen) {
                mobileDrawer.classList.add('open');
                mobileToggleBtn.setAttribute('aria-expanded', 'true');
                mobileToggleBtn.setAttribute('aria-label', 'Tutup navigasi');
                // Change icon to close (X)
                const icon = mobileToggleBtn.querySelector('svg');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />';
                }
            } else {
                mobileDrawer.classList.remove('open');
                mobileToggleBtn.setAttribute('aria-expanded', 'false');
                mobileToggleBtn.setAttribute('aria-label', 'Buka navigasi');
                // Change icon back to hamburger
                const icon = mobileToggleBtn.querySelector('svg');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />';
                }
            }
        };

        mobileToggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            toggleMenu();
        });

        // Close on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && mobileDrawer.classList.contains('open')) {
                toggleMenu(false);
            }
        });

        // Close when clicking outside drawer
        document.addEventListener('click', (e) => {
            if (mobileDrawer.classList.contains('open')) {
                if (!mobileDrawer.contains(e.target) && !mobileToggleBtn.contains(e.target)) {
                    toggleMenu(false);
                }
            }
        });
    }
});
