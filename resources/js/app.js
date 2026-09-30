// Public website behaviour: Bootstrap components, gallery lightbox and small UI touches.
import '@fontsource-variable/plus-jakarta-sans';
import '@fontsource-variable/playfair-display';
import 'bootstrap-icons/font/bootstrap-icons.min.css';
import * as bootstrap from 'bootstrap';

window.bootstrap = bootstrap;
document.documentElement.classList.add('js');

document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initReveal();
    initBackToTop();
    initLightbox();
});

// Shadow on the sticky header once the page scrolls.
function initHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const update = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
    update();
    window.addEventListener('scroll', update, { passive: true });

    // Close the mobile menu after choosing a link.
    const collapse = document.getElementById('mainNav');
    collapse?.querySelectorAll('a').forEach((link) =>
        link.addEventListener('click', () => bootstrap.Collapse.getInstance(collapse)?.hide())
    );
}

// Fade content in as it enters the viewport.
function initReveal() {
    const items = document.querySelectorAll('.reveal');
    if (!items.length) return;

    if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    items.forEach((el) => observer.observe(el));
}

function initBackToTop() {
    const button = document.querySelector('.back-to-top');
    if (!button) return;

    window.addEventListener('scroll', () => button.classList.toggle('is-visible', window.scrollY > 600), { passive: true });
    button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// The lightbox library is only downloaded on pages that have gallery images.
async function initLightbox() {
    if (!document.querySelector('.glightbox')) return;

    const [{ default: GLightbox }] = await Promise.all([
        import('glightbox'),
        import('glightbox/dist/css/glightbox.min.css'),
    ]);

    GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true, zoomable: false });
}
