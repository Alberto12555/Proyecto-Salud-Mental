// menu-toggle.js

document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.menu-toggle').addEventListener('click', function() {
        document.querySelector('.navbar').classList.toggle('active');
    });
});
