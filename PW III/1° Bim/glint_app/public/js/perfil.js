document.addEventListener('DOMContentLoaded', function () {

    const tabButtons = document.querySelectorAll('.tab-btn')
    const tabPanels = document.querySelectorAll('.tab-panel')

    if (tabButtons.length && tabPanels.length) {
        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-tab-target')

                tabButtons.forEach(b => {
                    b.classList.remove('text-blue-600', 'border-blue-600')
                    b.classList.add('text-gray-500', 'border-transparent')
                })
                btn.classList.add('text-blue-600', 'border-blue-600')
                btn.classList.remove('text-gray-500', 'border-transparent')

                tabPanels.forEach(panel => {
                    if (panel.id === targetId) {
                        panel.classList.remove('hidden')
                    } else {
                        panel.classList.add('hidden')
                    }
                })
            })
        })
    }
})
