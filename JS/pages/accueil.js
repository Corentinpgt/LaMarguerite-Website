// Notification page d'accueil


document.getElementById('notification_cross').addEventListener('change', function() {
    if (this.checked) {
        this.parentNode.style.maxHeight = '0';
        // this.parentNode.style.padding = '0';
        this.parentNode.style.marginBottom = '0';
    }
});