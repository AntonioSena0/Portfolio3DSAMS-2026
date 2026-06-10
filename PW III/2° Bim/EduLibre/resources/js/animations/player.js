// Barra de progresso do vídeo
window.animateProgressBar = function(percent) {
    gsap.to('.video-progress-fill', {
        width: percent + '%',
        duration: 0.5,
        ease: 'power1.inOut'
    });
};

// Marcador de vídeo concluído
window.showCompletionBadge = function() {
    const badge = document.querySelector('.completion-badge');
    gsap.timeline()
        .set(badge, { display: 'flex', scale: 0.7, opacity: 0 })
        .to(badge, { scale: 1, opacity: 1, duration: 0.4, ease: 'back.out(1.7)' })
        .to(badge, { opacity: 0, duration: 0.3, delay: 2.5 });
};

// Transição entre vídeos da playlist
window.transitionToNextVideo = function(callback) {
    const player = document.querySelector('.video-wrapper');
    gsap.timeline()
        .to(player, { opacity: 0, scale: 0.98, duration: 0.25, ease: 'power2.in' })
        .call(callback)
        .to(player, { opacity: 1, scale: 1, duration: 0.3, ease: 'power2.out' });
};