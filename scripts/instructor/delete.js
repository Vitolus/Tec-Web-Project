window.onload = function () {
    document.getElementById('delete').addEventListener('submit', function (e) {
        e.preventDefault();

        fetch('controllers/instructor/delete.php?id=' + new URLSearchParams(window.location.search).get('id'), {
            method: 'post',
            body: JSON.stringify(Object.fromEntries(new FormData(document.forms.item(0)).entries())),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    document.getElementById('form-success').classList.remove('hidden');
                    document.getElementById('form-error').classList.add('hidden');

                    document.getElementById('delete').classList.add('hidden');
                } else {
                    document.getElementById('form-error').classList.remove('hidden');
                    document.getElementById('form-success').classList.add('hidden');
                }
            });
    });
};