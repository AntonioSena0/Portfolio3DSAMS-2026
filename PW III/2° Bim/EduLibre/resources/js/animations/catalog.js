import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    const heroItems = document.querySelectorAll('.catalog-hero > *');
    const filterBar = document.querySelector('.filter-bar');
    const cards = document.querySelectorAll('.catalog-card');

    if (!heroItems.length && !filterBar && !cards.length) {
        return;
    }

    const timeline = gsap.timeline({ defaults: { ease: 'power2.out' } });

    if (heroItems.length) {
        timeline.fromTo(heroItems, { y: 14, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.45, stagger: 0.05 });
    }

    if (filterBar) {
        timeline.fromTo(filterBar, { y: 12, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: 0.4 }, '-=0.18');
    }

    if (cards.length) {
        timeline.fromTo(cards, { y: 14, autoAlpha: 0 }, {
            y: 0,
            autoAlpha: 1,
            duration: 0.36,
            stagger: 0.035,
            clearProps: 'transform',
        }, '-=0.08');
    }

    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => {
            gsap.to(card, { y: -4, duration: 0.2, ease: 'power2.out', overwrite: 'auto' });
        });

        card.addEventListener('mouseleave', () => {
            gsap.to(card, { y: 0, duration: 0.2, ease: 'power2.out', overwrite: 'auto', clearProps: 'transform' });
        });
    });
});

window.catalogFilterAnimation = function() {
    const grid = document.querySelector('.catalog-results');

    if (!grid) {
        return;
    }

    gsap.fromTo(grid, { autoAlpha: 0.55, y: 6 }, { autoAlpha: 1, y: 0, duration: 0.25, ease: 'power2.out' });
};
