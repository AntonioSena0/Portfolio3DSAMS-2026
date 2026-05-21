document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.comment-form, [id^="comment-form-"]').forEach(form => {
        form.addEventListener('submit', handleCommentSubmit)
    })
})

async function handleCommentSubmit(e) {
    e.preventDefault()

    const form = e.currentTarget
    const postId = form.dataset.postId
    const submitBtn = form.querySelector('button[type="submit"]')
    const textarea = form.querySelector('textarea[name="body"]')
    const commentsContainer = form.parentElement.querySelector('.max-h-\\[300px\\].overflow-y-auto')

    clearCommentError(textarea)
    setCommentLoading(submitBtn, true)

    const formData = new FormData(form)

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })

        if (!response.ok) {
            const data = await response.json()
            if (data.errors && data.errors.body) {
                showCommentError(textarea, data.errors.body[0])
            }
            return
        }

        const data = await response.json()

        appendCommentHTML(commentsContainer, data)
        updateCommentCount(postId)

        textarea.value = ''

    } catch (error) {
        console.error('Erro comentário: ', error)
    } finally {
        setCommentLoading(submitBtn, false)
    }
}

function appendCommentHTML(container, data) {

    const emptyMsg = container.querySelector('.no-comments-msg')
    if (emptyMsg) emptyMsg.remove()

    const commentHtml = `
        <div class="bg-gray-50 p-3 rounded-lg">
            <div class="flex flex-1 space-x-1 items-center mb-2">
                <div class="w-6 h-6 rounded-full bg-linear-to-r from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm shrink-0">
                    ${data.user.username[0]?.toUpperCase() || 'U'}
                </div>
                <p class="text-sm font-semibold text-gray-800">${data.user.username}</p>
                <p class="text-xs text-gray-500">${formatDate(data.created_at)}</p>
            </div>
            <p class="text-sm text-gray-700">${data.body}</p>
        </div>
    `
    container.insertAdjacentHTML('afterbegin', commentHtml)
}

function setCommentLoading(btn, loading) {
    if (!btn) return
    if (loading) {
        btn.dataset.originalText = btn.textContent
        btn.disabled = true
        btn.textContent = 'Enviando...'
    } else {
        btn.disabled = false
        if (btn.dataset.originalText) {
            btn.textContent = btn.dataset.originalText
        }
    }
}

function showCommentError(textarea, message) {
    if (!textarea) return
    const errorDiv = document.createElement('div')
    errorDiv.className = 'error text-red-500 text-xs mt-1'
    errorDiv.textContent = message
    textarea.parentNode.insertBefore(errorDiv, textarea.nextSibling)
    textarea.classList.add('border-red-500')
}

function clearCommentError(textarea) {
    if (!textarea) return
    textarea.classList.remove('border-red-500')
    const errorEl = textarea.nextElementSibling
    if (errorEl && errorEl.classList.contains('error')) {
        errorEl.remove()
    }
}

function formatDate(dateStr) {
    const date = new Date(dateStr)
    const day = String(date.getDate()).padStart(2, '0')
    const month = String(date.getMonth() + 1).padStart(2, '0')
    const year = String(date.getFullYear()).slice(-2)
    const hours = String(date.getHours()).padStart(2, '0')
    const minutes = String(date.getMinutes()).padStart(2, '0')
    return `${day}/${month}/${year} ${hours}:${minutes}`
}

function updateCommentCount(postId) {
    const countEl = document.querySelector(`.comment-count[data-post-id="${postId}"]`)
    if (!countEl) return

    let current = parseInt(countEl.dataset.comments || '0')
    current += 1

    countEl.dataset.comments = current.toString()
    countEl.textContent = current
}
