// Carrousel Light Mission

let index = 0;
const images = document.querySelectorAll('.lightMission__carrouselImage');
const buttons = document.querySelectorAll('.lightMission__controlsBtn');

setInterval(() => {
    images[index].style.display = 'none';
    index = (index + 1) % images.length;
    images[index].style.display = 'block';
}, 6000);

buttons.forEach((button, buttonIndex) => {
    button.addEventListener('click', () => {
        images[index].style.display = 'none';
        index = buttonIndex;
        images[index].style.display = 'block';
    });
});


// Carrousel Partenaires Footer

var index2 = 0;
var images2 = document.querySelectorAll('.carousel__images img');
var prevButton = document.querySelector('.carousel__button--prev');
var nextButton = document.querySelector('.carousel__button--next');

function showImage(i) {
    images2.forEach(img => img.style.transform = 'translateX(-' + i * 100 + '%)');
}

prevButton.addEventListener('click', function() {
    index2 = (index2 - 1 + images2.length) % images2.length;
    showImage(index2);
});

nextButton.addEventListener('click', function() {
    index2 = (index2 + 1) % images2.length;
    showImage(index2);
});

setInterval(function() {
    index2 = (index2 + 1) % images2.length;
    showImage(index2);
}, 5000);