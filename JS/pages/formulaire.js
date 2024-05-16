let AssoCheckbox = document.getElementById('check_asso');
let PartiCheckbox = document.getElementById('check_parti');
let formAlert = document.querySelector('.form__alert');

let form_data = {};

function assoRunder() {
    function firstStep() {
        form_data = {};
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_asso_1step').innerHTML;
        container.innerHTML = form;
        let PartiCheckbox = document.getElementById('check_parti');
        PartiCheckbox.addEventListener('change', partiRunder);
        let nextBtn = document.querySelector('.form__next');
        nextBtn.addEventListener('click', firstStepVerif);

        formAlert.textContent = '';
    }
    firstStep();

    function firstStepVerif() {
        var requiredFields = document.querySelectorAll('.required');
        let flag = false;

        for (var i = 0; i < requiredFields.length; i++) {
            
            let label = requiredFields[i].parentElement.firstChild;

            if (requiredFields[i].value === '') {
                flag = true;
                label.style.textDecoration = 'underline wavy red';
                
            }
            else {
                label.style.textDecoration = '';
            }
        }

        if (flag) {
            formAlert.textContent = 'Veuillez remplir tous les champs obligatoires.';
            return;
        }

        formAlert.textContent = '';

        secondStep();
    }

    function secondStep() {
        form_data.name_asso = document.getElementById('name_asso').value;
        form_data.address_asso = document.getElementById('address_asso').value;
        form_data.name_president = document.getElementById('name_president').value;
        form_data.mail_president = document.getElementById('mail_president').value;
        form_data.tel_president = document.getElementById('tel_president').value;

        form_data.name_contact = document.getElementById('name_contact').value;
        form_data.position_contact = document.getElementById('position_contact').value;
        form_data.mail_contact = document.getElementById('mail_contact').value;
        form_data.tel_contact = document.getElementById('tel_contact').value;

        // Templating 2nd step

        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_asso_finalStep').innerHTML;
        container.innerHTML = form;

    }

    

}




function partiRunder() {
    function firstStep() {
        form_data = {};
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_1step').innerHTML;
        container.innerHTML = form;
        let AssoCheckbox = document.getElementById('check_asso');
        AssoCheckbox.addEventListener('change', assoRunder);
        let nextBtn = document.querySelector('.form__next');
        nextBtn.addEventListener('click', firstStepVerif);

        formAlert.textContent = '';
    }
    firstStep();

    function firstStepVerif() {
        var requiredFields = document.querySelectorAll('.required');
        let flag = false;

        for (var i = 0; i < requiredFields.length; i++) {
            
            let label = requiredFields[i].parentElement.firstChild;

            if (requiredFields[i].value === '') {
                flag = true;
                label.style.textDecoration = 'underline wavy red';
                
            }
            else {
                label.style.textDecoration = '';
            }
        }

        if (flag) {
            formAlert.textContent = 'Veuillez remplir tous les champs obligatoires.';
            return;
        }
        
        formAlert.textContent = '';


        secondStep();
    }

    function secondStep() {
        form_data.name_individual = document.getElementById('name_individual').value;
        form_data.birth_individual = document.getElementById('birth_individual').value;
        form_data.address_individual = document.getElementById('address_individual').value;
        form_data.mail_individual = document.getElementById('mail_individual').value;
        form_data.tel_individual = document.getElementById('tel_individual').value;
        form_data.job_individual = document.getElementById('job_individual').value;

        // Templating 2nd step

        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_2step').innerHTML;
        container.innerHTML = form;

        let previousBtn = document.querySelector('.form__prev');
        let nextBtn = document.querySelector('.form__next');

        previousBtn.addEventListener('click', backToFirstStep);
        nextBtn.addEventListener('click', secondStepVerif);
    }

    function backToFirstStep() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_1step').innerHTML;
        container.innerHTML = form;
        let AssoCheckbox = document.getElementById('check_asso');
        AssoCheckbox.addEventListener('change', assoRunder);
        let nextBtn = document.querySelector('.form__next');
        nextBtn.addEventListener('click', firstStepVerif);

        document.getElementById('name_individual').value = form_data.name_individual;
        document.getElementById('birth_individual').value = form_data.birth_individual;
        document.getElementById('address_individual').value = form_data.address_individual;
        document.getElementById('mail_individual').value = form_data.mail_individual;
        document.getElementById('tel_individual').value = form_data.tel_individual;
        document.getElementById('job_individual').value = form_data.job_individual;
    }

    function secondStepVerif() {
        let checkbox1 = document.querySelector('.imglaw_checkbox1');
        let checkbox2 = document.querySelector('.imglaw_checkbox2');

        if ((checkbox1.checked && checkbox2.checked) || (!checkbox1.checked && !checkbox2.checked)) {
            document.querySelector('.form__li__redCheck').style.display = 'block';
            return;
        }

        document.querySelector('.form__li__redCheck').style.display = 'none';

        thirdStep();
    }

    function thirdStep() {
        let checkbox1 = document.querySelector('.imglaw_checkbox1');
        if (checkbox1.checked) {
            form_data.img_law = 'yes';
        }
        else {
            form_data.img_law = 'no';
        }

        // Templating 3rd step

        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_3step').innerHTML;
        container.innerHTML = form;

        let previousBtn = document.querySelector('.form__prev');
        let midBtn = document.querySelector('.form__mid');
        let nextBtn = document.querySelector('.form__next');

        previousBtn.addEventListener('click', backToSecondStep);
        midBtn.addEventListener('click', skipToFinalStep);
        nextBtn.addEventListener('click', thirdStepVerif);
    }

    function backToSecondStep() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_2step').innerHTML;
        container.innerHTML = form;

        let previousBtn = document.querySelector('.form__prev');
        let nextBtn = document.querySelector('.form__next');

        previousBtn.addEventListener('click', backToFirstStep);
        nextBtn.addEventListener('click', secondStepVerif);
    }

    function skipToFinalStep() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_finalStep').innerHTML;
        container.innerHTML = form;
    }

    function thirdStepVerif() {
        var select = document.querySelector('#members_select');
    
        if (select.value === '') {
            formAlert.textContent = 'Veuillez sélectionner une association.';

            let label = select.parentElement.children[1];
            label.style.textDecoration = 'underline wavy red';
            return;
        }

        formAlert.textContent = '';

        
        endText();
    }

    function endText() {
        let container = document.querySelector('.form__content');
        let form = document.querySelector('#template_parti_endText').innerHTML;
        container.innerHTML = form;

    }

}

AssoCheckbox.addEventListener('change', assoRunder);
PartiCheckbox.addEventListener('change', partiRunder);

