document.addEventListener('DOMContentLoaded', () => {
    // 1. Mobile Menu Toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileDrawer = document.getElementById('mobileDrawer');

    if (mobileToggle && mobileDrawer) {
        mobileToggle.addEventListener('click', () => {
            mobileDrawer.classList.toggle('active');
        });
    }

    // 2. Global Image Fallback Handler
    window.handleImageError = function(img) {
        img.onerror = null;
        img.src = '/images/placeholder.svg';
    };

    window.handleAvatarError = function(img) {
        img.onerror = null;
        img.src = '/images/avatar-placeholder.svg';
    };

    // 3. Live Image Preview for Admin URL Inputs
    const setupLivePreview = (inputId, previewId) => {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        if (input && preview) {
            const updatePreview = () => {
                const url = input.value.trim();
                if (url) {
                    preview.src = url;
                    preview.style.display = 'block';
                }
            };

            input.addEventListener('input', updatePreview);
            input.addEventListener('blur', updatePreview);
        }
    };

    setupLivePreview('image_url', 'image_url_preview');
    setupLivePreview('avatar_url', 'avatar_url_preview');
    setupLivePreview('photo_url', 'photo_url_preview');

    // 4. Customer Review Photo Modal / Lightbox
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');
    const modalClose = document.getElementById('modalClose');

    if (modal && modalImg) {
        document.querySelectorAll('.review-photo-thumb').forEach(thumb => {
            thumb.addEventListener('click', () => {
                modalImg.src = thumb.src;
                modal.classList.add('active');
            });
        });

        if (modalClose) {
            modalClose.addEventListener('click', () => {
                modal.classList.remove('active');
            });
        }

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    }
});
