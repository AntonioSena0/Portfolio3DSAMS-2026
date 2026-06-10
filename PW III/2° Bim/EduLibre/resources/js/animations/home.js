import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

document.addEventListener('DOMContentLoaded', () => {
    // Hero — entrada sequencial
    const heroTl = gsap.timeline({ delay: 0.1 });
    heroTl
        .from('.hero-eyebrow', {
            y: 20, opacity: 0, duration: 0.5, ease: 'power2.out'
        })
        .from('.hero-title', {
            y: 30, opacity: 0, duration: 0.6, ease: 'power2.out'
        }, '-=0.3')
        .from('.hero-subtitle', {
            y: 20, opacity: 0, duration: 0.5, ease: 'power2.out'
        }, '-=0.3')
        .from('.hero-cta', {
            y: 15, opacity: 0, duration: 0.4, ease: 'power2.out', stagger: 0.1
        }, '-=0.2')
        .from('.hero-image', {
            scale: 0.95, opacity: 0, duration: 0.8, ease: 'power2.out'
        }, '-=0.5');

    // Seção de stats — contagem animada
    ScrollTrigger.create({
        trigger: '.stats-section',
        start: 'top 75%',
        once: true,
        onEnter: () => {
            document.querySelectorAll('[data-count]').forEach(el => {
                const target = parseInt(el.dataset.count);
                gsap.to({ val: 0 }, {
                    val: target,
                    duration: 1.5,
                    ease: 'power2.out',
                    onUpdate: function() {
                        el.textContent = Math.round(this.targets()[0].val).toLocaleString('pt-BR');
                    }
                });
            });
        }
    });

    // Reveal de cards com stagger no scroll
    gsap.utils.toArray('.reveal-card').forEach((card, i) => {
        ScrollTrigger.create({
            trigger: card,
            start: 'top 85%',
            once: true,
            onEnter: () => {
                gsap.from(card, {
                    y: 30,
                    opacity: 0,
                    duration: 0.55,
                    ease: 'power2.out',
                    delay: (i % 3) * 0.08 // stagger por posição na linha
                });
            }
        });
    });

    // Seção de features — linha de progresso
    ScrollTrigger.create({
        trigger: '.features-section',
        start: 'top 60%',
        once: true,
        onEnter: () => {
            gsap.from('.feature-item', {
                x: -20,
                opacity: 0,
                duration: 0.5,
                stagger: 0.12,
                ease: 'power2.out'
            });
        }
    });
});