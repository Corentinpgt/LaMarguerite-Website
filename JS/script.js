// Parteners carousel

$(document).ready(function(){
    $("#carouselExample").owlCarousel({
        items: 3,
        loop: true,
        margin: 0,
        dots : false,
        nav: true,
        navText: ["<div class='nav-btn prev-slide'></div>", "<div class='nav-btn next-slide'></div>"],
        slideBy: 1, // Nombre d'items à déplacer lors de la navigation
        autoplay: true, // Active le défilement automatique
        autoplayTimeout: 3000, // Définit le délai de défilement automatique à 2 secondes
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 3
            }
        }
    });
});

// Header change when scrolling

window.addEventListener('scroll', function() {
    var header = document.querySelector('.header');
    header.classList.toggle('sticky', window.scrollY >= 50);
});






