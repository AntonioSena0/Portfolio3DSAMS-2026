document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('publish-image')
    const previewImg = document.getElementById('publish-image-preview')
    const container = document.getElementById('file-cont')
    const removeBtn = document.getElementById('publish-image-remove')

    const previewWrap = document.getElementById('publish-image-preview-wrap')

    if (fileInput && previewImg && container && removeBtn && previewWrap) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0]
            if (!file) {
                previewImg.src = ''
                previewWrap.classList.add('hidden')
                removeBtn.classList.add('hidden')
                container.classList.remove('hidden')
                return
            }

            const reader = new FileReader()

            reader.addEventListener('load', function () {
                previewImg.src = reader.result
                previewWrap.classList.remove('hidden')
                removeBtn.classList.remove('hidden')
                container.classList.add('hidden')
            })

            reader.readAsDataURL(file)
        })

        removeBtn.addEventListener('click', function () {
            fileInput.value = ''
            previewImg.src = ''
            previewWrap.classList.add('hidden')
            removeBtn.classList.add('hidden')
            container.classList.remove('hidden')
        })
    }
})
