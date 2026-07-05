import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import focus from '@alpinejs/focus';
import EmblaCarousel from 'embla-carousel';
import Autoplay from 'embla-carousel-autoplay';

Alpine.plugin(collapse);
Alpine.plugin(focus);

// Initialise Embla sliders (Top Rated Apps) once the DOM is ready.
function initSliders() {
    document.querySelectorAll('[data-embla]').forEach((root) => {
        if (root.dataset.emblaReady) return;
        const viewport = root.querySelector('.embla__viewport');
        if (!viewport) return;
        root.dataset.emblaReady = '1';

        const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const plugins = reduce ? [] : [Autoplay({ delay: 3800, stopOnMouseEnter: true, stopOnInteraction: false })];

        const embla = EmblaCarousel(viewport, { loop: true, align: 'start', dragFree: false, containScroll: 'trimSnaps' }, plugins);

        const prev = root.querySelector('.tr-prev');
        const next = root.querySelector('.tr-next');
        prev && prev.addEventListener('click', () => embla.scrollPrev());
        next && next.addEventListener('click', () => embla.scrollNext());

        // Dots
        const dotsWrap = root.querySelector('.tr-pagination');
        let dots = [];
        if (dotsWrap) {
            const render = () => {
                dotsWrap.innerHTML = '';
                dots = embla.scrollSnapList().map((_, i) => {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'embla__dot';
                    b.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                    b.addEventListener('click', () => embla.scrollTo(i));
                    dotsWrap.appendChild(b);
                    return b;
                });
            };
            const select = () => {
                const s = embla.selectedScrollSnap();
                dots.forEach((d, i) => d.classList.toggle('is-active', i === s));
            };
            render();
            select();
            embla.on('select', select);
            embla.on('reInit', () => { render(); select(); });
        }
    });
}

if (document.readyState !== 'loading') {
    initSliders();
} else {
    document.addEventListener('DOMContentLoaded', initSliders);
}

/**
 * Global theme store — persisted to localStorage, applied to <html class="dark">.
 * The initial class is set inline in <head> to avoid a flash of the wrong theme.
 */
Alpine.store('theme', {
    dark: document.documentElement.classList.contains('dark'),

    toggle() {
        this.dark = !this.dark;
        this.apply();
    },

    apply() {
        document.documentElement.classList.toggle('dark', this.dark);
        try {
            localStorage.setItem('yono-theme', this.dark ? 'dark' : 'light');
        } catch (e) {
            /* storage may be unavailable */
        }
    },
});

/**
 * Lightweight toast helper — listens for window `toast` events.
 */
Alpine.data('toaster', () => ({
    items: [],
    push(detail) {
        const id = Date.now() + Math.random();
        this.items.push({ id, ...detail });
        setTimeout(() => this.dismiss(id), 4000);
    },
    dismiss(id) {
        this.items = this.items.filter((t) => t.id !== id);
    },
}));

window.Alpine = Alpine;
Alpine.start();
