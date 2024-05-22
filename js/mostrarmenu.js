document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const menuItems = document.querySelector('.menu-items');

    menuToggle.addEventListener('click', function () {
        if (window.innerWidth <= 768) {
            menuItems.classList.toggle('show');
        }
    });
});