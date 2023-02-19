window.onload = function () {
    document.getElementById('add').addEventListener('submit', function (e) {
        e.preventDefault();

        const fieldNames = {
            title: 'Titolo',
            description: 'Descrizione',
            partecipants: 'Numero partecipanti',
        };

        fetch('controllers/instructor/add.php', {
            method: 'post',
            body: JSON.stringify(Object.fromEntries(new FormData(document.forms.item(0)).entries())),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('form-success').classList.remove('hidden');
                    document.getElementById('form-error').classList.add('hidden');

                    document.getElementById('add').classList.add('hidden');
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