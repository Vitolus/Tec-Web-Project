window.onload = function () {
    document.getElementById('modify').addEventListener('submit', function (e) {
        e.preventDefault();

        const fieldNames = {
            durata: 'Durata',
            dataInizio: 'Data di inizio',
            dataFine: 'Data di fine',
        };

        fetch('controllers/admins/modify-sub.php?id=' + new URLSearchParams(window.location.search).get('id'), {
            method: 'post',
            body: JSON.stringify(Object.fromEntries(new FormData(document.forms.item(0)).entries())),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('form-success').classList.remove('hidden');
                    document.getElementById('form-error').classList.add('hidden');

                    document.getElementById('modify').classList.add('hidden');
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