document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', handleLikeClick)
    })
})

async function handleLikeClick(e) {

    e.preventDefault()

    const btn = e.currentTarget
    const form = btn.closest('.like-form')
    const postId = form.dataset.postId
    const isLiked = btn.dataset.liked === 'true';
    const icon = btn.querySelector('i')
    const countEl = form.querySelector('.like-count')
    const currentLikes = parseInt(countEl.dataset.likes);

    updateLikeUI(btn, icon, !isLiked)

    try {

        const formData = new FormData(form)
        formData.append('_method', isLiked ? 'DELETE' : 'POST')

        const response = await fetch(`/posts/${postId}` + (isLiked ? '/unlike' : '/like'), {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })

        if(!response.ok){
            throw new Error('Erro no servidor')
        }

        const data = await response.json()

        countEl.dataset.likes = data.likes
        countEl.textContent = data.likes

    } catch (error) {

        updateLikeUI(btn, icon, isLiked)

        console.error('Erro like: ', error)

    }

}

function updateLikeUI(btn, icon, liked){

    btn.dataset.liked = liked.toString()

    btn.classList.remove('text-red-500');
    icon.classList.remove('bi-heart-fill', 'bi-heart');

    if (liked) {
        btn.classList.add('text-red-500');
        icon.className = 'bi-heart-fill';
    } else {
        icon.className = 'bi-heart';
    }

}
