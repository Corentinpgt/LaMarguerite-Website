let AssoCheckbox = document.getElementById('check_asso');
let ParticulierCheckbox = document.getElementById('check_particulier');

function assoRunder() {
    function firstStep() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('').innerHTML;
        container.innerHTML = form;
        ParticulierCheckbox.addEventListener('change', particulierRunder);

    }
    firstStep();
}

function particulierRunder() {
    function firstStep() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('').innerHTML;
        container.innerHTML = form;
        AssoCheckbox.addEventListener('change', assoRunder);

    }
    firstStep();
}

AssoCheckbox.addEventListener('change', assoRunder);
ParticulierCheckbox.addEventListener('change', particulierRunder);



// var button = document.querySelector('.submit-button');

// button.addEventListener('click', function(e) {

//     var requiredFields = document.querySelectorAll('.required');

//     for (var i = 0; i < requiredFields.length; i++) {

//         if (requiredFields[i].value === '') {

//             alert('Veuillez remplir tous les champs requis.');
//             break;
//         }
//     }
// });
