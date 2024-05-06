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


// Templatage missions

let missions = document.querySelectorAll('.temp__mission');
let container = document.querySelector('#container_missions');
let nav = document.querySelector('#missions_nav');


function handlerMission(ev) {

    let target = ev.target;
    let id = target.dataset.id;
    let mission = missions[id];
    container.innerHTML = mission.innerHTML;

    // Add Navigation

    var row = container.querySelector('.row');
    nav.classList.remove('nav__none')
    row.appendChild(nav);
    console.log(nav);

    let buttons = document.querySelectorAll('.mission_btn');
    buttons.forEach(btn => {
        if (id === btn.dataset.id) {
            btn.classList.add('missions-selected');
        }
        else {
            btn.classList.remove('missions-selected');
        }
    });

    listerner();

    
    
}

function listerner() {
    if (window.innerWidth > 992) {
        nav.style.display = 'flex';
        let buttons = document.querySelectorAll('.mission_btn');
        buttons.forEach(btn => {
            btn.addEventListener("click", handlerMission)
        });
    }
    else {
        nav.style.display = 'none';
    }
}

listerner();


window.addEventListener('resize', listerner);