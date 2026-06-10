// KPI cards — entrada com bounce sutil
gsap.from('.kpi-card', {
    y: 20,
    opacity: 0,
    duration: 0.5,
    stagger: 0.1,
    ease: 'back.out(1.2)',
    delay: 0.2
});

// Progress bars no dashboard — animar ao entrar na viewport
gsap.utils.toArray('.progress-bar-fill').forEach(bar => {
    const target = bar.dataset.progress + '%';
    gsap.from(bar, {
        width: '0%',
        duration: 1,
        ease: 'power2.out',
        scrollTrigger: {
            trigger: bar,
            start: 'top 85%',
            once: true
        }
    });
    gsap.set(bar, { width: target });
});