function initFollowButtons() {
    const forms = document.querySelectorAll('[data-follow-form], [data-follow-form-mini], [data-follow-form-mini-list]');

    forms.forEach(form => {
        if (form._followHandler) {
            form.removeEventListener('submit', form._followHandler);
        }

        form._followHandler = function(e) {
            e.preventDefault();

            const button = form.querySelector('button');
            if (!button) return;

            const originalText = button.textContent;
            const originalClass = button.className;

            button.disabled = true;
            button.textContent = '...';

            const action = form.getAttribute('action');
            if (!action) return;

            const isUnfollow = action.includes('/unfollow');
            const method = isUnfollow ? 'DELETE' : 'POST';
            const userId = form.getAttribute('data-follow-user-id');
            if (!userId) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ||
                            document.querySelector('input[name="_token"]')?.value;
            if (!csrfToken) return;

            fetch(action, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    button.disabled = false;
                    button.className = originalClass;

                    if (data.success) {
                        const allForms = document.querySelectorAll(`[data-follow-user-id="${userId}"]`);
                        allForms.forEach(formElement => {
                            const btn = formElement.querySelector('button');
                            if (!btn) return;

                            const currentAction = formElement.getAttribute('action');
                            if (!currentAction) return;

                            const isCurrentlyUnfollow = currentAction.includes('/unfollow');

                            if (isCurrentlyUnfollow) {
                                formElement.setAttribute('action', currentAction.replace('/unfollow', '/follow'));
                                const methodInput = formElement.querySelector('input[name="_method"]');
                                if (methodInput) methodInput.remove();

                                btn.textContent = 'Seguir';

                                if (btn.classList.contains('px-3')) {
                                    btn.className = 'cursor-pointer px-3 py-1 text-xs rounded-full bg-blue-600 text-white hover:bg-blue-700 font-semibold';
                                } else {
                                    btn.className = 'cursor-pointer px-4 py-1.5 rounded-full bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700';
                                }
                            } else {
                                formElement.setAttribute('action', currentAction.replace('/follow', '/unfollow'));
                                if (!formElement.querySelector('input[name="_method"]')) {
                                    const methodInput = document.createElement('input');
                                    methodInput.type = 'hidden';
                                    methodInput.name = '_method';
                                    methodInput.value = 'DELETE';
                                    formElement.appendChild(methodInput);
                                }

                                btn.textContent = 'Seguindo';

                                if (btn.classList.contains('px-3')) {
                                    btn.className = 'cursor-pointer px-3 py-1 text-xs rounded-full border border-gray-300 hover:bg-gray-50 font-semibold';
                                } else {
                                    btn.className = 'cursor-pointer px-4 py-1.5 rounded-full border border-gray-300 text-sm font-semibold hover:bg-gray-50';
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    button.disabled = false;
                    button.textContent = originalText;
                    button.className = originalClass;
                    console.error('Erro:', error);
                });
        };

        form.addEventListener('submit', form._followHandler);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initFollowButtons);
} else {
    initFollowButtons();
}
