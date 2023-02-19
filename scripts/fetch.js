window.onload = function () {
    const id = document.forms.item(0).id;
    let path;
    let fieldNames;

    switch (id) {
        case "modify-profile":
            path = "profile-modify.php";
            fieldNames = {
                username: 'Nome utente',
                email: 'E-mail',
                password: 'Password',
                cell: 'Telefono cellulare',
            };
            break;
        case "login":
            path = "login.php";
            fieldNames = {
                username: 'Nome utente',
                password: 'Password',
            };
            break;
        case "register":
            path = "register.php";
            fieldNames = {
                name: 'Nome',
                surname: 'Cognome',
                gender: 'Genere',
                'phone-number': 'Telefono cellulare',
                username: 'Nome utente',
                'email-address': 'E-mail',
                password: 'Password',
            };
            break;
        case "contacts":
            path = "submit_contacts.php";
            fieldNames = {
                name: 'Nome',
                surname: 'Cognome',
                'email-address': 'E-mail',
                'phone-number': 'Telefono cellulare',
            };
            break;
    }

    document.getElementById(id).addEventListener('submit', function (e) {
        e.preventDefault();
        fetch(`controllers/${path}`, {
            method: 'post',
            body: JSON.stringify(Object.fromEntries(new FormData(document.forms.item(0)).entries())),
        }).then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('form-success').classList.remove('hidden');
                    document.getElementById('form-error').classList.add('hidden');

                    document.getElementById(id).classList.add('hidden');

                    if (id === 'login') {
                        document.getElementById('register').classList.add('hidden');
                    }
                } else {
                    document.getElementById('form-error').classList.remove('hidden');
                    document.getElementById('form-success').classList.add('hidden');

                    if (data.errors) {
                        document.getElementById('form-error').innerHTML = validationErrorsToString(data.errors, fieldNames);
                    } else if (data.errorString) {
                        document.getElementById('form-error').innerHTML = data.errorString;
                    }
                }
            });
    });
};