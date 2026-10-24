document.addEventListener('DOMContentLoaded', () => {
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const iconMenu = menuBtn.querySelector('.icon-menu');
    const iconClose = menuBtn.querySelector('.icon-close');

    if (menuBtn && mobileMenu) {
        menuBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                setTimeout(() => {
                    mobileMenu.classList.remove('opacity-0');
                    mobileMenu.classList.add('opacity-100');
                }, 10);
                iconMenu.classList.add('hidden');
                iconClose.classList.remove('hidden');
            } else {
                mobileMenu.classList.remove('opacity-100');
                mobileMenu.classList.add('opacity-0');
                setTimeout(() => {
                    mobileMenu.classList.add('hidden');
                }, 500);
                iconMenu.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        });
    }
});