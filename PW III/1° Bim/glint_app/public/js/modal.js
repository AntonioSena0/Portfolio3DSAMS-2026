document.addEventListener('DOMContentLoaded', function () {
    const modals       = document.querySelectorAll('.js-modal')
    const openButtons  = document.querySelectorAll('.js-open-modal')
    const closeButtons = document.querySelectorAll('.js-close-modal')

    function openModalById(id) {
        const modal = document.getElementById(id)
        if (!modal) return
        modal.classList.remove('hidden')
        modal.classList.add('flex')
        document.body.classList.add('overflow-hidden')
    }

    function closeModal(modal) {
        modal.classList.add('hidden')
        modal.classList.remove('flex')
        document.body.classList.remove('overflow-hidden')
    }

    openButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-modal-target')
            if (targetId) openModalById(targetId)
        })
    })

    closeButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const modal = this.closest('.js-modal')
            if (modal) closeModal(modal)
        })
    })

    modals.forEach(modal => {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) closeModal(modal)
        })
    })

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) closeModal(modal)
            })
        }
    })
})
